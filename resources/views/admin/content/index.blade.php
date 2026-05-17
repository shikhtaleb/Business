@extends('layouts.admin')

@section('title', 'منشئ الصفحات')
@section('page-title', 'منشئ الصفحات')

@section('content')

@php
$sectionMeta = [
    'nav'          => ['icon' => 'M4 6h16M4 12h16M4 18h7',        'label' => 'شريط التنقل',      'desc' => 'القائمة الرئيسية وروابط التنقل'],
    'hero'         => ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'القسم الرئيسي',    'desc' => 'العنوان الرئيسي وزر الاتصال بالعمل'],
    'spotlight1'   => ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'تسليط الضوء ١',   'desc' => 'قسم الميزة الأولى المميزة'],
    'spotlight2'   => ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'تسليط الضوء ٢',   'desc' => 'قسم الميزة الثانية المميزة'],
    'spotlight3'   => ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'تسليط الضوء ٣',   'desc' => 'قسم الميزة الثالثة المميزة'],
    'features'     => ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'label' => 'المميزات',         'desc' => 'قائمة مميزات المنتج'],
    'testimonials' => ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'آراء العملاء',      'desc' => 'شهادات وتقييمات العملاء'],
    'pricing'      => ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'الأسعار',          'desc' => 'خطط الأسعار والاشتراكات'],
    'faq'          => ['icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'الأسئلة الشائعة',  'desc' => 'الأسئلة المتكررة وإجاباتها'],
    'contact'      => ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'تواصل معنا',       'desc' => 'نموذج الاتصال والمعلومات'],
    'footer'       => ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'التذييل',          'desc' => 'روابط ونصوص أسفل الصفحة'],
];

// Build a human-readable Arabic label from a content key
function keyLabel(string $key): string {
    return match(true) {
        $key === 'nav.home'              => 'رابط الرئيسية',
        $key === 'nav.features'          => 'رابط المميزات',
        $key === 'nav.pricing'           => 'رابط الأسعار',
        $key === 'nav.faq'               => 'رابط الأسئلة',
        $key === 'nav.contact'           => 'رابط التواصل',
        $key === 'nav.login'             => 'رابط تسجيل الدخول',
        $key === 'nav.cta'               => 'نص زر الدعوة',
        str_contains($key, 'title')      => 'العنوان',
        str_contains($key, 'subtitle')   => 'العنوان الفرعي',
        str_contains($key, 'sub')        => 'النص الفرعي',
        str_contains($key, 'lead')       => 'النص التمهيدي',
        str_contains($key, 'cta1')       => 'نص الزر الأول',
        str_contains($key, 'cta2')       => 'نص الزر الثاني',
        str_contains($key, 'cta')        => 'نص زر الدعوة',
        str_contains($key, 'btn')        => 'نص الزر',
        str_contains($key, 'desc')       => 'الوصف',
        str_contains($key, 'body')       => 'نص المحتوى',
        str_contains($key, 'content')    => 'المحتوى',
        str_contains($key, 'name')       => 'الاسم',
        str_contains($key, 'email')      => 'البريد الإلكتروني',
        str_contains($key, 'phone')      => 'الهاتف',
        str_contains($key, 'address')    => 'العنوان',
        str_contains($key, 'copy')       => 'حقوق النشر',
        str_contains($key, 'rights')     => 'حقوق النشر',
        str_contains($key, 'label')      => 'التسمية',
        str_contains($key, 'text')       => 'النص',
        str_contains($key, 'link')       => 'الرابط',
        str_contains($key, 'url')        => 'الرابط',
        str_contains($key, 'badge')      => 'الشارة',
        str_contains($key, 'tag')        => 'الوسم',
        str_contains($key, 'heading')    => 'العنوان الرئيسي',
        str_contains($key, 'caption')    => 'التعليق',
        str_contains($key, 'quote')      => 'الاقتباس',
        str_contains($key, 'author')     => 'المؤلف',
        str_contains($key, 'role')       => 'الدور الوظيفي',
        str_contains($key, 'price')      => 'السعر',
        str_contains($key, 'plan')       => 'الخطة',
        str_contains($key, 'feature')    => 'الميزة',
        str_contains($key, 'question')   => 'السؤال',
        str_contains($key, 'answer')     => 'الجواب',
        str_contains($key, 'note')       => 'ملاحظة',
        default => ucwords(str_replace(['.', '_', '-'], ' ', $key)),
    };
}

$activeMeta = $sectionMeta[$currentSection] ?? ['icon' => 'M4 6h16M4 12h16M4 18h7', 'label' => $currentSection, 'desc' => ''];
@endphp


<div x-data="{ showImport: false }" class="space-y-5">

    {{-- ── Top bar: Language selector + View site ──────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-3 flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm font-semibold text-gray-500 ms-1">اللغة:</span>
            @foreach(['ar' => ['🇸🇦', 'العربية'], 'en' => ['🇬🇧', 'English'], 'nl' => ['🇳🇱', 'Nederlands'], 'de' => ['🇩🇪', 'Deutsch']] as $code => [$flag, $langLabel])
            <a href="{{ request()->fullUrlWithQuery(['lang' => $code, 'section' => $currentSection]) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all
                      {{ $currentLang === $code ? 'text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
               @if($currentLang === $code) style="background:#FF8528;" @endif>
                <span>{{ $flag }}</span>
                <span>{{ $langLabel }}</span>
            </a>
            @endforeach
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.content.export', $currentLang) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs text-gray-600 hover:bg-gray-50 transition-colors font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                تصدير JSON
            </a>
            <button type="button" @click="showImport = !showImport"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all border"
                    :class="showImport ? 'text-white border-transparent' : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
                    :style="showImport ? 'background:#FF8528;' : ''">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                استيراد JSON
            </button>
            <a href="{{ url('/') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs text-gray-600 hover:bg-gray-50 transition-colors font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                معاينة الموقع
            </a>
        </div>
    </div>

    {{-- ── Import Panel ─────────────────────────────────────────────────────── --}}
    <div x-show="showImport" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-amber-50 border border-amber-200 rounded-2xl px-6 py-4">
        <p class="text-sm font-semibold text-amber-800 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            استيراد ترجمات JSON — اللغة الحالية:
            <span class="px-2 py-0.5 rounded-lg text-xs font-bold text-white" style="background:#FF8528;">{{ strtoupper($currentLang) }}</span>
        </p>
        <form method="POST" action="{{ route('admin.content.import') }}" enctype="multipart/form-data"
              class="flex flex-wrap items-end gap-3">
            @csrf
            <input type="hidden" name="lang" value="{{ $currentLang }}">
            <div>
                <label class="block text-xs font-medium text-amber-700 mb-1">اختر ملف JSON</label>
                <input type="file" name="file" accept=".json,application/json" required
                       class="text-sm text-gray-600 file:me-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:text-white file:cursor-pointer file:transition-colors">
                <style>input[type=file]::file-selector-button{background:#FF8528;color:#fff;border:none;padding:.375rem .75rem;border-radius:.5rem;font-size:.75rem;font-weight:600;cursor:pointer;}input[type=file]::file-selector-button:hover{background:#E06800;}</style>
            </div>
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-xs font-semibold shadow-sm transition-all hover:opacity-90"
                    style="background:#FF8528;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                استيراد
            </button>
        </form>
        <p class="mt-2 text-xs text-amber-600">ارفع ملف JSON بنفس بنية ملفات التصدير. سيتم استيراد المفاتيح الصحيحة فقط.</p>
    </div>

    <div class="flex gap-5 items-start">

        {{-- ── Section Sidebar ────────────────────────────────────────────────── --}}
        <div class="w-60 flex-shrink-0 space-y-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider px-1 mb-3">أقسام الصفحة</p>
            @foreach($sectionMeta as $sec => $meta)
            <a href="{{ request()->fullUrlWithQuery(['section' => $sec, 'lang' => $currentLang]) }}"
               class="w-full flex items-center gap-3 px-3.5 py-3 rounded-xl border transition-all text-start
                      {{ $currentSection === $sec
                          ? 'bg-white border-orange-200 shadow-sm text-gray-900'
                          : 'bg-white/60 border-transparent text-gray-600 hover:bg-white hover:border-gray-200' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="{{ $currentSection === $sec ? 'background:#FFF4EA;' : 'background:#f1f5f9;' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="{{ $currentSection === $sec ? 'stroke:#FF8528;' : 'stroke:#64748b;' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $meta['icon'] }}"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold leading-tight truncate">{{ $meta['label'] }}</p>
                    <p class="text-xs text-gray-400 truncate mt-0.5">{{ $meta['desc'] }}</p>
                </div>
                @if($currentSection === $sec)
                <div class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#FF8528;"></div>
                @endif
            </a>
            @endforeach
        </div>

        {{-- ── Content Editor ──────────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0">

            {{-- Section header --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 mb-4 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#FFF4EA;">
                    <svg class="w-5 h-5" fill="none" stroke="#FF8528" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $activeMeta['icon'] }}"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="font-bold text-gray-900 text-base leading-tight">{{ $activeMeta['label'] }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $activeMeta['desc'] }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold text-white" style="background:#FF8528;">
                        {{ strtoupper($currentLang) }}
                    </span>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-600">
                        {{ $currentSection }}
                    </span>
                </div>
            </div>

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="text-sm text-red-700">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Content blocks form --}}
            @if(!empty($blocks))
            <form method="POST" action="{{ route('admin.content.save') }}">
                @csrf
                <input type="hidden" name="section" value="{{ $currentSection }}">
                <input type="hidden" name="lang" value="{{ $currentLang }}">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="divide-y divide-gray-50">
                        @foreach($blocks as $key => $value)
                        @php
                            $label = keyLabel($key);
                            $isLong = mb_strlen($value ?? '') > 80 || str_contains((string)$value, "\n");
                            $isTextarea = $isLong
                                || str_contains(strtolower($key), 'desc')
                                || str_contains(strtolower($key), 'body')
                                || str_contains(strtolower($key), 'content')
                                || str_contains(strtolower($key), 'lead')
                                || str_contains(strtolower($key), 'subtitle');
                            $rows = $isLong
                                ? min(max(substr_count((string)$value, "\n") + 2, 3), 8)
                                : 3;
                        @endphp
                        <div class="px-6 py-4 flex items-start gap-5 hover:bg-gray-50/50 transition-colors">
                            {{-- Label column --}}
                            <div class="w-44 flex-shrink-0 pt-2.5">
                                <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $label }}</p>
                                <p class="text-xs text-gray-400 mt-1 font-mono break-all">{{ $key }}</p>
                            </div>
                            {{-- Input column --}}
                            <div class="flex-1 min-w-0">
                                @if($isTextarea)
                                <textarea name="blocks[{{ $key }}]"
                                          rows="{{ $rows }}"
                                          dir="{{ $currentLang === 'ar' ? 'rtl' : 'ltr' }}"
                                          class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-y transition-shadow">{{ old('blocks.' . $key, $value) }}</textarea>
                                @else
                                <input type="text"
                                       name="blocks[{{ $key }}]"
                                       value="{{ old('blocks.' . $key, $value) }}"
                                       dir="{{ $currentLang === 'ar' ? 'rtl' : 'ltr' }}"
                                       class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition-shadow">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Save footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-xs text-gray-400">التغييرات ستظهر فوراً على الموقع بعد الحفظ</p>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold shadow-sm transition-all hover:opacity-90 active:scale-[0.98]"
                                style="background:#FF8528;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
            @else
            {{-- Empty state --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background:#FFF4EA;">
                    <svg class="w-7 h-7" fill="none" stroke="#FF8528" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-gray-700 font-semibold text-sm mb-1">لا توجد محتويات لهذا القسم</p>
                <p class="text-gray-400 text-xs">القسم <span class="font-mono font-bold">{{ $currentSection }}</span> لا يحتوي على بيانات باللغة <span class="font-bold">{{ strtoupper($currentLang) }}</span> حتى الآن.</p>
                <p class="text-gray-400 text-xs mt-2">يمكنك استيراد ترجمات عبر JSON أو اختيار لغة أو قسم آخر.</p>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection
