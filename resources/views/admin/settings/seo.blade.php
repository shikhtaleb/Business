@extends('layouts.admin')

@section('title', 'SEO Settings')
@section('page-title', 'SEO Settings')

@section('content')

@php
$settingsTabs = [
    'admin.settings.general'    => ['label' => 'General',    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    'admin.settings.appearance' => ['label' => 'Appearance', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
    'admin.settings.seo'        => ['label' => 'SEO',        'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
];
@endphp

<div class="flex gap-6 items-start">

    {{-- ── Vertical Settings Tabs ─────────────────────────────────────────── --}}
    <div class="w-52 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @foreach($settingsTabs as $routeName => $tab)
                @php $active = request()->routeIs($routeName.'*'); @endphp
                <a href="{{ route($routeName) }}"
                   class="flex items-center gap-3 px-5 py-4 text-sm font-medium transition-all duration-150 border-b border-gray-50 last:border-0
                          {{ $active ? 'text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}"
                   @if($active) style="background-color:#FF8528;" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                    </svg>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ── Page Content ────────────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-5">

        {{-- Language Tabs --}}
        <div class="flex gap-2 flex-wrap">
            @foreach (['ar' => 'AR — Arabic', 'en' => 'EN — English', 'nl' => 'NL — Nederlands', 'de' => 'DE — Deutsch'] as $code => $label)
                <a href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}"
                   class="px-4 py-2 rounded-full text-xs font-semibold transition-all duration-150
                          {{ ($currentLang ?? 'en') === $code ? 'text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-gray-300' }}"
                   @if(($currentLang ?? 'en') === $code) style="background-color:#FF8528;" @endif
                >
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">
                    SEO Settings
                    <span class="ml-2 px-2.5 py-0.5 rounded-lg text-xs font-bold text-white uppercase"
                          style="background:#FF8528;">
                        {{ $currentLang ?? 'en' }}
                    </span>
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Search engine and social media metadata for the <strong>{{ strtoupper($currentLang ?? 'en') }}</strong> version of your site.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.settings.seo.save') }}" class="px-6 py-6 space-y-5">
                @csrf
                <input type="hidden" name="lang" value="{{ $currentLang ?? 'en' }}">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Meta Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Meta Title
                    </label>
                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title', $seo['title'] ?? '') }}"
                        maxlength="70"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                        placeholder="Your page title — up to 60 characters"
                    >
                    <p class="mt-1 text-xs text-gray-400">Recommended: 50-60 characters</p>
                </div>

                {{-- Meta Description --}}
                <div>
                    <label for="desc" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Meta Description
                    </label>
                    <textarea
                        id="desc"
                        name="desc"
                        rows="3"
                        maxlength="160"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow resize-y"
                        placeholder="A brief description of your page — up to 160 characters"
                    >{{ old('desc', $seo['desc'] ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Recommended: 120-160 characters</p>
                </div>

                {{-- Meta Keywords --}}
                <div>
                    <label for="keywords" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Meta Keywords
                    </label>
                    <input
                        id="keywords"
                        type="text"
                        name="keywords"
                        value="{{ old('keywords', $seo['keywords'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                        placeholder="keyword1, keyword2, keyword3"
                    >
                    <p class="mt-1 text-xs text-gray-400">Comma-separated. Most search engines ignore this tag.</p>
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Open Graph (Social Media)</p>

                    <div class="space-y-5">
                        {{-- OG Title --}}
                        <div>
                            <label for="og_title" class="block text-sm font-medium text-gray-700 mb-1.5">OG Title</label>
                            <input
                                id="og_title"
                                type="text"
                                name="og_title"
                                value="{{ old('og_title', $seo['og_title'] ?? '') }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                       focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                                placeholder="Open Graph title (defaults to meta title)"
                            >
                        </div>

                        {{-- OG Description --}}
                        <div>
                            <label for="og_desc" class="block text-sm font-medium text-gray-700 mb-1.5">OG Description</label>
                            <textarea
                                id="og_desc"
                                name="og_desc"
                                rows="3"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                       focus:outline-none focus:ring-2 focus:border-transparent transition-shadow resize-y"
                                placeholder="Open Graph description (shown when shared on social media)"
                            >{{ old('og_desc', $seo['og_desc'] ?? '') }}</textarea>
                        </div>

                        {{-- OG Image --}}
                        <div>
                            <label for="og_image" class="block text-sm font-medium text-gray-700 mb-1.5">
                                OG Image URL
                                <span class="text-gray-400 font-normal ml-1">(1200x630 px recommended)</span>
                            </label>
                            <div class="flex gap-2">
                                <input
                                    id="og_image"
                                    type="text"
                                    name="og_image"
                                    value="{{ old('og_image', $seo['og_image'] ?? '') }}"
                                    class="flex-1 px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                           focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                                    placeholder="https://example.com/storage/og-image.jpg"
                                >
                                <a href="{{ route('admin.media.index') }}"
                                   target="_blank"
                                   title="Browse Media Library"
                                   class="flex items-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-600 hover:bg-gray-50 flex-shrink-0 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                    </svg>
                                    Media
                                </a>
                            </div>
                            @if (!empty($seo['og_image']))
                                <div class="mt-2 w-32 h-16 rounded-lg overflow-hidden border border-gray-100">
                                    <img src="{{ $seo['og_image'] }}" alt="OG Preview" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Robots Meta --}}
                <div class="border-t border-gray-100 pt-5">
                    <label for="robots" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Robots Meta Tag
                    </label>
                    <select
                        id="robots"
                        name="robots"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @foreach ([
                            'index,follow'     => 'index, follow — Allow indexing and following links (default)',
                            'noindex,follow'   => 'noindex, follow — Don\'t index, but follow links',
                            'index,nofollow'   => 'index, nofollow — Index page, but don\'t follow links',
                            'noindex,nofollow' => 'noindex, nofollow — Block all crawlers',
                        ] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('robots', $seo['robots'] ?? 'index,follow') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Save Button --}}
                <div class="flex items-center justify-end pt-2 border-t border-gray-100">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold
                               shadow-md hover:shadow-lg focus:outline-none transition-all duration-150 active:scale-[0.98]"
                        style="background-color:#FF8528;"
                        onmouseover="this.style.backgroundColor='#E06800'"
                        onmouseout="this.style.backgroundColor='#FF8528'"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save SEO Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
