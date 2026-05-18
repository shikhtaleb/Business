@php
    $blocks = $blocks ?? [];
    $lang   = $lang ?? 'ar';
    $dir    = $lang === 'ar' ? 'rtl' : 'ltr';
    $t = fn($field, $block) => $block['content'][$field][$lang]
        ?? $block['content'][$field]['ar']
        ?? $block['content'][$field]['en']
        ?? '';
@endphp

@foreach ($blocks as $block)
@php $type = $block['type'] ?? ''; $s = $block['settings'] ?? []; $c = $block['content'] ?? []; @endphp

{{-- ── HERO ─────────────────────────────────────────────────────── --}}
@if ($type === 'hero')
@php
    $bgStyle  = 'background-color:' . ($s['bg_color'] ?? '#0f172a') . ';';
    if (!empty($s['bg_image'])) {
        $bgStyle .= 'background-image:url(' . e($s['bg_image']) . ');background-size:cover;background-position:center;';
    }
    $heights  = ['sm' => '320px', 'md' => '480px', 'lg' => '600px', 'full' => '100vh'];
    $ht       = $heights[$s['height'] ?? 'lg'] ?? '600px';
    $align    = $s['align'] ?? 'center';
    $txtColor = $s['text_color'] ?? '#ffffff';
@endphp
<section style="{{ $bgStyle }} min-height:{{ $ht }}; color:{{ $txtColor }};"
         class="flex items-center">
    <div class="w-full max-w-5xl mx-auto px-6 py-16 text-{{ $align }}" dir="{{ $dir }}">
        @if (!empty($c['title'][$lang] ?? $c['title']['ar'] ?? ''))
        <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4" style="color:{{ $txtColor }};">
            {{ $c['title'][$lang] ?? $c['title']['ar'] ?? '' }}
        </h1>
        @endif
        @if (!empty($c['subtitle'][$lang] ?? $c['subtitle']['ar'] ?? ''))
        <p class="text-lg md:text-xl opacity-85 mb-8 max-w-2xl {{ $align === 'center' ? 'mx-auto' : '' }}"
           style="color:{{ $txtColor }};">
            {{ $c['subtitle'][$lang] ?? $c['subtitle']['ar'] ?? '' }}
        </p>
        @endif
        @php $btnText = $c['btn_text'][$lang] ?? $c['btn_text']['ar'] ?? ''; @endphp
        @if ($btnText && !empty($c['btn_url']))
        <a href="{{ $c['btn_url'] }}"
           class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl font-semibold text-sm transition-opacity hover:opacity-90"
           style="background:#FF8528; color:#ffffff;">
            {{ $btnText }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="{{ $dir === 'rtl' ? 'M19 12H5m7-7l7 7-7 7' : 'M5 12h14m-7-7l7 7-7 7' }}"/>
            </svg>
        </a>
        @endif
    </div>
</section>

{{-- ── HEADING ──────────────────────────────────────────────────── --}}
@elseif ($type === 'heading')
@php
    $tag   = in_array($s['level'] ?? 'h2', ['h1','h2','h3','h4']) ? ($s['level'] ?? 'h2') : 'h2';
    $sizes = ['h1'=>'text-4xl','h2'=>'text-3xl','h3'=>'text-2xl','h4'=>'text-xl'];
    $txt   = $c['text'][$lang] ?? $c['text']['ar'] ?? '';
@endphp
<div class="w-full max-w-5xl mx-auto px-6 py-3" dir="{{ $dir }}">
    @if ($txt)
    <{{ $tag }} class="{{ $sizes[$tag] ?? 'text-3xl' }} font-bold text-{{ $s['align'] ?? 'center' }}"
                style="color:{{ $s['color'] ?? '#111827' }};">
        {{ $txt }}
    </{{ $tag }}>
    @endif
</div>

{{-- ── PARAGRAPH ────────────────────────────────────────────────── --}}
@elseif ($type === 'paragraph')
@php
    $txt   = $c['text'][$lang] ?? $c['text']['ar'] ?? '';
    $sizes = ['sm'=>'text-sm','base'=>'text-base','lg'=>'text-lg'];
@endphp
<div class="w-full max-w-3xl mx-auto px-6 py-3" dir="{{ $dir }}">
    @if ($txt)
    <p class="{{ $sizes[$s['size'] ?? 'base'] ?? 'text-base' }} text-gray-700 leading-relaxed text-{{ $s['align'] ?? ($lang === 'ar' ? 'right' : 'left') }}">
        {!! nl2br(e($txt)) !!}
    </p>
    @endif
</div>

{{-- ── IMAGE ────────────────────────────────────────────────────── --}}
@elseif ($type === 'image')
@php
    $url     = $c['url'] ?? '';
    $alt     = $c['alt'][$lang] ?? $c['alt']['ar'] ?? '';
    $caption = $c['caption'][$lang] ?? $c['caption']['ar'] ?? '';
    $maxW    = ['full'=>'max-w-none','lg'=>'max-w-4xl','md'=>'max-w-2xl','sm'=>'max-w-lg'][$s['size'] ?? 'full'] ?? 'max-w-none';
@endphp
@if ($url)
<div class="w-full {{ $maxW }} mx-auto px-6 py-4">
    <figure>
        <img src="{{ $url }}" alt="{{ $alt }}"
             class="w-full {{ ($s['rounded'] ?? true) ? 'rounded-2xl' : '' }} {{ ($s['shadow'] ?? false) ? 'shadow-xl' : '' }} object-cover"
             loading="lazy">
        @if ($caption)
        <figcaption class="text-sm text-gray-500 text-center mt-3 italic" dir="{{ $dir }}">{{ $caption }}</figcaption>
        @endif
    </figure>
</div>
@endif

{{-- ── TWO COLUMNS ──────────────────────────────────────────────── --}}
@elseif ($type === 'two_columns')
@php
    $ratioMap = ['1/1'=>'1fr 1fr','2/1'=>'2fr 1fr','1/2'=>'1fr 2fr','3/2'=>'3fr 2fr','2/3'=>'2fr 3fr'];
    $gridCols = $ratioMap[$s['ratio'] ?? '1/1'] ?? '1fr 1fr';
    $leftType  = $c['left_type']  ?? 'text';
    $rightType = $c['right_type'] ?? 'text';
@endphp
<div class="w-full max-w-5xl mx-auto px-6 py-6">
    <div class="grid gap-8 items-center" style="grid-template-columns:{{ $gridCols }};">
        <div dir="{{ $dir }}">
            @if ($leftType === 'image')
                @if (!empty($c['left_image']))
                <img src="{{ $c['left_image'] }}" class="w-full rounded-2xl object-cover" loading="lazy">
                @endif
            @else
                <p class="text-gray-700 leading-relaxed">
                    {!! nl2br(e($c['left_text'][$lang] ?? $c['left_text']['ar'] ?? '')) !!}
                </p>
            @endif
        </div>
        <div dir="{{ $dir }}">
            @if ($rightType === 'image')
                @if (!empty($c['right_image']))
                <img src="{{ $c['right_image'] }}" class="w-full rounded-2xl object-cover" loading="lazy">
                @endif
            @else
                <p class="text-gray-700 leading-relaxed">
                    {!! nl2br(e($c['right_text'][$lang] ?? $c['right_text']['ar'] ?? '')) !!}
                </p>
            @endif
        </div>
    </div>
</div>

{{-- ── CTA ───────────────────────────────────────────────────────── --}}
@elseif ($type === 'cta')
@php
    $bg  = $s['bg_color']   ?? '#FF8528';
    $clr = $s['text_color'] ?? '#ffffff';
    $al  = $s['align']      ?? 'center';
@endphp
<section style="background:{{ $bg }}; color:{{ $clr }};" class="py-16 px-6">
    <div class="max-w-3xl mx-auto text-{{ $al }}" dir="{{ $dir }}">
        @php $title = $c['title'][$lang] ?? $c['title']['ar'] ?? ''; @endphp
        @if ($title)
        <h2 class="text-3xl font-bold mb-4" style="color:{{ $clr }};">{{ $title }}</h2>
        @endif
        @php $text = $c['text'][$lang] ?? $c['text']['ar'] ?? ''; @endphp
        @if ($text)
        <p class="text-lg mb-8 opacity-90" style="color:{{ $clr }};">{{ $text }}</p>
        @endif
        @php $btnText = $c['btn_text'][$lang] ?? $c['btn_text']['ar'] ?? ''; @endphp
        @if ($btnText && !empty($c['btn_url']))
        <a href="{{ $c['btn_url'] }}"
           class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl font-semibold text-sm transition-opacity hover:opacity-90 border-2"
           style="background:#ffffff; color:{{ $bg }}; border-color:#ffffff;">
            {{ $btnText }}
        </a>
        @endif
    </div>
</section>

{{-- ── FEATURES ─────────────────────────────────────────────────── --}}
@elseif ($type === 'features')
@php
    $cols    = $s['columns'] ?? 3;
    $colsMap = [2=>'grid-cols-2', 3=>'grid-cols-1 sm:grid-cols-3', 4=>'grid-cols-2 lg:grid-cols-4'];
    $colsCls = $colsMap[$cols] ?? 'grid-cols-1 sm:grid-cols-3';
    $items   = $c['items'] ?? [];
@endphp
<div class="w-full max-w-5xl mx-auto px-6 py-10">
    <div class="grid {{ $colsCls }} gap-6">
        @foreach ($items as $item)
        <div class="text-center p-5" dir="{{ $dir }}">
            @if (!empty($item['icon']))
            <div class="text-4xl mb-4">{{ $item['icon'] }}</div>
            @endif
            @php $ititle = $item['title'][$lang] ?? $item['title']['ar'] ?? ''; @endphp
            @if ($ititle)
            <h3 class="text-base font-bold text-gray-900 mb-2">{{ $ititle }}</h3>
            @endif
            @php $itext = $item['text'][$lang] ?? $item['text']['ar'] ?? ''; @endphp
            @if ($itext)
            <p class="text-sm text-gray-600 leading-relaxed">{{ $itext }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- ── FAQ ───────────────────────────────────────────────────────── --}}
@elseif ($type === 'faq')
@php $faqTitle = $c['title'][$lang] ?? $c['title']['ar'] ?? ''; @endphp
<div class="w-full max-w-3xl mx-auto px-6 py-10" x-data="{open:null}">
    @if ($faqTitle)
    <h2 class="text-2xl font-bold text-gray-900 text-{{ $s['title_align'] ?? 'center' }} mb-8" dir="{{ $dir }}">
        {{ $faqTitle }}
    </h2>
    @endif
    <div class="space-y-3">
        @foreach (($c['items'] ?? []) as $fi => $fItem)
        @php
            $q = $fItem['question'][$lang] ?? $fItem['question']['ar'] ?? '';
            $a = $fItem['answer'][$lang]   ?? $fItem['answer']['ar']   ?? '';
        @endphp
        @if ($q)
        <div class="border border-gray-200 rounded-2xl overflow-hidden" x-data>
            <button type="button" @click="open === {{ $fi }} ? open=null : open={{ $fi }}"
                    class="w-full flex items-center justify-between px-5 py-4 text-start hover:bg-gray-50 transition-colors"
                    dir="{{ $dir }}">
                <span class="font-semibold text-gray-800 text-sm">{{ $q }}</span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform ms-3"
                     :class="open === {{ $fi }} ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open === {{ $fi }}" x-collapse class="border-t border-gray-100">
                <div class="px-5 py-4 text-sm text-gray-600 leading-relaxed" dir="{{ $dir }}">
                    {!! nl2br(e($a)) !!}
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>

{{-- ── DIVIDER ──────────────────────────────────────────────────── --}}
@elseif ($type === 'divider')
@php
    $spacings = ['sm'=>'py-4','md'=>'py-8','lg'=>'py-14','xl'=>'py-20'];
    $spacing  = $spacings[$s['height'] ?? 'md'] ?? 'py-8';
@endphp
<div class="{{ $spacing }} px-6 max-w-5xl mx-auto">
    @if (($s['style'] ?? 'line') === 'line')
    <hr style="border-color:{{ $s['color'] ?? '#e5e7eb' }};">
    @elseif (($s['style'] ?? '') === 'dots')
    <div class="flex justify-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $s['color'] ?? '#e5e7eb' }};"></span>
        <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $s['color'] ?? '#e5e7eb' }};"></span>
        <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $s['color'] ?? '#e5e7eb' }};"></span>
    </div>
    @endif
</div>

{{-- ── HTML ──────────────────────────────────────────────────────── --}}
@elseif ($type === 'html')
@if (!empty($c['code']))
{!! $c['code'] !!}
@endif

@endif
@endforeach
