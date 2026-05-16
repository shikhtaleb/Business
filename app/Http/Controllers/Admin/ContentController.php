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

    public function export(string $lang)
    {
        if (!in_array($lang, $this->langs)) {
            abort(404);
        }

        $data = [];
        foreach ($this->sections as $section) {
            $blocks = ContentBlock::where('section', $section)
                ->where('lang', $lang)
                ->pluck('value', 'key')
                ->toArray();
            if (!empty($blocks)) {
                $data[$section] = $blocks;
            }
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        ActivityLog::record("Translations exported: lang={$lang}", 'content', ['lang' => $lang]);

        return response($json, 200, [
            'Content-Type'        => 'application/json; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"translations_{$lang}.json\"",
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'lang' => 'required|in:ar,en,nl,de',
            'file' => 'required|file|max:1024',
        ]);

        $lang = $request->input('lang');
        $raw  = file_get_contents($request->file('file')->getRealPath());
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            return back()->with('error', 'Invalid JSON file format. Expected an object with section keys.');
        }

        $count = 0;
        foreach ($data as $section => $blocks) {
            if (!in_array($section, $this->sections) || !is_array($blocks)) {
                continue;
            }
            foreach ($blocks as $key => $value) {
                ContentBlock::upsert($section, (string) $key, $lang, (string) ($value ?? ''));
                $count++;
            }
        }

        ActivityLog::record(
            "Translations imported: lang={$lang}, keys={$count}",
            'content',
            ['lang' => $lang]
        );

        return back()->with('success', "Imported {$count} translation keys for language: {$lang}.");
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
