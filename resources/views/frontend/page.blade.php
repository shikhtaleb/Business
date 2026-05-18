<!doctype html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>{{ $page->meta_title ?: ($page->getTitle($lang) ?: ($settings['site_name'] ?? config('app.name'))) }}</title>
@if($page->meta_desc)
<meta name="description" content="{{ $page->meta_desc }}"/>
@endif
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{brand:{DEFAULT:'#FF8528',500:'#FF8528',600:'#E06800'}}}}}</script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
<style>
    [x-cloak]{display:none!important}
    body{font-family:'IBM Plex Sans Arabic','Inter',sans-serif;}
    [dir="ltr"] body{font-family:'Inter','IBM Plex Sans Arabic',sans-serif;}
</style>
</head>
<body class="bg-white text-gray-900 antialiased">

{{-- Simple Nav --}}
<nav class="border-b border-gray-100 px-6 py-4">
    <div class="max-w-5xl mx-auto flex items-center justify-between">
        <a href="{{ url('/') }}" class="font-bold text-gray-900 hover:opacity-80 transition-opacity">
            {{ $settings['site_name'] ?? config('app.name') }}
        </a>
        <a href="{{ url('/') }}"
           class="text-sm text-gray-500 hover:text-gray-800 transition-colors flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="{{ $lang === 'ar' ? 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' : 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' }}"/>
            </svg>
            {{ $lang === 'ar' ? 'الرئيسية' : 'Home' }}
        </a>
    </div>
</nav>

{{-- Page blocks --}}
<main>
    @if (!empty($page->blocks))
        <x-page-blocks :blocks="$page->blocks" :lang="$lang"/>
    @else
        <div class="max-w-3xl mx-auto px-6 py-16" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $page->getTitle($lang) }}</h1>
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($page->getBody($lang))) !!}
            </div>
        </div>
    @endif
</main>

{{-- Footer --}}
<footer class="border-t border-gray-100 px-6 py-8 mt-10">
    <p class="text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} {{ $settings['site_name'] ?? config('app.name') }}
    </p>
</footer>

</body>
</html>
