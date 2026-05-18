<!doctype html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $lang === 'ar' ? 'المدونة' : 'Blog' }} — {{ $seo['title'] ?? ($settings['site_name'] ?? config('app.name')) }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}" />
@if(!empty($seo['keywords']))
<meta name="keywords" content="{{ $seo['keywords'] }}" />
@endif
@if(!empty($seo['robots']))
<meta name="robots" content="{{ $seo['robots'] }}" />
@endif
<meta property="og:title" content="{{ $lang === 'ar' ? 'المدونة' : 'Blog' }} — {{ $seo['og_title'] ?? ($seo['title'] ?? '') }}" />
<meta property="og:description" content="{{ $seo['og_desc'] ?? ($seo['description'] ?? '') }}" />
@if(!empty($seo['og_image']))
<meta property="og:image" content="{{ $seo['og_image'] }}" />
@endif
<meta property="og:type" content="website" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@if(!empty($settings['font_family']))
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ urlencode($settings['font_family']) }}:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
@else
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
@endif
<link rel="stylesheet" href="{{ asset('styles.css') }}" />
@if(!empty($settings['ga_id']))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $settings['ga_id'] }}');
</script>
@endif
<style>
:root {
  --brand:       {{ $settings['brand_color'] ?? '#FF8528' }};
  --brand-light: color-mix(in srgb, {{ $settings['brand_color'] ?? '#FF8528' }} 15%, transparent);
  --brand-soft:  color-mix(in srgb, {{ $settings['brand_color'] ?? '#FF8528' }} 8%, transparent);
  --brand-hover: color-mix(in srgb, {{ $settings['brand_color'] ?? '#FF8528' }} 85%, #000);
}

/* ── Blog-specific styles ─────────────────────────────────────────── */
.blog-hero {
  background: linear-gradient(135deg, var(--brand-soft) 0%, transparent 60%);
  padding: 5rem 0 3.5rem;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.blog-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 70% 40%, var(--brand-light) 0%, transparent 55%);
  pointer-events: none;
}
.blog-hero h1 {
  font-size: clamp(2rem, 5vw, 3rem);
  font-weight: 700;
  margin: 0 0 .75rem;
  color: var(--text, #1a1a1a);
}
.blog-hero h1 .accent { color: var(--brand); }
.blog-hero p {
  font-size: 1.1rem;
  color: var(--text-muted, #666);
  max-width: 520px;
  margin: 0 auto;
}
.blog-hero .back-link {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  margin-top: 1.5rem;
  color: var(--brand);
  font-size: .9rem;
  font-weight: 500;
  text-decoration: none;
  transition: opacity .2s;
}
.blog-hero .back-link:hover { opacity: .75; }

.blog-grid-section {
  padding: 3.5rem 0 5rem;
}
.blog-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.75rem;
}
@media (max-width: 900px) {
  .blog-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 560px) {
  .blog-grid { grid-template-columns: 1fr; }
}

.post-card {
  background: var(--card-bg, #fff);
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,.06);
  border: 1px solid var(--border, #f0f0f0);
  display: flex;
  flex-direction: column;
  transition: transform .22s, box-shadow .22s;
}
.post-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 32px rgba(0,0,0,.12);
}
.post-card-img {
  width: 100%;
  aspect-ratio: 16/9;
  object-fit: cover;
  display: block;
}
.post-card-img-placeholder {
  width: 100%;
  aspect-ratio: 16/9;
  background: linear-gradient(135deg, var(--brand-soft) 0%, var(--brand-light) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}
.post-card-img-placeholder svg {
  width: 48px;
  height: 48px;
  color: var(--brand);
  opacity: .5;
}
.post-card-body {
  padding: 1.25rem 1.4rem 1.5rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.post-card-cat {
  display: inline-block;
  background: var(--brand-soft);
  color: var(--brand);
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .04em;
  padding: .25rem .7rem;
  border-radius: 999px;
  margin-bottom: .85rem;
  text-transform: uppercase;
}
.post-card-title {
  font-size: 1.05rem;
  font-weight: 700;
  line-height: 1.45;
  color: var(--text, #1a1a1a);
  margin: 0 0 .65rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.post-card-excerpt {
  font-size: .875rem;
  color: var(--text-muted, #666);
  line-height: 1.7;
  margin: 0 0 1.1rem;
  flex: 1;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.post-card-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .5rem;
  margin-top: auto;
  padding-top: 1rem;
  border-top: 1px solid var(--border, #f0f0f0);
}
.post-card-author {
  display: flex;
  align-items: center;
  gap: .5rem;
  font-size: .8rem;
  color: var(--text-muted, #666);
}
.post-card-author-avatar {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: var(--brand-light);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .75rem;
  font-weight: 700;
  color: var(--brand);
  flex-shrink: 0;
}
.post-card-date {
  font-size: .78rem;
  color: var(--text-muted, #999);
  white-space: nowrap;
}
.post-card-read {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  color: var(--brand);
  font-size: .85rem;
  font-weight: 600;
  text-decoration: none;
  margin-top: 1rem;
  transition: gap .2s;
}
.post-card-read:hover { gap: .6rem; }
.post-card-read svg { width: 14px; height: 14px; transition: transform .2s; }
.post-card-read:hover svg { transform: {{ $lang === 'ar' ? 'translateX(-3px)' : 'translateX(3px)' }}; }

/* Empty state */
.blog-empty {
  text-align: center;
  padding: 4rem 1rem;
  color: var(--text-muted, #888);
}
.blog-empty svg {
  width: 64px;
  height: 64px;
  margin: 0 auto 1.25rem;
  color: var(--brand);
  opacity: .4;
}
.blog-empty h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: .5rem;
  color: var(--text, #333);
}

/* Pagination */
.blog-pagination {
  margin-top: 3rem;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: .4rem;
  flex-wrap: wrap;
}
.blog-pagination a,
.blog-pagination span {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 40px;
  height: 40px;
  padding: 0 .75rem;
  border-radius: .65rem;
  font-size: .88rem;
  font-weight: 500;
  text-decoration: none;
  transition: background .18s, color .18s;
  color: var(--text, #333);
  background: var(--card-bg, #fff);
  border: 1px solid var(--border, #e2e8f0);
}
.blog-pagination a:hover {
  background: var(--brand-soft);
  border-color: var(--brand);
  color: var(--brand);
}
.blog-pagination .active span,
.blog-pagination span[aria-current="page"] {
  background: var(--brand);
  color: #fff;
  border-color: var(--brand);
  pointer-events: none;
}
.blog-pagination .disabled span {
  opacity: .4;
  pointer-events: none;
}
</style>
</head>
<body class="{{ (!empty($settings['dark_mode']) && $settings['dark_mode'] === 'dark') ? 'dark' : '' }}">

<!-- NAV -->
<header class="nav">
  <div class="wrap nav-inner">
    <div class="brand">
      @if(!empty($settings['logo_url']))
        @if(!empty($settings['dark_logo_url']))
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" class="logo-light" />
          <img src="{{ $settings['dark_logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" class="logo-dark" />
        @else
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" />
        @endif
      @else
      <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" />
      @endif
      <span>{{ $settings['site_name'] ?? config('app.name') }}</span>
    </div>
    <nav class="nav-links">
      @if(!empty($menus['header']) && $menus['header']->rootItems->isNotEmpty())
        <x-frontend-menu :menu="$menus['header']" :lang="$lang" />
      @else
        <a href="{{ url('/') }}">{{ $lang === 'ar' ? 'الرئيسية' : 'Home' }}</a>
        <a href="{{ url('/') }}#features">{{ $lang === 'ar' ? 'المميزات' : 'Features' }}</a>
        <a href="{{ url('/') }}#pricing">{{ $lang === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
        <a href="{{ route('blog.index') }}" style="color:var(--brand)">{{ $lang === 'ar' ? 'المدونة' : 'Blog' }}</a>
        <a href="{{ url('/') }}#contact">{{ $lang === 'ar' ? 'تواصل معنا' : 'Contact' }}</a>
      @endif
    </nav>
    <div class="nav-cta">
      <button class="nav-toggle" id="navToggle" aria-label="menu" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <button class="theme-toggle" id="themeToggle" aria-label="theme" aria-pressed="false">
        <svg class="moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
        <svg class="sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
      </button>
      <a href="{{ route('admin.login') }}" class="btn btn-ghost">{{ $lang === 'ar' ? 'تسجيل الدخول' : 'Login' }}</a>
      <a href="{{ url('/') }}#contact" class="btn btn-primary">{{ $lang === 'ar' ? 'ابدأ الآن' : 'Get Started' }}</a>
    </div>
  </div>
</header>

<!-- BLOG HERO -->
<section class="blog-hero">
  <div class="wrap">
    <h1>{{ $lang === 'ar' ? 'مد' : 'Our ' }}<span class="accent">{{ $lang === 'ar' ? 'ونة' : 'Blog' }}</span>{{ $lang === 'ar' ? '' : '' }}</h1>
    @if($lang === 'ar')
    <h1 style="display:none"></h1>
    @endif
    <p>
      {{ $lang === 'ar'
        ? 'أحدث المقالات والنصائح في إدارة الأعمال والتقنية'
        : 'Latest articles, tips and insights on business management' }}
    </p>
    <a href="{{ url('/') }}" class="back-link">
      @if($lang === 'ar')
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
      العودة للرئيسية
      @else
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M15 18l-6-6 6-6"/></svg>
      Back to Home
      @endif
    </a>
  </div>
</section>

<!-- POSTS GRID -->
<section class="blog-grid-section">
  <div class="wrap">
    @if($posts->isEmpty())
      <div class="blog-empty">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <h3>{{ $lang === 'ar' ? 'لا توجد مقالات حتى الآن' : 'No articles yet' }}</h3>
        <p>{{ $lang === 'ar' ? 'تابعنا قريبًا لأحدث المقالات.' : 'Stay tuned for upcoming articles.' }}</p>
      </div>
    @else
      <div class="blog-grid">
        @foreach($posts as $post)
        <article class="post-card">
          {{-- Featured Image --}}
          @if(!empty($post->featured_image))
            <img
              src="{{ $post->featured_image }}"
              alt="{{ $post->getTitle($lang) }}"
              class="post-card-img"
              loading="lazy"
            />
          @else
            <div class="post-card-img-placeholder">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
          @endif

          <div class="post-card-body">
            {{-- Category Badge --}}
            @if($post->category)
              <span class="post-card-cat">
                {{ $post->category->{"name_{$lang}"} ?? $post->category->name_ar ?? $post->category->name ?? '' }}
              </span>
            @endif

            {{-- Title --}}
            <h2 class="post-card-title">{{ $post->getTitle($lang) }}</h2>

            {{-- Excerpt --}}
            @php $excerpt = $post->getExcerpt($lang); @endphp
            @if($excerpt)
              <p class="post-card-excerpt">{{ $excerpt }}</p>
            @endif

            {{-- Meta: author + date --}}
            <div class="post-card-meta">
              <div class="post-card-author">
                <div class="post-card-author-avatar">
                  {{ mb_substr($post->author->name ?? '؟', 0, 1) }}
                </div>
                <span>{{ $post->author->name ?? ($lang === 'ar' ? 'الفريق' : 'Team') }}</span>
              </div>
              <span class="post-card-date">
                {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
              </span>
            </div>

            {{-- Read More --}}
            <a href="{{ route('blog.show', $post->slug) }}" class="post-card-read">
              {{ $lang === 'ar' ? 'اقرأ المزيد' : 'Read more' }}
              @if($lang === 'ar')
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
              @else
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
              @endif
            </a>
          </div>
        </article>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($posts->hasPages())
        <div class="blog-pagination">
          {{-- Previous --}}
          @if($posts->onFirstPage())
            <span class="disabled">
              <span>{{ $lang === 'ar' ? '→' : '←' }}</span>
            </span>
          @else
            <a href="{{ $posts->previousPageUrl() }}" aria-label="{{ $lang === 'ar' ? 'السابق' : 'Previous' }}">
              {{ $lang === 'ar' ? '→' : '←' }}
            </a>
          @endif

          {{-- Page numbers --}}
          @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
            @if($page == $posts->currentPage())
              <span aria-current="page"><span>{{ $page }}</span></span>
            @else
              <a href="{{ $url }}">{{ $page }}</a>
            @endif
          @endforeach

          {{-- Next --}}
          @if($posts->hasMorePages())
            <a href="{{ $posts->nextPageUrl() }}" aria-label="{{ $lang === 'ar' ? 'التالي' : 'Next' }}">
              {{ $lang === 'ar' ? '←' : '→' }}
            </a>
          @else
            <span class="disabled">
              <span>{{ $lang === 'ar' ? '←' : '→' }}</span>
            </span>
          @endif
        </div>
      @endif
    @endif
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="wrap foot">
    <div class="brand">
      @if(!empty($settings['logo_url']))
        @if(!empty($settings['dark_logo_url']))
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" class="logo-light" style="width:28px;height:28px;object-fit:contain" />
          <img src="{{ $settings['dark_logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" class="logo-dark" style="width:28px;height:28px;object-fit:contain" />
        @else
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" style="width:28px;height:28px;object-fit:contain" />
        @endif
      @else
      <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? config('app.name') }}" style="width:28px;height:28px;object-fit:contain" />
      @endif
      <span style="font-size:16px">{{ $settings['site_name'] ?? config('app.name') }}</span>
    </div>
    @if(!empty($menus['footer']) && $menus['footer']->rootItems->isNotEmpty())
    <nav class="foot-links">
      <x-frontend-menu :menu="$menus['footer']" :lang="$lang" />
    </nav>
    @endif
    <div>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? config('app.name') }}. {{ $lang === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</div>
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
