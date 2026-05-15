<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InstallController extends Controller
{
    public function index()
    {
        return view('install.wizard');
    }

    /**
     * Test database connection (called from wizard step 2).
     * Wizard sends JSON: { host, port, name, username, password }
     */
    public function testConnection(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        $host     = $data['host']     ?? '';
        $port     = $data['port']     ?? 3306;
        $name     = $data['name']     ?? '';
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (!$host || !$name || !$username) {
            return response()->json(['success' => false, 'message' => 'Host, database name and username are required.'], 422);
        }

        try {
            new \PDO(
                "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
                $username,
                $password,
                [\PDO::ATTR_TIMEOUT => 5, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            return response()->json(['success' => true, 'message' => 'Connection successful!']);
        } catch (\PDOException $e) {
            return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()], 422);
        }
    }

    /**
     * Run the full installation.
     * Wizard sends JSON: { language, db:{host,port,name,username,password}, site:{name,url}, admin:{name,email,password,confirm} }
     */
    public function install(Request $request): JsonResponse
    {
        $data = $request->json()->all();

        $locale       = $data['language']          ?? 'ar';
        $db           = $data['db']                ?? [];
        $site         = $data['site']              ?? [];
        $admin        = $data['admin']             ?? [];

        // Basic validation
        $errors = [];
        if (!in_array($locale, ['ar', 'en', 'nl', 'de']))     $errors[] = 'Invalid locale.';
        if (empty($db['host']))                                 $errors[] = 'DB host is required.';
        if (empty($db['name']))                                 $errors[] = 'DB name is required.';
        if (empty($db['username']))                             $errors[] = 'DB username is required.';
        if (empty($site['name']))                               $errors[] = 'Site name is required.';
        if (empty($site['url']))                                $errors[] = 'Site URL is required.';
        if (empty($admin['name']))                              $errors[] = 'Admin name is required.';
        if (empty($admin['email']))                             $errors[] = 'Admin email is required.';
        if (empty($admin['password']))                          $errors[] = 'Admin password is required.';
        if (strlen($admin['password'] ?? '') < 8)              $errors[] = 'Password must be at least 8 characters.';
        if (($admin['password'] ?? '') !== ($admin['confirm'] ?? '')) $errors[] = 'Passwords do not match.';

        if (!empty($errors)) {
            return response()->json(['success' => false, 'message' => implode(' ', $errors)], 422);
        }

        try {
            // Update .env file
            $this->updateEnv([
                'DB_HOST'     => $db['host'],
                'DB_PORT'     => $db['port'] ?? 3306,
                'DB_DATABASE' => $db['name'],
                'DB_USERNAME' => $db['username'],
                'DB_PASSWORD' => $db['password'] ?? '',
                'APP_URL'     => rtrim($site['url'], '/'),
                'APP_NAME'    => '"' . addslashes($site['name']) . '"',
                'APP_LOCALE'  => $locale,
            ]);

            // Reconfigure the live database connection
            config([
                'database.connections.mysql.host'     => $db['host'],
                'database.connections.mysql.port'     => $db['port'] ?? 3306,
                'database.connections.mysql.database' => $db['name'],
                'database.connections.mysql.username' => $db['username'],
                'database.connections.mysql.password' => $db['password'] ?? '',
            ]);

            DB::purge('mysql');
            DB::reconnect('mysql');

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // Create roles and permissions
            $this->createRolesAndPermissions();

            // Create admin user
            $adminUser = User::create([
                'name'     => $admin['name'],
                'email'    => $admin['email'],
                'password' => Hash::make($admin['password']),
            ]);
            $adminUser->assignRole('super_admin');

            // Seed default settings
            $this->seedDefaultSettings($site['name'], $site['url'], $admin['email'], $locale);

            // Generate brand CSS
            $this->generateBrandCss('#FF8528');

            // Create storage link (silently ignore if already exists)
            try {
                Artisan::call('storage:link', ['--force' => true]);
            } catch (\Exception $e) {
                // ignore
            }

            // Run default content seeder
            Artisan::call('db:seed', ['--class' => 'DefaultContentSeeder', '--force' => true]);

            // Create installed lock file
            file_put_contents(storage_path('installed'), now()->toDateTimeString());

            return response()->json(['success' => true, 'message' => 'Installation complete!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Installation failed: ' . $e->getMessage()], 500);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createRolesAndPermissions(): void
    {
        $permissions = [
            'manage users',
            'manage roles',
            'edit content',
            'manage media',
            'manage settings',
            'view analytics',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        // Flush Spatie cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $editor     = Role::findOrCreate('editor',      'web');
        $viewer     = Role::findOrCreate('viewer',      'web');

        $superAdmin->givePermissionTo(Permission::all());
        $editor->givePermissionTo(['edit content', 'manage media']);
        $viewer->givePermissionTo(['view analytics']);
    }

    private function seedDefaultSettings(string $siteName, string $siteUrl, string $adminEmail, string $locale): void
    {
        $defaults = [
            ['key' => 'site_name',         'value' => $siteName,             'group' => 'general'],
            ['key' => 'site_url',          'value' => rtrim($siteUrl, '/'),  'group' => 'general'],
            ['key' => 'admin_email',       'value' => $adminEmail,           'group' => 'general'],
            ['key' => 'default_locale',    'value' => $locale,               'group' => 'general'],
            ['key' => 'timezone',          'value' => 'Asia/Riyadh',         'group' => 'general'],
            ['key' => 'ga_id',             'value' => '',                    'group' => 'general'],
            ['key' => 'maintenance_mode',  'value' => '0',                   'group' => 'general'],
            ['key' => 'brand_color',       'value' => '#FF8528',             'group' => 'appearance'],
            ['key' => 'dark_mode_default', 'value' => 'light',               'group' => 'appearance'],
            ['key' => 'font_family',       'value' => 'IBM Plex Sans Arabic','group' => 'appearance'],
            ['key' => 'logo_path',         'value' => '',                    'group' => 'appearance'],
        ];

        foreach ($defaults as $s) {
            Setting::set($s['key'], $s['value'], $s['group']);
        }
    }

    private function generateBrandCss(string $color): void
    {
        $hex = ltrim($color, '#');
        $r   = (int) hexdec(substr($hex, 0, 2));
        $g   = (int) hexdec(substr($hex, 2, 2));
        $b   = (int) hexdec(substr($hex, 4, 2));

        $darken  = fn($r, $g, $b, $p) => sprintf('#%02x%02x%02x', max(0, (int) ($r * (1 - $p))), max(0, (int) ($g * (1 - $p))), max(0, (int) ($b * (1 - $p))));
        $lighten = fn($r, $g, $b, $p) => sprintf('#%02x%02x%02x', min(255, (int) ($r + (255 - $r) * $p)), min(255, (int) ($g + (255 - $g) * $p)), min(255, (int) ($b + (255 - $b) * $p)));

        $css = ":root {\n"
            . "    --brand: {$color};\n"
            . "    --brand-600: {$darken($r,$g,$b,0.08)};\n"
            . "    --brand-700: {$darken($r,$g,$b,0.15)};\n"
            . "    --brand-50: {$lighten($r,$g,$b,0.95)};\n"
            . "    --brand-100: {$lighten($r,$g,$b,0.85)};\n"
            . "}\n";

        @mkdir(public_path('css'), 0755, true);
        file_put_contents(public_path('css/brand.css'), $css);
    }

    private function updateEnv(array $data): void
    {
        $envPath = base_path('.env');
        $content = file_exists($envPath) ? file_get_contents($envPath) : '';

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $line    = "{$key}={$value}";

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $line, $content);
            } else {
                $content .= "\n{$line}";
            }
        }

        file_put_contents($envPath, $content);
    }
}
