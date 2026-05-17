<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // -------------------------------------------------------------------------
    // General
    // -------------------------------------------------------------------------

    public function general()
    {
        $settings = Setting::getGroup('general');

        return view('admin.settings.general', compact('settings'));
    }

    public function saveGeneral(Request $request)
    {
        $request->validate([
            'site_name'        => 'required|string|max:100',
            'site_url'         => 'required|url',
            'default_locale'   => 'required|in:ar,en,nl,de',
            'timezone'         => 'required|string',
            'ga_id'            => 'nullable|string',
            'maintenance_mode' => 'nullable',
        ]);

        Setting::set('site_name',        $request->input('site_name'),        'general');
        Setting::set('site_url',         $request->input('site_url'),         'general');
        Setting::set('default_locale',   $request->input('default_locale'),   'general');
        Setting::set('timezone',         $request->input('timezone'),         'general');
        Setting::set('ga_id',            $request->input('ga_id', ''),        'general');
        Setting::set('maintenance_mode', $request->boolean('maintenance_mode') ? '1' : '0', 'general');

        ActivityLog::record('General settings updated', 'settings');

        return back()->with('success', 'تم حفظ الإعدادات العامة بنجاح.');
    }

    // -------------------------------------------------------------------------
    // Appearance
    // -------------------------------------------------------------------------

    public function appearance()
    {
        $settings   = Setting::getGroup('appearance');
        $mediaItems = Media::orderBy('created_at', 'desc')->get();

        return view('admin.settings.appearance', compact('settings', 'mediaItems'));
    }

    public function saveAppearance(Request $request)
    {
        $request->validate([
            'brand_color'           => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'dark_mode_default'     => 'required|in:light,dark,system',
            'font_family'           => 'required|string',
            'logo_url'              => 'nullable|string|max:500',
            'dark_logo_url'         => 'nullable|string|max:500',
        ]);

        Setting::set('brand_color',       $request->input('brand_color'),       'appearance');
        Setting::set('dark_mode_default', $request->input('dark_mode_default'), 'appearance');
        Setting::set('font_family',       $request->input('font_family'),       'appearance');

        if ($request->has('logo_url')) {
            Setting::set('logo_url', $request->input('logo_url', ''), 'appearance');
        }

        if ($request->has('dark_logo_url')) {
            Setting::set('dark_logo_url', $request->input('dark_logo_url', ''), 'appearance');
        }

        // Handle uploaded logo
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('logo_url', asset('storage/' . $path), 'appearance');
        }

        $this->generateBrandCss($request->input('brand_color'));

        ActivityLog::record('Appearance settings updated', 'settings');

        return back()->with('success', 'تم حفظ إعدادات المظهر بنجاح.');
    }

    // -------------------------------------------------------------------------
    // SEO
    // -------------------------------------------------------------------------

    public function seo()
    {
        $langs       = ['ar', 'en', 'nl', 'de'];
        $currentLang = request('lang', 'ar');

        if (!in_array($currentLang, $langs)) {
            $currentLang = 'ar';
        }

        $seo = [
            'title'    => Setting::get("seo_title_{$currentLang}",    ''),
            'desc'     => Setting::get("seo_desc_{$currentLang}",     ''),
            'keywords' => Setting::get("seo_keywords_{$currentLang}", ''),
            'og_title' => Setting::get("seo_og_title_{$currentLang}", ''),
            'og_desc'  => Setting::get("seo_og_desc_{$currentLang}",  ''),
            'robots'   => Setting::get("seo_robots_{$currentLang}",   'index, follow'),
        ];

        return view('admin.settings.seo', compact('langs', 'currentLang', 'seo'));
    }

    public function saveSeo(Request $request)
    {
        $request->validate([
            'lang'     => 'required|in:ar,en,nl,de',
            'title'    => 'nullable|string|max:255',
            'desc'     => 'nullable|string|max:500',
            'keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_desc'  => 'nullable|string|max:500',
            'robots'   => 'nullable|string|max:100',
        ]);

        $lang = $request->input('lang');

        Setting::set("seo_title_{$lang}",    $request->input('title',    ''), 'seo');
        Setting::set("seo_desc_{$lang}",     $request->input('desc',     ''), 'seo');
        Setting::set("seo_keywords_{$lang}", $request->input('keywords', ''), 'seo');
        Setting::set("seo_og_title_{$lang}", $request->input('og_title', ''), 'seo');
        Setting::set("seo_og_desc_{$lang}",  $request->input('og_desc',  ''), 'seo');
        Setting::set("seo_robots_{$lang}",   $request->input('robots', 'index, follow'), 'seo');

        $this->generateSitemap();

        ActivityLog::record("SEO settings updated for lang={$lang}", 'settings');

        return back()->with('success', 'تم حفظ إعدادات SEO وتحديث خريطة الموقع.');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function generateBrandCss(string $color): void
    {
        $hex = ltrim($color, '#');
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));

        $darken = function (int $r, int $g, int $b, float $pct): string {
            return sprintf(
                '#%02x%02x%02x',
                max(0, (int) round($r * (1 - $pct))),
                max(0, (int) round($g * (1 - $pct))),
                max(0, (int) round($b * (1 - $pct)))
            );
        };

        $lighten = function (int $r, int $g, int $b, float $pct): string {
            return sprintf(
                '#%02x%02x%02x',
                min(255, (int) round($r + (255 - $r) * $pct)),
                min(255, (int) round($g + (255 - $g) * $pct)),
                min(255, (int) round($b + (255 - $b) * $pct))
            );
        };

        $brand600 = $darken($r, $g, $b, 0.08);
        $brand700 = $darken($r, $g, $b, 0.15);
        $brand50  = $lighten($r, $g, $b, 0.95);
        $brand100 = $lighten($r, $g, $b, 0.85);

        $css = ":root {\n"
            . "    --brand: {$color};\n"
            . "    --brand-600: {$brand600};\n"
            . "    --brand-700: {$brand700};\n"
            . "    --brand-50: {$brand50};\n"
            . "    --brand-100: {$brand100};\n"
            . "}\n";

        @mkdir(public_path('css'), 0755, true);
        file_put_contents(public_path('css/brand.css'), $css);
    }

    // -------------------------------------------------------------------------
    // SMTP / Email
    // -------------------------------------------------------------------------

    public function smtp()
    {
        $settings = Setting::getGroup('smtp');
        return view('admin.settings.smtp', compact('settings'));
    }

    public function saveSmtp(Request $request)
    {
        $request->validate([
            'smtp_host'       => 'nullable|string|max:255',
            'smtp_port'       => 'nullable|integer|min:1|max:65535',
            'smtp_username'   => 'nullable|string|max:255',
            'smtp_password'   => 'nullable|string|max:255',
            'smtp_encryption' => 'required|in:tls,ssl,none',
            'smtp_from_email' => 'nullable|email|max:255',
            'smtp_from_name'  => 'nullable|string|max:150',
        ]);

        Setting::set('smtp_host',       $request->input('smtp_host', ''),       'smtp');
        Setting::set('smtp_port',       $request->input('smtp_port', 587),      'smtp');
        Setting::set('smtp_username',   $request->input('smtp_username', ''),   'smtp');
        if ($request->filled('smtp_password')) {
            Setting::set('smtp_password', $request->input('smtp_password'), 'smtp');
        }
        Setting::set('smtp_encryption', $request->input('smtp_encryption', 'tls'), 'smtp');
        Setting::set('smtp_from_email', $request->input('smtp_from_email', ''), 'smtp');
        Setting::set('smtp_from_name',  $request->input('smtp_from_name', ''),  'smtp');

        ActivityLog::record('SMTP settings updated', 'settings');

        return back()->with('success', __('admin.smtp_saved'));
    }

    public function testSmtp(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        try {
            $smtpHost    = Setting::get('smtp_host', '');
            $smtpPort    = Setting::get('smtp_port', 587);
            $smtpUser    = Setting::get('smtp_username', '');
            $smtpPass    = Setting::get('smtp_password', '');
            $smtpEncrypt = Setting::get('smtp_encryption', 'tls');
            $fromEmail   = Setting::get('smtp_from_email', $smtpUser);
            $fromName    = Setting::get('smtp_from_name', Setting::get('site_name', config('app.name')));

            if (!$smtpHost || !$smtpUser) {
                return back()->with('error', __('admin.smtp_not_configured'));
            }

            config([
                'mail.mailers.smtp.host'       => $smtpHost,
                'mail.mailers.smtp.port'       => (int) $smtpPort,
                'mail.mailers.smtp.username'   => $smtpUser,
                'mail.mailers.smtp.password'   => $smtpPass,
                'mail.mailers.smtp.encryption' => $smtpEncrypt === 'none' ? null : $smtpEncrypt,
                'mail.from.address'            => $fromEmail,
                'mail.from.name'               => $fromName,
            ]);

            \Illuminate\Support\Facades\Mail::raw(
                'بريد اختبار من ' . Setting::get('site_name', config('app.name')) . ' — SMTP يعمل بشكل صحيح!',
                fn($m) => $m->to($request->input('test_email'))->subject('اختبار SMTP')
            );

            ActivityLog::record('SMTP test email sent to ' . $request->input('test_email'), 'settings');

            return back()->with('success', __('admin.smtp_test_sent'));
        } catch (\Throwable $e) {
            return back()->with('error', __('admin.smtp_test_failed') . ': ' . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Cache
    // -------------------------------------------------------------------------

    public function clearCache()
    {
        \App\Models\Setting::clearCache();
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        ActivityLog::record('Cache cleared', 'settings');

        return back()->with('success', 'تم مسح الذاكرة المؤقتة بنجاح.');
    }

    // -------------------------------------------------------------------------
    // Maintenance toggle (AJAX)
    // -------------------------------------------------------------------------

    public function toggleMaintenance(Request $request)
    {
        $on = $request->boolean('enabled');
        Setting::set('maintenance_mode', $on ? '1' : '0', 'general');

        ActivityLog::record('Maintenance mode ' . ($on ? 'enabled' : 'disabled'), 'settings');

        return response()->json(['ok' => true, 'maintenance' => $on]);
    }

    private function generateSitemap(): void
    {
        $siteUrl = rtrim(Setting::get('site_url', config('app.url')), '/');
        $langs   = ['ar', 'en', 'nl', 'de'];
        $date    = now()->toDateString();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($langs as $lang) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$siteUrl}/?lang={$lang}</loc>\n";
            $xml .= "    <lastmod>{$date}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>1.0</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $xml);
    }
}
