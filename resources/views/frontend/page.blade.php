<!doctype html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>{{ $page->meta_title ?: ($page->getTitle($lang) ?: ($settings['site_name'] ?? config('app.name'))) }}</title>
@if($page->meta_desc)
<meta name="description" content="{{ $page->meta_desc }}"/>
@endif
<link rel="stylesheet" href="{{ asset('styles.css') }}">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{brand:{DEFAULT:'{{ $settings['brand_color'] ?? '#FF8528' }}',500:'{{ $settings['brand_color'] ?? '#FF8528' }}',600:'#E06800'}}}}}</script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
<style>
:root { --brand: {{ $settings['brand_color'] ?? '#FF8528' }}; }
[x-cloak]{display:none!important}
body{font-family:'IBM Plex Sans Arabic','Inter',sans-serif;}
[dir="ltr"] body{font-family:'Inter','IBM Plex Sans Arabic',sans-serif;}
/* Nav dropdown */
.nav-dropdown{position:relative;display:inline-flex;align-items:center}
.nav-dropdown-btn{display:inline-flex;align-items:center;gap:4px;background:none;border:none;cursor:pointer;font:inherit;font-size:.875rem;font-weight:500;color:#64748b;padding:0;transition:color .2s}
.nav-dropdown-btn:hover,.nav-dropdown-btn.open{color:#0f172a}
.nav-dropdown-btn svg{transition:transform .2s}
.nav-dropdown-btn.open svg{transform:rotate(180deg)}
.nav-dropdown-menu{display:none;position:absolute;top:calc(100% + 8px);inset-inline-start:50%;transform:translateX(-50%);background:#fff;border:1px solid #e2e8f0;border-radius:.75rem;box-shadow:0 8px 24px rgba(0,0,0,.1);padding:.4rem;min-width:160px;z-index:100}
.nav-dropdown-menu.open{display:block}
.nav-dropdown-menu a{display:block;padding:.5rem .75rem;border-radius:.5rem;font-size:.875rem;color:#64748b;text-decoration:none;transition:background .15s,color .15s;white-space:nowrap}
.nav-dropdown-menu a:hover{background:#fff4ea;color:var(--brand)}
.foot-links{display:flex;flex-wrap:wrap;gap:.5rem 1.25rem;justify-content:center}
.foot-links a{font-size:.8rem;color:#94a3b8;text-decoration:none;transition:color .15s}
.foot-links a:hover{color:var(--brand)}
</style>
</head>
<body class="bg-white text-gray-900 antialiased {{ (!empty($settings['dark_mode']) && $settings['dark_mode'] === 'dark') ? 'dark' : '' }}">

<header class="nav">
  <div class="wrap nav-inner">
    <div class="brand">
      @if(!empty($settings['logo_url']))
        <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" style="width:32px;height:32px;object-fit:contain"/>
      @else
        <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? config('app.name') }}"/>
      @endif
      <span>{{ $settings['site_name'] ?? config('app.name') }}</span>
    </div>
    <nav class="nav-links">
      @if(!empty($menus['header']) && $menus['header']->rootItems->isNotEmpty())
        <x-frontend-menu :menu="$menus['header']" :lang="$lang"/>
      @else
        <a href="{{ url('/') }}">{{ $lang === 'ar' ? 'الرئيسية' : 'Home' }}</a>
      @endif
    </nav>
    <div class="nav-cta">
      <button class="nav-toggle" id="navToggle" aria-label="menu" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>
</header>

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

<footer>
  <div class="wrap foot">
    <div class="brand">
      @if(!empty($settings['logo_url']))
        <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" style="width:28px;height:28px;object-fit:contain"/>
      @else
        <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" style="width:28px;height:28px;object-fit:contain"/>
      @endif
      <span style="font-size:16px">{{ $settings['site_name'] ?? config('app.name') }}</span>
    </div>
    @if(!empty($menus['footer']) && $menus['footer']->rootItems->isNotEmpty())
    <nav class="foot-links">
      <x-frontend-menu :menu="$menus['footer']" :lang="$lang"/>
    </nav>
    @endif
    <div>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? config('app.name') }}.</div>
    <div class="social">
      <div class="lang-wrap">
        <button id="langBtn" aria-label="Language" aria-haspopup="true" aria-expanded="false">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a13.5 13.5 0 0 1 0 18M12 3a13.5 13.5 0 0 0 0 18"/></svg>
        </button>
        <div class="lang-menu" id="langMenu" role="menu">
          <a href="?lang=ar" role="menuitem" class="{{ $lang === 'ar' ? 'active' : '' }}"><span class="flag">🇸🇦</span><span class="lbl">العربية</span>@if($lang === 'ar')<span class="ck">✓</span>@endif</a>
          <a href="?lang=en" role="menuitem" class="{{ $lang === 'en' ? 'active' : '' }}"><span class="flag">🇬🇧</span><span class="lbl">English</span>@if($lang === 'en')<span class="ck">✓</span>@endif</a>
          <a href="?lang=nl" role="menuitem" class="{{ $lang === 'nl' ? 'active' : '' }}"><span class="flag">🇳🇱</span><span class="lbl">Nederlands</span>@if($lang === 'nl')<span class="ck">✓</span>@endif</a>
          <a href="?lang=de" role="menuitem" class="{{ $lang === 'de' ? 'active' : '' }}"><span class="flag">🇩🇪</span><span class="lbl">Deutsch</span>@if($lang === 'de')<span class="ck">✓</span>@endif</a>
        </div>
      </div>
    </div>
  </div>
</footer>

<script src="{{ asset('app.js') }}"></script>
<script>
document.querySelectorAll('.nav-dropdown-btn').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    const menu = this.nextElementSibling;
    const isOpen = menu.classList.contains('open');
    document.querySelectorAll('.nav-dropdown-menu.open').forEach(m => m.classList.remove('open'));
    document.querySelectorAll('.nav-dropdown-btn.open').forEach(b => b.classList.remove('open'));
    if (!isOpen) { menu.classList.add('open'); this.classList.add('open'); }
  });
});
document.addEventListener('click', () => {
  document.querySelectorAll('.nav-dropdown-menu.open').forEach(m => m.classList.remove('open'));
  document.querySelectorAll('.nav-dropdown-btn.open').forEach(b => b.classList.remove('open'));
});
</script>
</body>
</html>
