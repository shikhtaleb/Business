<!doctype html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $seo['title'] ?? ($post->{"title_{$lang}"} ?? $post->title_ar ?? ($settings['site_name'] ?? 'Retont Business')) }}</title>
<meta name="description" content="{{ $seo['description'] ?? ($post->{"excerpt_{$lang}"} ?? $post->excerpt_ar ?? '') }}" />
@if(!empty($seo['keywords']))
<meta name="keywords" content="{{ $seo['keywords'] }}" />
@endif
@if(!empty($seo['robots']))
<meta name="robots" content="{{ $seo['robots'] }}" />
@endif
<!-- Open Graph -->
<meta property="og:title" content="{{ $seo['title'] ?? ($post->{"title_{$lang}"} ?? $post->title_ar ?? '') }}" />
<meta property="og:description" content="{{ $seo['description'] ?? ($post->{"excerpt_{$lang}"} ?? $post->excerpt_ar ?? '') }}" />
@if(!empty($seo['og_image']))
<meta property="og:image" content="{{ $seo['og_image'] }}" />
@elseif(!empty($post->featured_image))
<meta property="og:image" content="{{ $post->featured_image }}" />
@endif
<meta property="og:type" content="article" />
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

/* ── Reading progress bar ──────────────────────────────────────────── */
#reading-progress {
  position: fixed;
  top: 0;
  {{ $lang === 'ar' ? 'right' : 'left' }}: 0;
  width: 0%;
  height: 3px;
  background: var(--brand);
  z-index: 9999;
  transition: width .1s linear;
  border-radius: 0 2px 2px 0;
}

/* ── Post hero ─────────────────────────────────────────────────────── */
.post-hero {
  position: relative;
  overflow: hidden;
  min-height: 380px;
  display: flex;
  align-items: flex-end;
}
.post-hero-bg {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
.post-hero-bg::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.72) 0%, rgba(0,0,0,.25) 60%, transparent 100%);
}
.post-hero-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, var(--brand-soft) 0%, transparent 60%);
}
.post-hero-gradient::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse at 70% 40%, var(--brand-light) 0%, transparent 55%);
}
.post-hero-content {
  position: relative;
  z-index: 1;
  padding: 5rem 0 2.5rem;
  width: 100%;
}
.post-hero-content.has-image {
  color: #fff;
}
.post-hero-content.has-image .post-hero-cat,
.post-hero-content.has-image .post-hero-meta-item {
  opacity: .85;
}

.post-hero-cat {
  display: inline-block;
  background: var(--brand);
  color: #fff;
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .05em;
  padding: .3rem .85rem;
  border-radius: 999px;
  margin-bottom: 1rem;
  text-transform: uppercase;
}
.post-hero-title {
  font-size: clamp(1.6rem, 4vw, 2.4rem);
  font-weight: 700;
  line-height: 1.35;
  margin: 0 0 1.25rem;
  max-width: 780px;
}
.post-hero-meta {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 1.25rem;
}
.post-hero-meta-item {
  display: flex;
  align-items: center;
  gap: .4rem;
  font-size: .85rem;
}
.post-hero-meta-item svg {
  width: 15px;
  height: 15px;
  flex-shrink: 0;
  opacity: .7;
}
.post-author-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--brand-light);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .8rem;
  font-weight: 700;
  color: var(--brand);
  flex-shrink: 0;
}
.post-hero-content.has-image .post-author-avatar {
  background: rgba(255,255,255,.25);
  color: #fff;
}
.back-link {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  color: var(--brand);
  font-size: .88rem;
  font-weight: 500;
  text-decoration: none;
  margin-bottom: 1.25rem;
  transition: opacity .2s;
}
.back-link:hover { opacity: .75; }
.back-link svg { width: 15px; height: 15px; }
.post-hero-content.has-image .back-link { color: rgba(255,255,255,.85); }

/* ── Post body ─────────────────────────────────────────────────────── */
.post-layout {
  padding: 3rem 0 5rem;
}
.post-article {
  max-width: 760px;
  margin: 0 auto;
}

/* Prose styles for post body */
.post-body {
  font-size: 1.05rem;
  line-height: 1.85;
  color: var(--text, #1f2937);
}
.post-body h1, .post-body h2, .post-body h3, .post-body h4 {
  font-weight: 700;
  line-height: 1.35;
  margin: 2rem 0 .75rem;
  color: var(--text, #111827);
}
.post-body h2 { font-size: 1.45rem; }
.post-body h3 { font-size: 1.2rem; }
.post-body h4 { font-size: 1rem; }
.post-body p { margin: 0 0 1.25rem; }
.post-body a { color: var(--brand); text-decoration: underline; text-underline-offset: 3px; }
.post-body a:hover { opacity: .8; }
.post-body ul, .post-body ol {
  margin: 0 0 1.25rem;
  {{ $lang === 'ar' ? 'padding-right: 1.5rem; padding-left: 0;' : 'padding-left: 1.5rem; padding-right: 0;' }}
}
.post-body li { margin-bottom: .4rem; }
.post-body blockquote {
  margin: 1.5rem 0;
  padding: 1rem 1.5rem;
  border-{{ $lang === 'ar' ? 'right' : 'left' }}: 4px solid var(--brand);
  background: var(--brand-soft);
  border-radius: 0 .75rem .75rem 0;
  font-style: italic;
  color: var(--text-muted, #555);
}
.post-body img {
  width: 100%;
  border-radius: .85rem;
  margin: 1.5rem 0;
  display: block;
}
.post-body pre {
  background: var(--card-bg, #f8f9fa);
  border: 1px solid var(--border, #e5e7eb);
  border-radius: .75rem;
  padding: 1.25rem 1.5rem;
  overflow-x: auto;
  font-size: .88rem;
  margin: 1.5rem 0;
}
.post-body code {
  background: var(--brand-soft);
  color: var(--brand);
  padding: .15em .4em;
  border-radius: .3em;
  font-size: .88em;
}
.post-body pre code { background: none; color: inherit; padding: 0; }
.post-body table {
  width: 100%;
  border-collapse: collapse;
  margin: 1.5rem 0;
  font-size: .9rem;
}
.post-body th, .post-body td {
  padding: .65rem 1rem;
  border: 1px solid var(--border, #e5e7eb);
  text-align: {{ $lang === 'ar' ? 'right' : 'left' }};
}
.post-body th { background: var(--brand-soft); font-weight: 600; }
.post-body hr { border: none; border-top: 1px solid var(--border, #e5e7eb); margin: 2rem 0; }

/* Post meta bar */
.post-meta-bar {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  flex-wrap: wrap;
  padding: 1rem 1.25rem;
  background: var(--card-bg, #fff);
  border: 1px solid var(--border, #f0f0f0);
  border-radius: 1rem;
  margin-bottom: 2.5rem;
  font-size: .85rem;
  color: var(--text-muted, #666);
}
.post-meta-bar-item {
  display: flex;
  align-items: center;
  gap: .4rem;
}
.post-meta-bar-item svg { width: 14px; height: 14px; opacity: .6; }

/* Share strip */
.post-share {
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid var(--border, #e5e7eb);
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}
.post-share-label {
  font-size: .88rem;
  font-weight: 600;
  color: var(--text-muted, #666);
}
.post-share-btn {
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  padding: .45rem 1rem;
  border-radius: .6rem;
  font-size: .82rem;
  font-weight: 600;
  text-decoration: none;
  transition: opacity .2s;
  color: #fff;
}
.post-share-btn:hover { opacity: .85; }
.post-share-btn.twitter  { background: #000; }
.post-share-btn.facebook { background: #1877f2; }
.post-share-btn.linkedin { background: #0a66c2; }
.post-share-btn.copy     { background: var(--brand); cursor: pointer; border: none; font-family: inherit; }
.post-share-btn svg { width: 14px; height: 14px; }

/* ── Related posts ─────────────────────────────────────────────────── */
.related-section {
  padding: 3.5rem 0 5rem;
  background: var(--soft, #f9fafb);
  border-top: 1px solid var(--border, #f0f0f0);
}
.related-section .section-head {
  margin-bottom: 2rem;
}
.related-section h2 {
  font-size: 1.4rem;
  font-weight: 700;
  margin: 0 0 .35rem;
  color: var(--text, #111);
}
.related-section h2 .accent { color: var(--brand); }
.related-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}
@media (max-width: 900px) {
  .related-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 560px) {
  .related-grid { grid-template-columns: 1fr; }
}

/* Re-use post card styles from blog listing */
.post-card {
  background: var(--card-bg, #fff);
  border-radius: 1.25rem;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,.06);
  border: 1px solid var(--border, #f0f0f0);
  display: flex;
  flex-direction: column;
  transition: transform .22s, box-shadow .22s;
  text-decoration: none;
  color: inherit;
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
.post-card-img-placeholder svg { width: 40px; height: 40px; color: var(--brand); opacity: .4; }
.post-card-body { padding: 1.1rem 1.3rem 1.4rem; flex: 1; display: flex; flex-direction: column; }
.post-card-cat {
  display: inline-block;
  background: var(--brand-soft);
  color: var(--brand);
  font-size: .7rem;
  font-weight: 700;
  letter-spacing: .04em;
  padding: .22rem .65rem;
  border-radius: 999px;
  margin-bottom: .75rem;
  text-transform: uppercase;
}
.post-card-title {
  font-size: 1rem;
  font-weight: 700;
  line-height: 1.45;
  color: var(--text, #1a1a1a);
  margin: 0 0 .5rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.post-card-date {
  font-size: .78rem;
  color: var(--text-muted, #999);
  margin-top: auto;
  padding-top: .85rem;
}
</style>
</head>
<body class="{{ (!empty($settings['dark_mode']) && $settings['dark_mode'] === 'dark') ? 'dark' : '' }}">

<!-- Reading progress bar -->
<div id="reading-progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>

<!-- NAV -->
<header class="nav">
  <div class="wrap nav-inner">
    <div class="brand">
      @if(!empty($settings['logo_url']))
        @if(!empty($settings['dark_logo_url']))
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-light" />
          <img src="{{ $settings['dark_logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-dark" />
        @else
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" />
        @endif
      @else
      <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" />
      @endif
      <span>{{ $settings['site_name'] ?? 'Retont Business' }}</span>
    </div>
    <nav class="nav-links">
      <a href="{{ url('/') }}">{{ $lang === 'ar' ? 'الرئيسية' : 'Home' }}</a>
      <a href="{{ url('/') }}#features">{{ $lang === 'ar' ? 'المميزات' : 'Features' }}</a>
      <a href="{{ url('/') }}#pricing">{{ $lang === 'ar' ? 'الأسعار' : 'Pricing' }}</a>
      <a href="{{ route('blog.index') }}" style="color:var(--brand)">{{ $lang === 'ar' ? 'المدونة' : 'Blog' }}</a>
      <a href="{{ url('/') }}#contact">{{ $lang === 'ar' ? 'تواصل معنا' : 'Contact' }}</a>
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

<!-- POST HERO -->
<section class="post-hero">
  @if(!empty($post->featured_image))
    <div class="post-hero-bg" style="background-image:url('{{ $post->featured_image }}')"></div>
  @else
    <div class="post-hero-gradient"></div>
  @endif

  <div class="wrap post-hero-content {{ !empty($post->featured_image) ? 'has-image' : '' }}">
    <a href="{{ route('blog.index') }}" class="back-link">
      @if($lang === 'ar')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
        العودة للمدونة
      @else
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
        Back to Blog
      @endif
    </a>

    @if($post->category)
      <div>
        <span class="post-hero-cat">
          {{ $post->category->getName($lang) }}
        </span>
      </div>
    @endif

    <h1 class="post-hero-title">{{ $post->getTitle($lang) }}</h1>

    <div class="post-hero-meta">
      {{-- Author --}}
      <div class="post-hero-meta-item">
        <div class="post-author-avatar">{{ mb_substr($post->author->name ?? '؟', 0, 1) }}</div>
        <span>{{ $post->author->name ?? ($lang === 'ar' ? 'الفريق' : 'Team') }}</span>
      </div>

      {{-- Date --}}
      @if($post->published_at)
      <div class="post-hero-meta-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>{{ $post->published_at->format('d M Y') }}</span>
      </div>
      @endif

      {{-- Views --}}
      @if($post->views_count > 0)
      <div class="post-hero-meta-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        <span>{{ number_format($post->views_count) }} {{ $lang === 'ar' ? 'مشاهدة' : 'views' }}</span>
      </div>
      @endif
    </div>
  </div>
</section>

<!-- POST CONTENT -->
<div class="post-layout">
  <div class="wrap">
    <article class="post-article" id="post-article">

      {{-- Meta bar (compact, for no-image posts) --}}
      @if(empty($post->featured_image))
      <div class="post-meta-bar">
        <div class="post-meta-bar-item">
          <div class="post-author-avatar" style="width:28px;height:28px;font-size:.75rem">{{ mb_substr($post->author->name ?? '؟', 0, 1) }}</div>
          <span>{{ $post->author->name ?? ($lang === 'ar' ? 'الفريق' : 'Team') }}</span>
        </div>
        @if($post->published_at)
        <div class="post-meta-bar-item">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <span>{{ $post->published_at->format('d M Y') }}</span>
        </div>
        @endif
        @if($post->views_count > 0)
        <div class="post-meta-bar-item">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          <span>{{ number_format($post->views_count) }} {{ $lang === 'ar' ? 'مشاهدة' : 'views' }}</span>
        </div>
        @endif
        @if($post->category)
        <div class="post-meta-bar-item">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
          <span>{{ $post->category->getName($lang) }}</span>
        </div>
        @endif
      </div>
      @endif

      {{-- Post body --}}
      <div class="post-body">
        {!! $post->getBody($lang) !!}
      </div>

      {{-- Share strip --}}
      @php
        $shareUrl = urlencode(request()->url());
        $shareTitle = urlencode($post->getTitle($lang));
      @endphp
      <div class="post-share">
        <span class="post-share-label">{{ $lang === 'ar' ? 'شارك المقال:' : 'Share:' }}</span>
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="post-share-btn twitter">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2H21.5l-7.41 8.466L23 22h-6.8l-5.32-6.95L4.8 22H1.54l7.93-9.06L1 2h6.96l4.81 6.36L18.244 2zm-1.19 18h1.88L7.05 4H5.04l12.014 16z"/></svg>
          X (Twitter)
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="post-share-btn facebook">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88V14.9H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.9h-2.33v6.98A10 10 0 0 0 22 12z"/></svg>
          Facebook
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}&title={{ $shareTitle }}" target="_blank" rel="noopener" class="post-share-btn linkedin">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          LinkedIn
        </a>
        <button class="post-share-btn copy" onclick="copyLink(this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
          {{ $lang === 'ar' ? 'نسخ الرابط' : 'Copy link' }}
        </button>
      </div>
    </article>
  </div>
</div>

<!-- RELATED POSTS -->
@if($related->isNotEmpty())
<section class="related-section">
  <div class="wrap">
    <div class="section-head">
      <h2>{{ $lang === 'ar' ? 'مقالات' : 'Related ' }}<span class="accent">{{ $lang === 'ar' ? ' ذات صلة' : 'Articles' }}</span></h2>
    </div>
    <div class="related-grid">
      @foreach($related as $relPost)
      <a href="{{ route('blog.show', $relPost->slug) }}" class="post-card">
        @if(!empty($relPost->featured_image))
          <img src="{{ $relPost->featured_image }}" alt="{{ $relPost->getTitle($lang) }}" class="post-card-img" loading="lazy" />
        @else
          <div class="post-card-img-placeholder">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        @endif
        <div class="post-card-body">
          @if($relPost->category)
            <span class="post-card-cat">{{ $relPost->category->getName($lang) }}</span>
          @endif
          <h3 class="post-card-title">{{ $relPost->getTitle($lang) }}</h3>
          @if($relPost->published_at)
            <div class="post-card-date">{{ $relPost->published_at->format('d M Y') }}</div>
          @endif
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- FOOTER -->
<footer>
  <div class="wrap foot">
    <div class="brand">
      @if(!empty($settings['logo_url']))
        @if(!empty($settings['dark_logo_url']))
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-light" style="width:28px;height:28px;object-fit:contain" />
          <img src="{{ $settings['dark_logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-dark" style="width:28px;height:28px;object-fit:contain" />
        @else
          <img src="{{ $settings['logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" style="width:28px;height:28px;object-fit:contain" />
        @endif
      @else
      <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" style="width:28px;height:28px;object-fit:contain" />
      @endif
      <span style="font-size:16px">{{ $settings['site_name'] ?? 'Retont Business' }}</span>
    </div>
    <div>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'Retont Business' }}. {{ $lang === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</div>
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
// Reading progress bar
(function () {
  var bar = document.getElementById('reading-progress');
  var article = document.getElementById('post-article');
  if (!bar || !article) return;
  function updateProgress() {
    var articleTop    = article.getBoundingClientRect().top + window.scrollY;
    var articleBottom = articleTop + article.offsetHeight;
    var scrolled      = window.scrollY + window.innerHeight;
    var total         = articleBottom - articleTop;
    var progress      = Math.min(100, Math.max(0, ((window.scrollY - articleTop + window.innerHeight * 0.1) / total) * 100));
    bar.style.width = progress + '%';
    bar.setAttribute('aria-valuenow', Math.round(progress));
  }
  window.addEventListener('scroll', updateProgress, { passive: true });
  updateProgress();
})();

// Copy link
function copyLink(btn) {
  navigator.clipboard.writeText(window.location.href).then(function () {
    var orig = btn.innerHTML;
    btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M5 13l4 4L19 7"/></svg> {{ $lang === "ar" ? "تم النسخ!" : "Copied!" }}';
    setTimeout(function () { btn.innerHTML = orig; }, 2000);
  });
}
</script>
</body>
</html>
