<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        // Determine locale
        $defaultLocale = Setting::get('default_locale', config('app.locale', 'ar'));
        $availableLocales = ['ar', 'en', 'nl', 'de'];

        // Priority: ?lang param > cookie > Accept-Language > default
        if ($request->has('lang') && in_array($request->get('lang'), $availableLocales)) {
            $lang = $request->get('lang');
        } elseif ($request->cookie('site_lang') && in_array($request->cookie('site_lang'), $availableLocales)) {
            $lang = $request->cookie('site_lang');
        } else {
            // Try Accept-Language
            $acceptLang = $request->getPreferredLanguage($availableLocales);
            $lang = $acceptLang ?: $defaultLocale;
        }

        // Load all content blocks for the current language
        $sections = ['hero', 'spotlight1', 'spotlight2', 'spotlight3', 'features', 'testimonials', 'pricing', 'faq', 'contact', 'footer', 'nav'];
        $content  = [];

        foreach ($sections as $section) {
            $content[$section] = ContentBlock::getSection($section, $lang);
        }

        // Settings
        $settings = [
            'site_name'   => Setting::get('site_name', config('app.name')),
            'brand_color' => Setting::get('brand_color', '#FF8528'),
            'dark_mode'   => Setting::get('dark_mode_default', 'light'),
            'font_family' => Setting::get('font_family', 'IBM Plex Sans Arabic'),
            'ga_id'       => Setting::get('ga_id', ''),
            'logo_url'      => Setting::get('logo_url', ''),
            'dark_logo_url' => Setting::get('dark_logo_url', ''),
        ];

        // SEO settings per language
        $seo = [
            'title'       => Setting::get("seo_title_{$lang}", config('app.name') . ' — أدر مشاريعك وفريقك من مكان واحد'),
            'description' => Setting::get("seo_desc_{$lang}", 'منصة ' . config('app.name') . ' لإدارة المشاريع والمهام والعملاء والفواتير من لوحة واحدة.'),
            'keywords'    => Setting::get("seo_keywords_{$lang}", ''),
            'og_title'    => Setting::get("seo_og_title_{$lang}", ''),
            'og_desc'     => Setting::get("seo_og_desc_{$lang}", ''),
            'og_image'    => Setting::get("seo_og_image_{$lang}", ''),
            'robots'      => Setting::get("seo_robots_{$lang}", 'index, follow'),
        ];

        $response = response()->view('frontend.landing', compact('content', 'settings', 'seo', 'lang'));

        // Set cookie if lang was changed via query param
        if ($request->has('lang')) {
            $response->cookie('site_lang', $lang, 60 * 24 * 30); // 30 days
        }

        return $response;
    }

    public function blog(Request $request)
    {
        $lang = $this->detectLang($request);
        $settings = $this->getSettings();
        $seo = $this->getSeo($lang);

        $posts = \App\Models\Post::with(['category', 'author'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->paginate(9);

        return response()->view('frontend.blog', compact('posts', 'settings', 'seo', 'lang'));
    }

    public function post(Request $request, string $slug)
    {
        $lang = $this->detectLang($request);
        $settings = $this->getSettings();

        $post = \App\Models\Post::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment views
        $post->increment('views_count');

        $seo = [
            'title'       => $post->seo_title ?: ($post->{"title_{$lang}"} ?? $post->title_ar),
            'description' => $post->seo_desc ?: ($post->{"excerpt_{$lang}"} ?? $post->excerpt_ar ?? ''),
            'og_image'    => $post->featured_image ?? '',
            'robots'      => 'index, follow',
        ];

        // Related posts
        $related = \App\Models\Post::with(['category'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('frontend.post', compact('post', 'settings', 'seo', 'lang', 'related'));
    }

    private function detectLang(Request $request): string
    {
        $defaultLocale = \App\Models\Setting::get('default_locale', config('app.locale', 'ar'));
        $available = ['ar', 'en', 'nl', 'de'];
        if ($request->has('lang') && in_array($request->get('lang'), $available)) {
            return $request->get('lang');
        }
        if ($request->cookie('site_lang') && in_array($request->cookie('site_lang'), $available)) {
            return $request->cookie('site_lang');
        }
        return $defaultLocale;
    }

    private function getSettings(): array
    {
        return [
            'site_name'     => \App\Models\Setting::get('site_name', config('app.name')),
            'brand_color'   => \App\Models\Setting::get('brand_color', '#FF8528'),
            'dark_mode'     => \App\Models\Setting::get('dark_mode_default', 'light'),
            'font_family'   => \App\Models\Setting::get('font_family', 'IBM Plex Sans Arabic'),
            'ga_id'         => \App\Models\Setting::get('ga_id', ''),
            'logo_url'      => \App\Models\Setting::get('logo_url', ''),
            'dark_logo_url' => \App\Models\Setting::get('dark_logo_url', ''),
        ];
    }

    private function getSeo(string $lang): array
    {
        return [
            'title'       => \App\Models\Setting::get("seo_title_{$lang}", config('app.name')),
            'description' => \App\Models\Setting::get("seo_desc_{$lang}", ''),
            'keywords'    => \App\Models\Setting::get("seo_keywords_{$lang}", ''),
            'og_title'    => \App\Models\Setting::get("seo_og_title_{$lang}", ''),
            'og_desc'     => \App\Models\Setting::get("seo_og_desc_{$lang}", ''),
            'og_image'    => \App\Models\Setting::get("seo_og_image_{$lang}", ''),
            'robots'      => \App\Models\Setting::get("seo_robots_{$lang}", 'index, follow'),
        ];
    }

    public function page(Request $request, string $slug)
    {
        $page = \App\Models\Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $lang     = $this->detectLang($request);
        $settings = $this->getSettings();

        return view('frontend.page', compact('page', 'lang', 'settings'));
    }

    public function sitemap()
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

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $siteUrl = rtrim(Setting::get('site_url', config('app.url')), '/');
        $default = "User-agent: *\nAllow: /\nSitemap: {$siteUrl}/sitemap.xml";
        $content = Setting::get('robots_txt', $default);

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
