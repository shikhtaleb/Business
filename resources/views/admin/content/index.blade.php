@extends('layouts.admin')

@section('title', 'Content')
@section('page-title', 'Content Management')

@section('content')

    <div
        x-data="{
            activeSection: '{{ request('section', 'nav') }}',
            activeLang: '{{ request('lang', 'en') }}',
        }"
    >
        {{-- ── Section Tabs ──────────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5">
            <div class="px-5 pt-4 pb-0">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Section</p>
                <div class="flex flex-wrap gap-2 pb-0">
                    @foreach (['nav','hero','spotlight1','spotlight2','spotlight3','features','testimonials','pricing','faq','contact','footer'] as $sec)
                        <button
                            type="button"
                            @click="activeSection = '{{ $sec }}'"
                            :class="activeSection === '{{ $sec }}'
                                ? 'text-white shadow-sm'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                            :style="activeSection === '{{ $sec }}' ? 'background-color:#FF8528;' : ''"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all duration-150 capitalize"
                        >
                            {{ $sec }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Language Tabs --}}
            <div class="px-5 pt-4 pb-4 border-t border-gray-50 mt-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Language</p>
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
        </div>

        {{-- ── Content Form ──────────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">
                    Edit Content
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
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                               placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent transition-shadow resize-y"
                                        placeholder="Enter {{ $key }}..."
                                    >{{ old('blocks.'.$key, $value) }}</textarea>
                                @else
                                    <input
                                        type="text"
                                        name="blocks[{{ $key }}]"
                                        value="{{ old('blocks.'.$key, $value) }}"
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
                        <p class="text-xs text-gray-300 mt-1">Select a different section or language above.</p>
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
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
