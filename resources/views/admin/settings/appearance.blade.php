@extends('layouts.admin')

@section('title', 'Appearance')
@section('page-title', 'Appearance Settings')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Appearance</h2>
                <p class="text-sm text-gray-500 mt-0.5">Customize the look and feel of your website.</p>
            </div>

            <form method="POST" action="{{ route('admin.settings.appearance.save') }}"
                  enctype="multipart/form-data"
                  class="px-6 py-6 space-y-6"
                  x-data="{
                      brandColor: '{{ old('brand_color', $settings['brand_color'] ?? '#FF8528') }}',

                      syncFromPicker(val) {
                          this.brandColor = val;
                      },
                      syncFromText(val) {
                          if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                              this.brandColor = val;
                          }
                      }
                  }"
            >
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Brand Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand Color</label>
                    <div class="flex items-center gap-4 flex-wrap">
                        <!-- Color Picker -->
                        <div class="flex items-center gap-3">
                            <input
                                type="color"
                                :value="brandColor"
                                @input="syncFromPicker($event.target.value)"
                                class="w-12 h-12 rounded-xl border border-gray-200 cursor-pointer p-0.5"
                                style="padding: 2px;"
                            >
                            <!-- Hex Text Input -->
                            <input
                                type="text"
                                name="brand_color"
                                :value="brandColor"
                                @input="syncFromText($event.target.value)"
                                maxlength="7"
                                class="w-32 px-3 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:border-transparent transition-shadow uppercase"
                                placeholder="#FF8528"
                            >
                        </div>

                        <!-- Live Preview -->
                        <div class="flex items-center gap-3">
                            <div class="w-24 h-10 rounded-xl shadow-sm transition-all duration-150 flex items-center justify-center text-white text-xs font-semibold"
                                 :style="'background-color:' + brandColor">
                                Preview
                            </div>
                            <div class="w-8 h-8 rounded-full shadow-sm"
                                 :style="'background-color:' + brandColor">
                            </div>
                        </div>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400">This color is used for buttons, highlights, and brand accents.</p>
                </div>

                {{-- Dark Mode Default --}}
                <div>
                    <label for="dark_mode" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Dark Mode Default
                    </label>
                    <select
                        id="dark_mode"
                        name="dark_mode"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @foreach (['light' => 'Light', 'dark' => 'Dark', 'system' => 'System (follow device)'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('dark_mode', $settings['dark_mode'] ?? 'light') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Font Family --}}
                <div>
                    <label for="font_family" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Font Family
                    </label>
                    <select
                        id="font_family"
                        name="font_family"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @foreach ([
                            'IBM Plex Sans Arabic' => 'IBM Plex Sans Arabic',
                            'Cairo'                => 'Cairo',
                            'Tajawal'              => 'Tajawal',
                            'Inter'                => 'Inter',
                        ] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('font_family', $settings['font_family'] ?? 'IBM Plex Sans Arabic') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Primary font used across the frontend.</p>
                </div>

                {{-- Current Logo --}}
                @if (!empty($settings['logo_url']))
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Current Logo</p>
                        <div class="w-40 h-20 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center p-3">
                            <img src="{{ $settings['logo_url'] }}" alt="Current Logo" class="max-w-full max-h-full object-contain">
                        </div>
                    </div>
                @endif

                {{-- Logo Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Upload New Logo
                        <span class="text-gray-400 font-normal ml-1">(PNG, SVG, WebP)</span>
                    </label>
                    <input
                        type="file"
                        name="logo"
                        accept="image/png,image/svg+xml,image/webp"
                        class="w-full text-sm text-gray-500
                               file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                               file:text-sm file:font-semibold file:text-white file:cursor-pointer"
                    >
                    <style>
                        input[type=file]::file-selector-button {
                            background-color: #FF8528;
                            color: white;
                            border: none;
                            padding: 0.5rem 1rem;
                            border-radius: 0.5rem;
                            font-size: 0.875rem;
                            font-weight: 600;
                            cursor: pointer;
                        }
                        input[type=file]::file-selector-button:hover {
                            background-color: #E06800;
                        }
                    </style>
                </div>

                {{-- Logo URL --}}
                <div>
                    <label for="logo_url" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Logo URL
                        <span class="text-gray-400 font-normal ml-1">(or paste from Media Library)</span>
                    </label>
                    <input
                        id="logo_url"
                        type="text"
                        name="logo_url"
                        value="{{ old('logo_url', $settings['logo_url'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                        placeholder="https://example.com/storage/logo.png"
                    >
                    <p class="mt-1 text-xs text-gray-400">
                        You can copy a URL from the
                        <a href="{{ route('admin.media.index') }}" class="underline" style="color:#FF8528;">Media Library</a>.
                    </p>
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
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
