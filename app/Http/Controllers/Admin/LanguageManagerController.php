<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageManagerController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('sort_order')->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:10|unique:languages,code',
            'name'        => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'flag_code'   => 'nullable|string|max:10',
            'is_rtl'      => 'nullable|boolean',
            'translations' => 'nullable|file|mimes:json|max:1024',
        ]);

        $translations = null;
        if ($request->hasFile('translations') && $request->file('translations')->isValid()) {
            $json = file_get_contents($request->file('translations')->getRealPath());
            $translations = json_decode($json, true);
        }

        Language::create([
            'code'         => strtolower($request->input('code')),
            'name'         => $request->input('name'),
            'native_name'  => $request->input('native_name'),
            'flag_code'    => strtoupper($request->input('flag_code', '')),
            'is_rtl'       => $request->boolean('is_rtl'),
            'is_active'    => true,
            'is_default'   => false,
            'translations' => $translations,
            'sort_order'   => Language::count(),
        ]);

        Language::clearCache();
        ActivityLog::record("Language added: {$request->input('code')}", 'languages');

        return redirect()->route('admin.languages.index')
            ->with('success', __('admin.language_added'));
    }

    public function edit(Language $language)
    {
        return view('admin.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'flag_code'   => 'nullable|string|max:10',
            'is_rtl'      => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
            'translations' => 'nullable|file|mimes:json|max:1024',
        ]);

        $data = [
            'name'        => $request->input('name'),
            'native_name' => $request->input('native_name'),
            'flag_code'   => strtoupper($request->input('flag_code', '')),
            'is_rtl'      => $request->boolean('is_rtl'),
            'is_active'   => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('translations') && $request->file('translations')->isValid()) {
            $json = file_get_contents($request->file('translations')->getRealPath());
            $parsed = json_decode($json, true);
            if (is_array($parsed)) {
                $data['translations'] = $parsed;
            }
        }

        $language->update($data);
        Language::clearCache();

        if ($request->boolean('set_default')) {
            $language->setAsDefault();
        }

        ActivityLog::record("Language updated: {$language->code}", 'languages');

        return back()->with('success', __('admin.language_updated'));
    }

    public function destroy(Language $language)
    {
        if ($language->is_default) {
            return back()->with('error', __('admin.cannot_delete_default_lang'));
        }

        $language->delete();
        Language::clearCache();
        ActivityLog::record("Language deleted: {$language->code}", 'languages');

        return back()->with('success', __('admin.language_deleted'));
    }

    public function exportTranslations(Language $language)
    {
        $translations = $language->translations ?? [];
        $filename = "translations-{$language->code}.json";

        return response()->streamDownload(function () use ($translations) {
            echo json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }, $filename, ['Content-Type' => 'application/json']);
    }
}
