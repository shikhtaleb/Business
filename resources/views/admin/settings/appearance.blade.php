@extends('layouts.admin')

@section('title', __('admin.appearance_settings'))
@section('page-title', __('admin.appearance_settings'))

@section('content')

@php
$settingsTabs = [
    'admin.settings.general'    => ['label' => __('admin.general'),    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    'admin.settings.appearance' => ['label' => __('admin.appearance'), 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
    'admin.settings.seo'        => ['label' => __('admin.seo'),        'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
    'admin.settings.smtp'       => ['label' => __('admin.smtp'),       'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
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
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">{{ __('admin.appearance') }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('admin.appearance_lead') }}</p>
            </div>

            <form method="POST" action="{{ route('admin.settings.appearance.save') }}"
                  enctype="multipart/form-data"
                  class="px-6 py-6 space-y-6"
                  x-data="{
                      brandColor: '{{ old('brand_color', $settings['brand_color'] ?? '#FF8528') }}',
                      syncFromPicker(val) { this.brandColor = val; },
                      syncFromText(val) { if (/^#[0-9A-Fa-f]{6}$/.test(val)) this.brandColor = val; },
                      mediaPicker: { open: false, loading: false, images: [], callback: null },
                      async openMediaPicker(callback) {
                          this.mediaPicker.callback = callback;
                          this.mediaPicker.open = true;
                          if (this.mediaPicker.images.length === 0) {
                              this.mediaPicker.loading = true;
                              try {
                                  const res = await fetch('{{ route('admin.media.index') }}?json=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                                  this.mediaPicker.images = await res.json();
                              } finally { this.mediaPicker.loading = false; }
                          }
                      },
                      pickImage(url) {
                          if (this.mediaPicker.callback) this.mediaPicker.callback(url);
                          this.mediaPicker.open = false;
                      }
                  }"
                  @keydown.escape.window="mediaPicker.open = false"
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.brand_color') }}</label>
                    <div class="flex items-center gap-4 flex-wrap">
                        <div class="flex items-center gap-3">
                            <input
                                type="color"
                                :value="brandColor"
                                @input="syncFromPicker($event.target.value)"
                                class="w-12 h-12 rounded-xl border border-gray-200 cursor-pointer"
                                style="padding: 2px;"
                            >
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
                        <div class="flex items-center gap-3">
                            <div class="w-24 h-10 rounded-xl shadow-sm transition-all duration-150 flex items-center justify-center text-white text-xs font-semibold"
                                 :style="'background-color:' + brandColor">
                                {{ __('admin.preview') }}
                            </div>
                            <div class="w-8 h-8 rounded-full shadow-sm"
                                 :style="'background-color:' + brandColor">
                            </div>
                        </div>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400">{{ __('admin.brand_color_hint') }}</p>
                </div>

                {{-- Dark Mode Default --}}
                <div>
                    <label for="dark_mode" class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('admin.dark_mode_default') }}
                    </label>
                    <select
                        id="dark_mode"
                        name="dark_mode_default"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @foreach ([
                            'light'  => __('admin.light_mode'),
                            'dark'   => __('admin.dark_mode'),
                            'system' => __('admin.system_mode'),
                        ] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('dark_mode_default', $settings['dark_mode_default'] ?? 'light') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Font Family --}}
                <div>
                    <label for="font_family" class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('admin.font_family') }}
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
                    <p class="mt-1 text-xs text-gray-400">{{ __('admin.font_hint') }}</p>
                </div>

                {{-- Current Logo --}}
                @if (!empty($settings['logo_url']))
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">{{ __('admin.current_logo') }}</p>
                        <div class="w-40 h-20 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center p-3">
                            <img src="{{ $settings['logo_url'] }}" alt="Current Logo" class="max-w-full max-h-full object-contain">
                        </div>
                    </div>
                @endif

                {{-- Logo Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('admin.upload_logo') }}
                        <span class="text-gray-400 font-normal ms-1">(PNG, SVG, WebP)</span>
                    </label>
                    <input
                        type="file"
                        name="logo"
                        accept="image/png,image/svg+xml,image/webp"
                        class="w-full text-sm text-gray-500 file:me-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:text-white file:cursor-pointer"
                    >
                    <style>
                        input[type=file]::file-selector-button { background-color:#FF8528; color:white; border:none; padding:0.5rem 1rem; border-radius:0.5rem; font-size:0.875rem; font-weight:600; cursor:pointer; }
                        input[type=file]::file-selector-button:hover { background-color:#E06800; }
                    </style>
                </div>

                {{-- Logo URL --}}
                <div x-data="{ logoUrl: '{{ old('logo_url', $settings['logo_url'] ?? '') }}' }">
                    <label for="logo_url" class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('admin.logo_url') }}
                        <span class="text-gray-400 font-normal ms-1">({{ __('admin.or_paste_media') }})</span>
                    </label>
                    <div class="flex gap-2">
                        <input
                            id="logo_url"
                            type="text"
                            name="logo_url"
                            x-model="logoUrl"
                            class="flex-1 px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                            placeholder="https://example.com/storage/logo.png"
                        >
                        <button type="button"
                                @click="openMediaPicker(url => { logoUrl = url; })"
                                class="px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 whitespace-nowrap flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            استعراض
                        </button>
                    </div>
                    <div x-show="logoUrl" class="mt-2 w-36 h-16 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center p-2">
                        <img :src="logoUrl" alt="Logo Preview" class="max-w-full max-h-full object-contain">
                    </div>
                </div>

                {{-- Dark Mode Logo URL --}}
                <div x-data="{ darkLogoUrl: '{{ old('dark_logo_url', $settings['dark_logo_url'] ?? '') }}' }">
                    <label for="dark_logo_url" class="block text-sm font-medium text-gray-700 mb-1.5">
                        {{ __('admin.dark_logo_url') }}
                        <span class="text-gray-400 font-normal ms-1 text-xs">({{ __('admin.or_paste_media') }})</span>
                    </label>
                    <div class="flex gap-2">
                        <input
                            id="dark_logo_url"
                            type="text"
                            name="dark_logo_url"
                            x-model="darkLogoUrl"
                            class="flex-1 px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                            placeholder="https://example.com/storage/logo-dark.png"
                        >
                        <button type="button"
                                @click="openMediaPicker(url => { darkLogoUrl = url; })"
                                class="px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 whitespace-nowrap flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            استعراض
                        </button>
                    </div>
                    <div x-show="darkLogoUrl" class="mt-2 w-36 h-16 rounded-xl border border-gray-200 bg-gray-800 flex items-center justify-center p-2">
                        <img :src="darkLogoUrl" alt="Dark Logo Preview" class="max-w-full max-h-full object-contain">
                    </div>
                    <p class="mt-1 text-xs text-gray-400">يُعرض عند تفعيل الزوار الوضع الداكن في الواجهة الأمامية.</p>
                </div>

                {{-- Media Picker Modal --}}
                <div x-show="mediaPicker.open" x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center p-4"
                     style="background:rgba(0,0,0,.55)">
                    <div @click.outside="mediaPicker.open = false"
                         class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">اختر صورة</h3>
                            <button type="button" @click="mediaPicker.open = false"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="flex-1 overflow-y-auto p-4">
                            <div x-show="mediaPicker.loading" class="flex items-center justify-center py-12 text-gray-400 text-sm">
                                <svg class="w-5 h-5 animate-spin me-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                جاري التحميل...
                            </div>
                            <div x-show="!mediaPicker.loading && mediaPicker.images.length === 0" class="py-12 text-center text-gray-400 text-sm">
                                لا توجد صور في المكتبة
                            </div>
                            <div x-show="!mediaPicker.loading && mediaPicker.images.length > 0"
                                 class="grid grid-cols-4 gap-3">
                                <template x-for="img in mediaPicker.images" :key="img.id">
                                    <button type="button" @click="pickImage(img.url)"
                                            class="group relative aspect-square rounded-xl overflow-hidden border-2 border-transparent hover:border-orange-400 transition-all">
                                        <img :src="img.url" :alt="img.original_name" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
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
                        {{ __('admin.save_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
