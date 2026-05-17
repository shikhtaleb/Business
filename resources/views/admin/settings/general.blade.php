@extends('layouts.admin')

@section('title', 'الإعدادات العامة')
@section('page-title', 'الإعدادات العامة')

@section('content')

@php
$settingsTabs = [
    'admin.settings.general'    => ['label' => 'عام',        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    'admin.settings.appearance' => ['label' => 'المظهر',    'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
    'admin.settings.seo'        => ['label' => 'SEO',       'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
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

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">إعدادات الموقع</h2>
                <p class="text-sm text-gray-500 mt-0.5">الإعدادات الأساسية لموقعك.</p>
            </div>

            <form method="POST" action="{{ route('admin.settings.general.save') }}" class="px-6 py-6 space-y-6">
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

                {{-- Site Name --}}
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        اسم الموقع
                    </label>
                    <input
                        id="site_name"
                        type="text"
                        name="site_name"
                        value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                               @error('site_name') border-red-400 bg-red-50 @enderror"
                        placeholder="Retont Business"
                    >
                    @error('site_name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Site URL --}}
                <div>
                    <label for="site_url" class="block text-sm font-medium text-gray-700 mb-1.5">
                        رابط الموقع
                    </label>
                    <input
                        id="site_url"
                        type="url"
                        name="site_url"
                        value="{{ old('site_url', $settings['site_url'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                               @error('site_url') border-red-400 bg-red-50 @enderror"
                        placeholder="https://example.com"
                    >
                    @error('site_url')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Language --}}
                <div>
                    <label for="default_locale" class="block text-sm font-medium text-gray-700 mb-1.5">
                        اللغة الافتراضية
                    </label>
                    <select
                        id="default_locale"
                        name="default_locale"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @foreach (['ar' => 'Arabic (العربية)', 'en' => 'English', 'nl' => 'Dutch (Nederlands)', 'de' => 'German (Deutsch)'] as $code => $label)
                            <option value="{{ $code }}"
                                {{ old('default_locale', $settings['default_locale'] ?? 'en') === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Timezone --}}
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1.5">
                        المنطقة الزمنية
                    </label>
                    <select
                        id="timezone"
                        name="timezone"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @php
                            $timezones = [
                                'UTC'                    => 'UTC',
                                'Europe/London'          => 'Europe/London (GMT)',
                                'Europe/Amsterdam'       => 'Europe/Amsterdam (CET)',
                                'Europe/Berlin'          => 'Europe/Berlin (CET)',
                                'Europe/Paris'           => 'Europe/Paris (CET)',
                                'Europe/Rome'            => 'Europe/Rome (CET)',
                                'Europe/Madrid'          => 'Europe/Madrid (CET)',
                                'Europe/Istanbul'        => 'Europe/Istanbul (TRT)',
                                'Asia/Riyadh'            => 'Asia/Riyadh (AST)',
                                'Asia/Dubai'             => 'Asia/Dubai (GST)',
                                'Asia/Kuwait'            => 'Asia/Kuwait (AST)',
                                'Asia/Baghdad'           => 'Asia/Baghdad (AST)',
                                'Asia/Beirut'            => 'Asia/Beirut (EET)',
                                'Africa/Cairo'           => 'Africa/Cairo (EET)',
                                'America/New_York'       => 'America/New_York (EST)',
                                'America/Chicago'        => 'America/Chicago (CST)',
                                'America/Denver'         => 'America/Denver (MST)',
                                'America/Los_Angeles'    => 'America/Los_Angeles (PST)',
                                'America/Toronto'        => 'America/Toronto (EST)',
                                'America/Sao_Paulo'      => 'America/Sao_Paulo (BRT)',
                                'Asia/Tokyo'             => 'Asia/Tokyo (JST)',
                                'Asia/Singapore'         => 'Asia/Singapore (SGT)',
                                'Asia/Shanghai'          => 'Asia/Shanghai (CST)',
                                'Asia/Kolkata'           => 'Asia/Kolkata (IST)',
                                'Australia/Sydney'       => 'Australia/Sydney (AEDT)',
                                'Pacific/Auckland'       => 'Pacific/Auckland (NZDT)',
                            ];
                            $currentTz = old('timezone', $settings['timezone'] ?? 'UTC');
                        @endphp
                        @foreach ($timezones as $tz => $label)
                            <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Google Analytics ID --}}
                <div>
                    <label for="ga_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                        معرف Google Analytics
                        <span class="text-gray-400 font-normal ms-1">(اختياري)</span>
                    </label>
                    <input
                        id="ga_id"
                        type="text"
                        name="ga_id"
                        value="{{ old('ga_id', $settings['ga_id'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow font-mono"
                        placeholder="G-XXXXXXXXXX"
                    >
                    <p class="mt-1 text-xs text-gray-400">أدخل معرف القياس الخاص بـ Google Analytics 4.</p>
                </div>

                {{-- Maintenance Mode --}}
                <div class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-4"
                     x-data="{
                         maintenance: {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'true' : 'false' }},
                         saving: false,
                         async toggle() {
                             this.saving = true;
                             try {
                                 const resp = await fetch('{{ route('admin.settings.maintenance') }}', {
                                     method: 'POST',
                                     headers: {
                                         'Content-Type': 'application/json',
                                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                     },
                                     body: JSON.stringify({ enabled: this.maintenance }),
                                 });
                                 if (!resp.ok) throw new Error();
                             } catch(e) {
                                 this.maintenance = !this.maintenance;
                             } finally {
                                 this.saving = false;
                             }
                         }
                     }">
                    <div>
                        <p class="text-sm font-medium text-gray-800">وضع الصيانة</p>
                        <p class="text-xs text-gray-500 mt-0.5">عند التفعيل، يرى الزوار صفحة صيانة بدلاً من الموقع.</p>
                    </div>
                    <button type="button"
                            @click="maintenance = !maintenance; toggle()"
                            :disabled="saving"
                            class="relative inline-flex items-center flex-shrink-0 ml-4 cursor-pointer focus:outline-none disabled:opacity-60">
                        <div class="w-11 h-6 rounded-full transition-colors duration-200"
                             :style="maintenance ? 'background-color:#FF8528;' : 'background-color:#d1d5db;'">
                            <div class="absolute top-[2px] left-[2px] w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"
                                 :class="maintenance ? 'translate-x-5' : 'translate-x-0'"></div>
                        </div>
                    </button>
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
                        حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>

        {{-- Cache Management --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">إدارة الذاكرة المؤقتة</h2>
                <p class="text-sm text-gray-500 mt-0.5">مسح الإعدادات المخزنة مؤقتاً وبيانات التطبيق.</p>
            </div>
            <div class="px-6 py-5 flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="text-sm text-gray-700">ذاكرة التطبيق المؤقتة</p>
                    <p class="text-xs text-gray-400 mt-0.5">يمسح ذاكرة الإعدادات والقوالب المُجمّعة وذاكرة التطبيق.</p>
                </div>
                <form method="POST" action="{{ route('admin.settings.cache.clear') }}">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                        onclick="return confirm('مسح كل الذاكرة المؤقتة؟')">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        مسح الذاكرة المؤقتة
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
