<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin user (if none exists)
        if (User::count() === 0) {
            User::create([
                'name'     => 'مدير النظام',
                'email'    => 'admin@retont.com',
                'password' => Hash::make('password'),
            ]);
        }

        // Default settings
        $settings = [
            ['key' => 'site_name',         'value' => 'Retont Business',  'group' => 'general'],
            ['key' => 'site_url',           'value' => config('app.url'), 'group' => 'general'],
            ['key' => 'default_locale',     'value' => 'ar',              'group' => 'general'],
            ['key' => 'timezone',           'value' => 'Asia/Riyadh',     'group' => 'general'],
            ['key' => 'ga_id',              'value' => '',                'group' => 'general'],
            ['key' => 'maintenance_mode',   'value' => '0',               'group' => 'general'],
            ['key' => 'brand_color',        'value' => '#FF8528',         'group' => 'appearance'],
            ['key' => 'dark_mode_default',  'value' => '0',               'group' => 'appearance'],
            ['key' => 'font_family',        'value' => 'sans',            'group' => 'appearance'],
            ['key' => 'logo_url',           'value' => '',                'group' => 'appearance'],
        ];

        Setting::upsert($settings, ['key'], ['value', 'group']);

        // Content blocks for all languages
        $this->call([
            DefaultContentSeeder::class,
        ]);
    }
}
