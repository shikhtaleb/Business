<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContentBlock;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    private array $sections = [
        'nav',
        'hero',
        'spotlight1',
        'spotlight2',
        'spotlight3',
        'features',
        'testimonials',
        'pricing',
        'faq',
        'contact',
        'footer',
    ];

    private array $langs = ['ar', 'en', 'nl', 'de'];

    public function index(Request $request)
    {
        $currentSection = $request->get('section', 'hero');
        $currentLang    = $request->get('lang', 'ar');

        // Validate section
        if (!in_array($currentSection, $this->sections)) {
            $currentSection = 'hero';
        }

        // Validate lang
        if (!in_array($currentLang, $this->langs)) {
            $currentLang = 'ar';
        }

        // Load all content blocks for the section + lang as key => value
        $blocks = ContentBlock::where('section', $currentSection)
            ->where('lang', $currentLang)
            ->pluck('value', 'key')
            ->toArray();

        $sections = $this->sections;
        $langs    = $this->langs;

        return view('admin.content.index', compact(
            'sections',
            'currentSection',
            'currentLang',
            'langs',
            'blocks'
        ));
    }

    public function save(Request $request)
    {
        $request->validate([
            'section' => 'required|string|in:' . implode(',', $this->sections),
            'lang'    => 'required|string|in:ar,en,nl,de',
            'blocks'  => 'required|array',
        ]);

        $section = $request->input('section');
        $lang    = $request->input('lang');
        $blocks  = $request->input('blocks');

        foreach ($blocks as $key => $value) {
            ContentBlock::upsert($section, (string) $key, $lang, (string) ($value ?? ''));
        }

        ActivityLog::record(
            "Content saved: section={$section}, lang={$lang}, keys=" . count($blocks),
            'content',
            ['section' => $section, 'lang' => $lang]
        );

        return back()->with('success', 'Content saved successfully.');
    }
}
