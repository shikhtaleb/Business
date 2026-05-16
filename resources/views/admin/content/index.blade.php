@extends('layouts.admin')

@section('title', __('admin.content_management'))
@section('page-title', __('admin.content_management'))

@section('content')

<div
    x-data="{
        activeSection: '{{ request('section', 'nav') }}',
        activeLang: '{{ request('lang', 'ar') }}',
        showImport: false,
    }"
    class="flex gap-5 items-start"
>

    {{-- ── Left: Section Sidebar ──────────────────────────────────────────────── --}}
    <div class="w-48 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('admin.section') }}</p>
            </div>
            @foreach (['nav','hero','spotlight1','spotlight2','spotlight3','features','testimonials','pricing','faq','contact','footer'] as $sec)
                <button
                    type="button"
                    @click="activeSection = '{{ $sec }}'"
                    :class="activeSection === '{{ $sec }}'
                        ? 'text-white border-r-2'
                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 border-r-2 border-transparent'"
                    :style="activeSection === '{{ $sec }}' ? 'background-color:#FF8528; border-color:#E06800;' : ''"
                    class="w-full text-left px-4 py-2.5 text-sm font-medium transition-all duration-150 capitalize border-b border-gray-50 last:border-0 flex items-center gap-2"
                >
                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 opacity-60"
                          :style="activeSection === '{{ $sec }}' ? 'background:white' : 'background:#9ca3af'"></span>
                    {{ $sec }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- ── Right: Language + Export/Import + Content Form ─────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-4">

        {{-- Language Tabs --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 pt-4 pb-4 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('admin.language') }}</p>
                    <div class="flex gap-2">
                        @foreach (['ar' => 'العربية', 'en' => 'English', 'nl' => 'Nederlands', 'de' => 'Deutsch'] as $code => $label)
                            <button
                                type="button"
                                @click="activeLang = '{{ $code }}'"
                                :class="activeLang === '{{ $code }}'
                                    ? 'text-white shadow-sm'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                :style="activeLang === '{{ $code }}' ? 'background-color:#FF8528;' : ''"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-150"
                            >
                                {{ strtoupper($code) }}
                                <span class="hidden sm:inline text-xs opacity-75 ml-1">— {{ $label }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Export / Import Buttons --}}
                <div class="flex items-center gap-2">
                    {{-- Export --}}
                    <template x-for="lang in ['ar','en','nl','de']" :key="lang">
                        <a
                            x-show="activeLang === lang"
                            :href="`{{ route('admin.content.export', '__lang__') }}`.replace('__lang__', lang)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 text-xs font-semibold hover:bg-gray-50 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('admin.export_json') }}
                        </a>
                    </template>

                    {{-- Import Toggle --}}
                    <button
                        type="button"
                        @click="showImport = !showImport"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                        :class="showImport ? 'text-white' : 'border border-gray-200 text-gray-600 hover:bg-gray-50'"
                        :style="showImport ? 'background-color:#FF8528;' : ''"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        {{ __('admin.import_json') }}
                    </button>
                </div>
            </div>

            {{-- Import Form --}}
            <div x-show="showImport" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="px-5 pb-4 border-t border-gray-100 pt-4">
                <p class="text-xs font-semibold text-gray-500 mb-3">{{ __('admin.import_lang_label') }}</p>
                <form method="POST" action="{{ route('admin.content.import') }}" enctype="multipart/form-data"
                      class="flex flex-wrap items-end gap-3">
                    @csrf
                    <input type="hidden" name="lang" :value="activeLang">

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">JSON {{ __('admin.upload') }}</label>
                        <input type="file" name="file" accept=".json,application/json" required
                               class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:text-white file:cursor-pointer"
                               style="--file-bg: #FF8528;">
                        <style>input[type=file]::file-selector-button{background:#FF8528;color:#fff;border:none;padding:.375rem .75rem;border-radius:.5rem;font-size:.75rem;font-weight:600;cursor:pointer;}input[type=file]::file-selector-button:hover{background:#E06800;}</style>
                    </div>

                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-white text-xs font-semibold shadow-sm hover:shadow transition-all"
                            style="background-color:#FF8528;"
                            onmouseover="this.style.backgroundColor='#E06800'"
                            onmouseout="this.style.backgroundColor='#FF8528'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('admin.import_json') }}
                    </button>
                </form>
                <p class="mt-2 text-xs text-gray-400">
                    Upload a JSON file matching the structure exported from this page. Only valid section keys will be imported.
                </p>
            </div>
        </div>

        {{-- ── Content Form ──────────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">
                    {{ __('admin.edit_content') }}
                    <span class="ml-2 inline-flex items-center gap-1">
                        <span class="px-2 py-0.5 rounded-md text-xs font-bold text-white" style="background:#FF8528;" x-text="activeSection.toUpperCase()"></span>
                        <span class="px-2 py-0.5 rounded-md text-xs font-bold bg-gray-200 text-gray-700 uppercase" x-text="activeLang"></span>
                    </span>
                </h2>
            </div>

            <form method="POST" action="{{ route('admin.content.save') }}" class="px-5 py-5 space-y-5">
                @csrf
                <input type="hidden" name="section" :value="activeSection">
                <input type="hidden" name="lang" :value="activeLang">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (isset($blocks) && count($blocks))
                    <div class="grid grid-cols-1 gap-5">
                        @foreach ($blocks as $key => $value)
                            @php
                                $isTextarea = strlen($value ?? '') > 80
                                    || str_contains(strtolower($key), 'title')
                                    || str_contains(strtolower($key), '_p')
                                    || str_ends_with(strtolower($key), 'body')
                                    || str_contains(strtolower($key), 'description')
                                    || str_contains(strtolower($key), 'content');
                            @endphp
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    <span class="font-mono text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">{{ $key }}</span>
                                </label>
                                @if ($isTextarea)
                                    <textarea
                                        name="blocks[{{ $key }}]"
                                        rows="4"
                                        dir="auto"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent transition-shadow resize-y"
                                        placeholder="Enter {{ $key }}..."
                                    >{{ old('blocks.'.$key, $value) }}</textarea>
                                @else
                                    <input
                                        type="text"
                                        name="blocks[{{ $key }}]"
                                        value="{{ old('blocks.'.$key, $value) }}"
                                        dir="auto"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                                        placeholder="Enter {{ $key }}..."
                                    >
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center">
                        <svg class="mx-auto w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400">No content blocks found for this section/language.</p>
                        <p class="text-xs text-gray-300 mt-1">Select a different section or language, or import translations via JSON.</p>
                    </div>
                @endif

                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
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
                        {{ __('admin.save_content') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
