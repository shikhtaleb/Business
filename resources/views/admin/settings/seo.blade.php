@extends('layouts.admin')

@section('title', 'SEO Settings')
@section('page-title', 'SEO Settings')

@section('content')

    <div class="max-w-2xl">

        {{-- ── Language Tabs ─────────────────────────────────────────────────────── --}}
        <div class="flex gap-2 mb-5 flex-wrap">
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
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Meta Title
                    </label>
                    <input
                        id="meta_title"
                        type="text"
                        name="meta_title"
                        value="{{ old('meta_title', $seo['meta_title'] ?? $seo['title'] ?? '') }}"
                        maxlength="70"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                               @error('meta_title') border-red-400 bg-red-50 @enderror"
                        placeholder="Your page title — up to 60 characters"
                    >
                    <p class="mt-1 text-xs text-gray-400">Recommended: 50-60 characters</p>
                    @error('meta_title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Meta Description --}}
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Meta Description
                    </label>
                    <textarea
                        id="meta_description"
                        name="meta_description"
                        rows="3"
                        maxlength="160"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow resize-y
                               @error('meta_description') border-red-400 bg-red-50 @enderror"
                        placeholder="A brief description of your page — up to 160 characters"
                    >{{ old('meta_description', $seo['meta_description'] ?? $seo['desc'] ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Recommended: 120-160 characters</p>
                    @error('meta_description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Meta Keywords --}}
                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Meta Keywords
                    </label>
                    <input
                        id="meta_keywords"
                        type="text"
                        name="meta_keywords"
                        value="{{ old('meta_keywords', $seo['meta_keywords'] ?? $seo['keywords'] ?? '') }}"
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
                            <label for="og_description" class="block text-sm font-medium text-gray-700 mb-1.5">OG Description</label>
                            <textarea
                                id="og_description"
                                name="og_description"
                                rows="3"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                       focus:outline-none focus:ring-2 focus:border-transparent transition-shadow resize-y"
                                placeholder="Open Graph description (shown when shared on social media)"
                            >{{ old('og_description', $seo['og_description'] ?? $seo['og_desc'] ?? '') }}</textarea>
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

@endsection
