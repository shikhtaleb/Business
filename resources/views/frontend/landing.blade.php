<!doctype html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $seo['title'] ?? ($settings['site_name'] ?? 'Retont Business') }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}" />
@if(!empty($seo['keywords']))
<meta name="keywords" content="{{ $seo['keywords'] }}" />
@endif
@if(!empty($seo['robots']))
<meta name="robots" content="{{ $seo['robots'] }}" />
@endif
<!-- Open Graph -->
<meta property="og:title" content="{{ $seo['og_title'] ?? ($seo['title'] ?? '') }}" />
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
<style>
:root {
  --brand:         {{ $settings['brand_color'] ?? '#FF8528' }};
  --brand-light:   color-mix(in srgb, {{ $settings['brand_color'] ?? '#FF8528' }} 15%, transparent);
  --brand-soft:    color-mix(in srgb, {{ $settings['brand_color'] ?? '#FF8528' }} 8%, transparent);
  --brand-hover:   color-mix(in srgb, {{ $settings['brand_color'] ?? '#FF8528' }} 85%, #000);
}
</style>
@if(!empty($settings['ga_id']))
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['ga_id'] }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ $settings['ga_id'] }}');
</script>
@endif
</head>
<body class="{{ (!empty($settings['dark_mode']) && $settings['dark_mode'] === 'dark') ? 'dark' : '' }}">

<!-- NAV -->
<header class="nav">
  <div class="wrap nav-inner">
    <div class="brand">
      @if(!empty($settings['logo_url']) || !empty($settings['logo_path']))
        @php $logoUrl = $settings['logo_url'] ?? asset($settings['logo_path']); @endphp
        @if(!empty($settings['dark_logo_url']))
          <img src="{{ $logoUrl }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-light" />
          <img src="{{ $settings['dark_logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-dark" />
        @else
          <img src="{{ $logoUrl }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" />
        @endif
      @else
      <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" />
      @endif
      <span>{{ $settings['site_name'] ?? 'Retont Business' }}</span>
    </div>
    <nav class="nav-links">
      <a href="#home" data-i18n="nav.home">{{ $content['nav']['nav.home'] ?? 'الرئيسية' }}</a>
      <a href="#features" data-i18n="nav.features">{{ $content['nav']['nav.features'] ?? 'المميزات' }}</a>
      <a href="#pricing" data-i18n="nav.pricing">{{ $content['nav']['nav.pricing'] ?? 'الأسعار' }}</a>
      <a href="#faq" data-i18n="nav.faq">{{ $content['nav']['nav.faq'] ?? 'الأسئلة الشائعة' }}</a>
      <a href="#contact" data-i18n="nav.contact">{{ $content['nav']['nav.contact'] ?? 'تواصل معنا' }}</a>
    </nav>
    <div class="nav-cta">
      <button class="nav-toggle" id="navToggle" aria-label="menu" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <button class="theme-toggle" id="themeToggle" aria-label="theme" aria-pressed="false">
        <svg class="moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
        <svg class="sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
      </button>
      <a href="#" class="btn btn-ghost" data-i18n="nav.login">{{ $content['nav']['nav.login'] ?? 'تسجيل الدخول' }}</a>
      <a href="#" class="btn btn-primary" data-i18n="nav.cta">{{ $content['nav']['nav.cta'] ?? 'ابدأ الآن' }}</a>
    </div>
  </div>
</header>

<!-- HERO -->
<section class="hero" id="home">
  <svg class="squiggle s1" viewBox="0 0 90 30" fill="none"><path d="M2 15 Q 10 2, 18 15 T 34 15 T 50 15 T 66 15 T 88 15" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
  <svg class="squiggle s2" viewBox="0 0 70 26" fill="none"><path d="M2 13 Q 9 2, 16 13 T 30 13 T 44 13 T 68 13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
  <span class="dot d1"></span>
  <span class="dot d2"></span>
  <div class="wrap">
    <h1 data-i18n-html="hero.title">{!! $content['hero']['hero.title'] ?? 'منصة <span class="accent">Retont Business</span><br/>لإدارة مشاريعك وفريقك بسلاسة' !!}</h1>
    <p class="sub" data-i18n="hero.sub">{{ $content['hero']['hero.sub'] ?? 'أدر المشاريع والمهام والعملاء والفواتير من لوحة واحدة.' }}</p>
    <div class="cta-row">
      <a href="#" class="btn btn-primary" data-i18n="hero.cta1">{{ $content['hero']['hero.cta1'] ?? 'ابدأ تجربة ٣٠ يومًا مجانًا' }}</a>
      <a href="#" class="btn btn-outline" data-i18n="hero.cta2">{{ $content['hero']['hero.cta2'] ?? 'شاهد العرض التوضيحي' }}</a>
    </div>

    <!-- Dashboard mock -->
    <div class="dash-wrap">
      <div class="dash">
        <div class="dash-inner">
          <aside class="ds-side">
            <div class="item active"><span class="ico"></span><span data-i18n="ds.dashboard">{{ $content['hero']['ds.dashboard'] ?? 'لوحة التحكم' }}</span></div>
            <div class="group" data-i18n="ds.manage">{{ $content['hero']['ds.manage'] ?? 'إدارة العمل' }}</div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.projects">{{ $content['hero']['ds.projects'] ?? 'المشاريع' }}</span></div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.tasks">{{ $content['hero']['ds.tasks'] ?? 'المهام' }}</span></div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.clients">{{ $content['hero']['ds.clients'] ?? 'العملاء' }}</span></div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.invoices">{{ $content['hero']['ds.invoices'] ?? 'الفواتير' }}</span></div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.payments">{{ $content['hero']['ds.payments'] ?? 'المدفوعات' }}</span></div>
            <div class="group" data-i18n="ds.general">{{ $content['hero']['ds.general'] ?? 'عام' }}</div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.staff">{{ $content['hero']['ds.staff'] ?? 'الموظفون' }}</span></div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.attendance">{{ $content['hero']['ds.attendance'] ?? 'الحضور والانصراف' }}</span></div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.reports">{{ $content['hero']['ds.reports'] ?? 'التقارير' }}</span></div>
            <div class="item"><span class="ico"></span><span data-i18n="ds.settings">{{ $content['hero']['ds.settings'] ?? 'الإعدادات' }}</span></div>
          </aside>
          <main class="ds-main">
            <div class="ds-top">
              <div class="ds-tabs">
                <span class="tab on" data-i18n="ds.tabToday">{{ $content['hero']['ds.tabToday'] ?? 'مهام اليوم' }}</span>
                <span class="tab" data-i18n="ds.tabRes">{{ $content['hero']['ds.tabRes'] ?? 'مشاريع نشطة' }}</span>
                <span class="tab" data-i18n="ds.tabInv">{{ $content['hero']['ds.tabInv'] ?? 'ساعات العمل' }}</span>
              </div>
              <div class="ds-search" data-i18n="ds.search">{{ $content['hero']['ds.search'] ?? '🔍 بحث سريع…' }}</div>
            </div>
            <div class="ds-grid">
              <div style="display:flex;flex-direction:column;gap:10px">
                <div class="ds-stats">
                  <div class="ds-card">
                    <h4 data-i18n="ds.totalOrders">{{ $content['hero']['ds.totalOrders'] ?? 'المشاريع النشطة' }}</h4>
                    <div class="big">24</div>
                    <div class="delta" data-i18n="ds.delta1">{{ $content['hero']['ds.delta1'] ?? '▲ ٣ مشاريع جديدة هذا الأسبوع' }}</div>
                  </div>
                  <div class="ds-card">
                    <h4 data-i18n="ds.avgInv">{{ $content['hero']['ds.avgInv'] ?? 'المهام المنجزة' }}</h4>
                    <div class="big">186</div>
                    <div class="delta" data-i18n="ds.delta2">{{ $content['hero']['ds.delta2'] ?? '▲ ١٢٪ عن الشهر الماضي' }}</div>
                  </div>
                </div>
                <div class="ds-card">
                  <h4 data-i18n="ds.weeklySales">{{ $content['hero']['ds.weeklySales'] ?? 'الإيرادات الأسبوعية' }}</h4>
                  <div class="big" style="font-size:22px"><span data-i18n="ds.sales">{{ $content['hero']['ds.sales'] ?? '٤٢٬٧٥٠ ر.س' }}</span></div>
                  <div class="ds-chart" style="margin-top:10px">
                    <svg viewBox="0 0 400 160" preserveAspectRatio="none">
                      <defs>
                        <linearGradient id="g1" x1="0" x2="0" y1="0" y2="1">
                          <stop offset="0%" stop-color="#FF8528" stop-opacity=".6"/>
                          <stop offset="100%" stop-color="#FF8528" stop-opacity="0"/>
                        </linearGradient>
                      </defs>
                      <path d="M0,130 C40,120 70,90 100,95 C140,100 160,60 200,55 C240,50 270,80 300,70 C340,55 370,30 400,40 L400,160 L0,160 Z" fill="url(#g1)"/>
                      <path d="M0,130 C40,120 70,90 100,95 C140,100 160,60 200,55 C240,50 270,80 300,70 C340,55 370,30 400,40" fill="none" stroke="#FF8528" stroke-width="2.5"/>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="ds-card ds-orders">
                <h4 style="margin-bottom:10px" data-i18n="ds.todayOrders">{{ $content['hero']['ds.todayOrders'] ?? 'مهام اليوم' }}</h4>
                <div class="order"><span class="av"></span><div class="name"><span data-i18n="ds.order103">{{ $content['hero']['ds.order103'] ?? 'مشروع موقع شركة سواحل' }}</span><span class="meta"></span></div><span class="badge" data-i18n="ds.paid">{{ $content['hero']['ds.paid'] ?? 'مكتمل' }}</span></div>
                <div class="order"><span class="av"></span><div class="name"><span data-i18n="ds.order102">{{ $content['hero']['ds.order102'] ?? 'مشروع تطبيق نقل' }}</span><span class="meta"></span></div><span class="badge warn" data-i18n="ds.preparing">{{ $content['hero']['ds.preparing'] ?? 'قيد التنفيذ' }}</span></div>
                <div class="order"><span class="av"></span><div class="name"><span data-i18n="ds.order101">{{ $content['hero']['ds.order101'] ?? 'متجر بسمة الإلكتروني' }}</span><span class="meta"></span></div><span class="badge" data-i18n="ds.paid">{{ $content['hero']['ds.paid'] ?? 'مكتمل' }}</span></div>
                <div class="order"><span class="av"></span><div class="name"><span data-i18n="ds.order100">{{ $content['hero']['ds.order100'] ?? 'فريق التطوير الداخلي' }}</span><span class="meta"></span></div><span class="badge warn" data-i18n="ds.preparing">{{ $content['hero']['ds.preparing'] ?? 'قيد التنفيذ' }}</span></div>
                <div class="order"><span class="av"></span><div class="name"><span data-i18n="ds.order099">{{ $content['hero']['ds.order099'] ?? 'مؤسسة الأفق التجارية' }}</span><span class="meta"></span></div><span class="badge" data-i18n="ds.paid">{{ $content['hero']['ds.paid'] ?? 'مكتمل' }}</span></div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SPOTLIGHT ROWS -->
<section class="section" id="features">
  <div class="wrap">
    <h2 data-i18n-html="sec.controlTitle">{!! $content['spotlight1']['sec.controlTitle'] ?? 'تحكّم في إدارة <span class="accent">أعمالك</span>' !!}</h2>
    <p class="lead" data-i18n="sec.controlLead">{{ $content['spotlight1']['sec.controlLead'] ?? 'ثلاث وحدات أساسية في منصة واحدة لإدارة عملك اليومي.' }}</p>

    <!-- 1. Invoice / Billing -->
    <div class="spotlight">
      <div class="mock inv">
        <div class="head">
          <div class="who"><span data-i18n="mock.customer1">{{ $content['spotlight1']['mock.customer1'] ?? 'مؤسسة الأفق التجارية' }}</span><span class="date" data-i18n="mock.date1">{{ $content['spotlight1']['mock.date1'] ?? 'فاتورة #INV‑2026‑047 · ١٥ مايو ٢٠٢٦' }}</span></div>
          <span class="tag" data-i18n="mock.paid">{{ $content['spotlight1']['mock.paid'] ?? 'مدفوعة' }}</span>
        </div>
        <table>
          <thead><tr><th data-i18n="mock.item">{{ $content['spotlight1']['mock.item'] ?? 'البند' }}</th><th data-i18n="mock.qty">{{ $content['spotlight1']['mock.qty'] ?? 'الساعات' }}</th><th data-i18n="mock.price">{{ $content['spotlight1']['mock.price'] ?? 'السعر/س' }}</th><th data-i18n="mock.sum">{{ $content['spotlight1']['mock.sum'] ?? 'المجموع' }}</th></tr></thead>
          <tbody>
            <tr><td data-i18n="mock.burger">{{ $content['spotlight1']['mock.burger'] ?? 'تصميم واجهات المستخدم' }}</td><td data-i18n="mock.n2">{{ $content['spotlight1']['mock.n2'] ?? '٣٢' }}</td><td>150</td><td>4,800</td></tr>
            <tr><td data-i18n="mock.juice">{{ $content['spotlight1']['mock.juice'] ?? 'تطوير الواجهة الأمامية' }}</td><td data-i18n="mock.n1">{{ $content['spotlight1']['mock.n1'] ?? '٢٤' }}</td><td>180</td><td>4,320</td></tr>
            <tr><td data-i18n="mock.salad">{{ $content['spotlight1']['mock.salad'] ?? 'إدارة المشروع والمتابعة' }}</td><td>١٢</td><td>120</td><td>1,440</td></tr>
          </tbody>
        </table>
        <div class="total"><span data-i18n="mock.total">{{ $content['spotlight1']['mock.total'] ?? 'الإجمالي (شامل الضريبة)' }}</span><span data-i18n="mock.totalVal">{{ $content['spotlight1']['mock.totalVal'] ?? '١٢٬١٧٢ ر.س' }}</span></div>
        <div class="float f1">
          <span class="a"></span>
          <div class="n"><span data-i18n="mock.order047">{{ $content['spotlight1']['mock.order047'] ?? 'مشروع #٠٤٧' }}</span><span class="sm" data-i18n="mock.cust2">{{ $content['spotlight1']['mock.cust2'] ?? 'شركة نسائم للتقنية' }}</span></div>
          <span class="badge" data-i18n="mock.new">{{ $content['spotlight1']['mock.new'] ?? 'قيد التنفيذ' }}</span>
        </div>
        <div class="float f2">
          <span class="a"></span>
          <div class="n"><span data-i18n="mock.amount">{{ $content['spotlight1']['mock.amount'] ?? '٧٬٤٥٠ ر.س' }}</span><span class="sm" data-i18n="mock.payOk">{{ $content['spotlight1']['mock.payOk'] ?? 'دفعة جديدة مستلمة' }}</span></div>
        </div>
      </div>
      <div class="sp-text">
        <h3 data-i18n="sp.orders.h">{{ $content['spotlight1']['sp.orders.h'] ?? 'من المهمة إلى الفاتورة دون جهد' }}</h3>
        <p data-i18n="sp.orders.p">{{ $content['spotlight1']['sp.orders.p'] ?? 'حوّل ساعات العمل المسجّلة إلى فواتير دقيقة بضغطة زر.' }}</p>
        <ul>
          <li data-i18n="sp.orders.l1">{{ $content['spotlight1']['sp.orders.l1'] ?? 'فواتير احترافية بعملات متعدّدة مع توافق ضريبي كامل' }}</li>
          <li data-i18n="sp.orders.l2">{{ $content['spotlight1']['sp.orders.l2'] ?? 'تذكيرات دفع آلية عبر البريد الإلكتروني والرسائل الفورية' }}</li>
          <li data-i18n="sp.orders.l3">{{ $content['spotlight1']['sp.orders.l3'] ?? 'ربط مباشر بأنظمة الدفع الإلكترونية الرائدة' }}</li>
        </ul>
      </div>
    </div>

    <!-- 2. Tasks / Projects board -->
    <div class="spotlight">
      <div class="sp-text">
        <h3 data-i18n="sp.res.h">{{ $content['spotlight2']['sp.res.h'] ?? 'لوحة بصرية لإدارة المهام' }}</h3>
        <p data-i18n="sp.res.p">{{ $content['spotlight2']['sp.res.p'] ?? 'نظّم مهام الفريق بطريقة بصرية مرنة: من المسودّة إلى الإنجاز.' }}</p>
        <ul>
          <li data-i18n="sp.res.l1">{{ $content['spotlight2']['sp.res.l1'] ?? 'عروض متعددة — البطاقات والمخطط الزمني والقائمة — بنفس البيانات' }}</li>
          <li data-i18n="sp.res.l2">{{ $content['spotlight2']['sp.res.l2'] ?? 'إسناد المهام مع مواعيد نهائية وأولويات' }}</li>
          <li data-i18n="sp.res.l3">{{ $content['spotlight2']['sp.res.l3'] ?? 'تتبّع ساعات العمل لكل مهمة وموظف' }}</li>
        </ul>
      </div>
      <div class="mock res">
        <div class="res-title" data-i18n="res.title">{{ $content['spotlight2']['res.title'] ?? 'لوحة المشاريع' }}</div>
        <div class="row">
          <div class="pill"><div><span class="lab" data-i18n="res.from">{{ $content['spotlight2']['res.from'] ?? 'الفترة' }}</span><span class="v" data-i18n="res.dateA">{{ $content['spotlight2']['res.dateA'] ?? '١٥ – ٢٢ مايو' }}</span></div></div>
          <div class="pill"><div><span class="lab" data-i18n="res.to">{{ $content['spotlight2']['res.to'] ?? 'المشروع' }}</span><span class="v" data-i18n="res.dateB">{{ $content['spotlight2']['res.dateB'] ?? 'موقع سواحل' }}</span></div></div>
        </div>
        <div class="row">
          <div class="pill" style="background:var(--soft)">
            <div style="flex:1"><span class="lab" data-i18n="res.t4">{{ $content['spotlight2']['res.t4'] ?? 'تصميم الصفحة الرئيسية' }}</span><span class="v" data-i18n="res.fam">{{ $content['spotlight2']['res.fam'] ?? 'سارة المطيري · أولوية عالية' }}</span></div>
            <span class="chip ok" data-i18n="res.confirmed">{{ $content['spotlight2']['res.confirmed'] ?? 'مكتملة' }}</span>
          </div>
        </div>
        <div class="row">
          <div class="pill">
            <div style="flex:1"><span class="lab" data-i18n="res.t7">{{ $content['spotlight2']['res.t7'] ?? 'برمجة واجهة المستخدم' }}</span><span class="v" data-i18n="res.phone">{{ $content['spotlight2']['res.phone'] ?? 'يستحقّ ٢٠ مايو · ١٢ ساعة' }}</span></div>
            <span class="chip" data-i18n="res.pending">{{ $content['spotlight2']['res.pending'] ?? 'قيد التنفيذ' }}</span>
          </div>
        </div>
        <div class="row">
          <div class="pill">
            <div style="flex:1"><span class="lab" data-i18n="res.t2">{{ $content['spotlight2']['res.t2'] ?? 'إعداد قاعدة البيانات' }}</span><span class="v" data-i18n="res.cust3">{{ $content['spotlight2']['res.cust3'] ?? 'فيصل النمر · أولوية متوسطة' }}</span></div>
            <span class="chip ok" data-i18n="res.arrived">{{ $content['spotlight2']['res.arrived'] ?? 'قيد المراجعة' }}</span>
          </div>
        </div>
        <div class="row">
          <div class="pill">
            <div style="flex:1"><span class="lab" data-i18n="res.t9">{{ $content['spotlight2']['res.t9'] ?? 'اختبار الجودة' }}</span><span class="v" data-i18n="res.cust4">{{ $content['spotlight2']['res.cust4'] ?? 'نورة الزهراني · ٤ مهام فرعية' }}</span></div>
            <span class="chip" data-i18n="res.pending">{{ $content['spotlight2']['res.pending'] ?? 'قيد التنفيذ' }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. CRM / Clients -->
    <div class="spotlight">
      <div class="mock menu">
        <div class="tabs">
          <span class="t on"><span data-i18n="menu.tab1">{{ $content['spotlight3']['menu.tab1'] ?? 'عملاء نشطون' }}</span><span class="ct">٤</span></span>
          <span class="t"><span data-i18n="menu.tab2">{{ $content['spotlight3']['menu.tab2'] ?? 'عملاء محتملون' }}</span><span class="ct">٦</span></span>
          <span class="t"><span data-i18n="menu.tab3">{{ $content['spotlight3']['menu.tab3'] ?? 'مؤرشف' }}</span><span class="ct">٢</span></span>
        </div>
        <div class="menu-head">
          <span class="title" data-i18n="menu.tab1">{{ $content['spotlight3']['menu.tab1'] ?? 'عملاء نشطون' }}</span>
          <span class="update" data-i18n="menu.update">{{ $content['spotlight3']['menu.update'] ?? 'إضافة عميل' }}</span>
        </div>
        <div class="item-row">
          <div class="thumb"></div>
          <div><div class="n" data-i18n="menu.i1n">{{ $content['spotlight3']['menu.i1n'] ?? 'مؤسسة الأفق التجارية' }}</div><div class="d" data-i18n="menu.i1d">{{ $content['spotlight3']['menu.i1d'] ?? '٣ مشاريع نشطة · آخر تواصل قبل يومين' }}</div></div>
          <div class="price"><span data-i18n="menu.p42">{{ $content['spotlight3']['menu.p42'] ?? '٤٢٬٠٠٠ ر.س' }}</span></div>
        </div>
        <div class="item-row">
          <div class="thumb b"></div>
          <div><div class="n" data-i18n="menu.i2n">{{ $content['spotlight3']['menu.i2n'] ?? 'شركة نسائم للتقنية' }}</div><div class="d" data-i18n="menu.i2d">{{ $content['spotlight3']['menu.i2d'] ?? 'مشروعان نشطان · فاتورة معلّقة' }}</div></div>
          <div class="price"><span data-i18n="menu.p38">{{ $content['spotlight3']['menu.p38'] ?? '٣٨٬٥٠٠ ر.س' }}</span></div>
        </div>
        <div class="item-row">
          <div class="thumb c"></div>
          <div><div class="n" data-i18n="menu.i3n">{{ $content['spotlight3']['menu.i3n'] ?? 'متجر بسمة الإلكتروني' }}</div><div class="d" data-i18n="menu.i3d">{{ $content['spotlight3']['menu.i3d'] ?? 'مشروع واحد · في مرحلة الاختبار' }}</div></div>
          <div class="price"><span data-i18n="menu.p28">{{ $content['spotlight3']['menu.p28'] ?? '٢٨٬٢٠٠ ر.س' }}</span></div>
        </div>
        <div class="item-row">
          <div class="thumb d"></div>
          <div><div class="n" data-i18n="menu.i4n">{{ $content['spotlight3']['menu.i4n'] ?? 'مجموعة وجهة الرياض' }}</div><div class="d" data-i18n="menu.i4d">{{ $content['spotlight3']['menu.i4d'] ?? '٤ مشاريع · عميل مميز منذ ٢٠٢٣' }}</div></div>
          <div class="price"><span data-i18n="menu.p45">{{ $content['spotlight3']['menu.p45'] ?? '٤٥٬٩٠٠ ر.س' }}</span></div>
        </div>
      </div>
      <div class="sp-text">
        <h3 data-i18n="sp.menu.h">{{ $content['spotlight3']['sp.menu.h'] ?? 'نظام متكامل لإدارة علاقات العملاء' }}</h3>
        <p data-i18n="sp.menu.p">{{ $content['spotlight3']['sp.menu.p'] ?? 'احفظ جهات الاتصال، تتبّع الفرص البيعية من العرض حتى الإغلاق.' }}</p>
        <ul>
          <li data-i18n="sp.menu.l1">{{ $content['spotlight3']['sp.menu.l1'] ?? 'مسار مبيعات قابل للتخصيص' }}</li>
          <li data-i18n="sp.menu.l2">{{ $content['spotlight3']['sp.menu.l2'] ?? 'سجل كامل للعروض والعقود لكل عميل' }}</li>
          <li data-i18n="sp.menu.l3">{{ $content['spotlight3']['sp.menu.l3'] ?? 'بوابة عملاء آمنة لمشاركة المشاريع والفواتير' }}</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES GRID -->
<section class="section" style="padding-top:0">
  <div class="wrap">
    <h2 data-i18n-html="sec.allInOneTitle">{!! $content['features']['sec.allInOneTitle'] ?? 'كل ما تحتاجه <span class="accent">في مكان واحد</span>' !!}</h2>
    <p class="lead" data-i18n="sec.allInOneLead">{{ $content['features']['sec.allInOneLead'] ?? 'منظومة متكاملة لإدارة كل جوانب عملك — من المشاريع إلى الموظفين إلى تقارير الأداء.' }}</p>
    <div class="features">
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/></svg></div>
        <h4 data-i18n="ft.pos.h">{{ $content['features']['ft.pos.h'] ?? 'إدارة المشاريع' }}</h4>
        <p data-i18n="ft.pos.p">{{ $content['features']['ft.pos.p'] ?? 'خطّط ونفّذ وراقب مشاريعك بعروض البطاقات والمخطط الزمني والقائمة.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18M3 12h18M3 17h12"/></svg></div>
        <h4 data-i18n="ft.rep.h">{{ $content['features']['ft.rep.h'] ?? 'تقارير ذكية' }}</h4>
        <p data-i18n="ft.rep.p">{{ $content['features']['ft.rep.p'] ?? 'تقارير حيّة وفورية عن الإيرادات والإنتاجية والربحية لكل مشروع.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 3v6"/></svg></div>
        <h4 data-i18n="ft.inv.h">{{ $content['features']['ft.inv.h'] ?? 'الفواتير والعروض' }}</h4>
        <p data-i18n="ft.inv.p">{{ $content['features']['ft.inv.p'] ?? 'عروض أسعار وفواتير وعقود إلكترونية بتوافق ضريبي كامل.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div>
        <h4 data-i18n="ft.stock.h">{{ $content['features']['ft.stock.h'] ?? 'تتبّع ساعات العمل' }}</h4>
        <p data-i18n="ft.stock.p">{{ $content['features']['ft.stock.p'] ?? 'سجّل ساعات الفريق على كل مهمة، واحسب التكلفة والربحية تلقائيًا.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div>
        <h4 data-i18n="ft.staff.h">{{ $content['features']['ft.staff.h'] ?? 'الموارد البشرية والصلاحيات' }}</h4>
        <p data-i18n="ft.staff.p">{{ $content['features']['ft.staff.p'] ?? 'إدارة الفريق والإجازات والحضور والانصراف وصلاحيات كل دور وظيفي.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-9.5 8.5L3 21l1-7A8 8 0 1 1 21 11.5z"/></svg></div>
        <h4 data-i18n="ft.comm.h">{{ $content['features']['ft.comm.h'] ?? 'دردشة الفريق' }}</h4>
        <p data-i18n="ft.comm.p">{{ $content['features']['ft.comm.p'] ?? 'قنوات نقاش لكل مشروع وإشعارات فورية وتعليقات على المهام.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg></div>
        <h4 data-i18n="ft.pay.h">{{ $content['features']['ft.pay.h'] ?? 'المدفوعات الإلكترونية' }}</h4>
        <p data-i18n="ft.pay.p">{{ $content['features']['ft.pay.p'] ?? 'قبول البطاقات الائتمانية ومدى والتحويلات البنكية وعدد من بوابات الدفع الرائدة.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h10"/><circle cx="18" cy="17" r="2"/></svg></div>
        <h4 data-i18n="ft.sup.h">{{ $content['features']['ft.sup.h'] ?? 'تذاكر الدعم الفني' }}</h4>
        <p data-i18n="ft.sup.p">{{ $content['features']['ft.sup.p'] ?? 'نظام دعم فني مدمج لاستقبال طلبات العملاء والردّ عليها بسرعة.' }}</p>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section testimonials">
  <div class="wrap">
    <h2 data-i18n-html="sec.testTitle">{!! $content['testimonials']['sec.testTitle'] ?? 'ماذا يقول <span class="accent">عملاؤنا</span>' !!}</h2>
    <p class="lead" data-i18n="sec.testLead">{{ $content['testimonials']['sec.testLead'] ?? 'آلاف الفرق والشركات تعتمد على Retont Business لتشغيل أعمالها اليومية.' }}</p>
    <div class="quotes">
      <div class="q">
        <div class="stars">★★★★★</div>
        <div class="body" data-i18n="q1.body">{{ $content['testimonials']['q1.body'] ?? '"كنّا نستخدم خمسة أدوات مختلفة لإدارة المشاريع والفواتير. اليوم كل شيء داخل Retont Business."' }}</div>
        <div class="who">
          <div class="av"></div>
          <div><div class="name" data-i18n="q1.name">{{ $content['testimonials']['q1.name'] ?? 'خالد العمري' }}</div><div class="role" data-i18n="q1.role">{{ $content['testimonials']['q1.role'] ?? 'المدير التنفيذي · وكالة إثراء الرقمية' }}</div></div>
        </div>
      </div>
      <div class="q">
        <div class="stars">★★★★★</div>
        <div class="body" data-i18n="q2.body">{{ $content['testimonials']['q2.body'] ?? '"تتبّع ساعات العمل والربط بين المهام والفواتير غيّر طريقة عملنا."' }}</div>
        <div class="who">
          <div class="av"></div>
          <div><div class="name" data-i18n="q2.name">{{ $content['testimonials']['q2.name'] ?? 'ريم الدوسري' }}</div><div class="role" data-i18n="q2.role">{{ $content['testimonials']['q2.role'] ?? 'مديرة العمليات · استوديو نَمَط' }}</div></div>
        </div>
      </div>
      <div class="q">
        <div class="stars">★★★★★</div>
        <div class="body" data-i18n="q3.body">{{ $content['testimonials']['q3.body'] ?? '"بوابة العملاء أعطت شركتنا مظهرًا احترافيًا حقيقيًا."' }}</div>
        <div class="who">
          <div class="av"></div>
          <div><div class="name" data-i18n="q3.name">{{ $content['testimonials']['q3.name'] ?? 'ماجد الشهري' }}</div><div class="role" data-i18n="q3.role">{{ $content['testimonials']['q3.role'] ?? 'شريك مؤسس · شركة تطوير برمجيات' }}</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="section" id="pricing">
  <div class="wrap">
    <h2 data-i18n-html="sec.pricingTitle">{!! $content['pricing']['sec.pricingTitle'] ?? 'خطط واضحة <span class="accent">ومرنة</span>' !!}</h2>
    <p class="lead" data-i18n="sec.pricingLead">{{ $content['pricing']['sec.pricingLead'] ?? 'اختر الخطة المناسبة لحجم فريقك. بدون رسوم مخفية، يمكنك إلغاء الاشتراك في أي وقت.' }}</p>
    <div class="pricing-toggle">
      <div class="pt">
        <button class="on" data-i18n="pr.monthly">{{ $content['pricing']['pr.monthly'] ?? 'شهري' }}</button>
        <button data-i18n="pr.yearly">{{ $content['pricing']['pr.yearly'] ?? 'سنوي · وفّر ٢٠٪' }}</button>
      </div>
    </div>
    <div class="price-cards">
      <div class="pc">
        <div class="label" data-i18n="pr.basic">{{ $content['pricing']['pr.basic'] ?? 'الباقة الأساسية' }}</div>
        <div class="price">
          <span class="per" data-i18n="pr.per">{{ $content['pricing']['pr.per'] ?? 'ر.س/شهر' }}</span>
          <span id="price-basic">{{ $content['pricing']['pr.price.basic.monthly'] ?? '199' }}</span>
        </div>
        <div class="note" data-i18n="pr.basicNote">{{ $content['pricing']['pr.basicNote'] ?? 'للفرق الصغيرة حتى ٥ مستخدمين' }}</div>
        <a class="btn btn-outline" href="#" data-i18n="pr.start">{{ $content['pricing']['pr.start'] ?? 'ابدأ تجربتك' }}</a>
      </div>
      <div class="pc pop" data-popular="{{ $content['pricing']['pr.popular'] ?? 'الأكثر شعبية' }}">
        <div class="label" data-i18n="pr.pro">{{ $content['pricing']['pr.pro'] ?? 'الباقة الاحترافية' }}</div>
        <div class="price">
          <span class="per" data-i18n="pr.per">{{ $content['pricing']['pr.per'] ?? 'ر.س/شهر' }}</span>
          <span id="price-pro">{{ $content['pricing']['pr.price.pro.monthly'] ?? '499' }}</span>
        </div>
        <div class="note" data-i18n="pr.proNote">{{ $content['pricing']['pr.proNote'] ?? 'للشركات المتنامية بمستخدمين غير محدودين' }}</div>
        <a class="btn btn-primary" href="#" data-i18n="pr.start">{{ $content['pricing']['pr.start'] ?? 'ابدأ تجربتك' }}</a>
      </div>
    </div>

    <table class="price-table">
      <thead>
        <tr>
          <th style="text-align:start" data-i18n="pt.features">{{ $content['pricing']['pt.features'] ?? 'المميزات' }}</th>
          <th data-i18n="pr.basic">{{ $content['pricing']['pr.basic'] ?? 'الباقة الأساسية' }}</th>
          <th data-i18n="pr.pro">{{ $content['pricing']['pr.pro'] ?? 'الباقة الاحترافية' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr><td data-i18n="pt.branches">{{ $content['pricing']['pt.branches'] ?? 'عدد المشاريع' }}</td><td data-i18n="pt.one">{{ $content['pricing']['pt.one'] ?? 'حتى ١٠ مشاريع' }}</td><td data-i18n="pt.unlim">{{ $content['pricing']['pt.unlim'] ?? 'غير محدود' }}</td></tr>
        <tr><td data-i18n="pt.staffN">{{ $content['pricing']['pt.staffN'] ?? 'عدد المستخدمين' }}</td><td data-i18n="pt.upto5">{{ $content['pricing']['pt.upto5'] ?? 'حتى ٥' }}</td><td data-i18n="pt.unlim">{{ $content['pricing']['pt.unlim'] ?? 'غير محدود' }}</td></tr>
        <tr><td data-i18n="pt.posR">{{ $content['pricing']['pt.posR'] ?? 'إدارة المشاريع والمهام' }}</td><td><span class="check">✓</span></td><td><span class="check">✓</span></td></tr>
        <tr><td data-i18n="pt.ordRes">{{ $content['pricing']['pt.ordRes'] ?? 'الفواتير والعروض' }}</td><td><span class="check">✓</span></td><td><span class="check">✓</span></td></tr>
        <tr><td data-i18n="pt.invR">{{ $content['pricing']['pt.invR'] ?? 'إدارة العملاء وبوابتهم' }}</td><td><span class="dash">—</span></td><td><span class="check">✓</span></td></tr>
        <tr><td data-i18n="pt.advRep">{{ $content['pricing']['pt.advRep'] ?? 'تقارير وتحليلات متقدمة' }}</td><td><span class="dash">—</span></td><td><span class="check">✓</span></td></tr>
        <tr><td data-i18n="pt.deliv">{{ $content['pricing']['pt.deliv'] ?? 'تكامل مع المدفوعات الإلكترونية' }}</td><td><span class="dash">—</span></td><td><span class="check">✓</span></td></tr>
        <tr><td data-i18n="pt.supp">{{ $content['pricing']['pt.supp'] ?? 'الدعم الفني' }}</td><td data-i18n="pt.email">{{ $content['pricing']['pt.email'] ?? 'بريد إلكتروني' }}</td><td data-i18n="pt.support247">{{ $content['pricing']['pt.support247'] ?? 'دعم مخصص ٢٤/٧' }}</td></tr>
      </tbody>
    </table>

    {{-- Store pricing data for JS toggle --}}
    <script>
      window.PRICING = {
        basic:  { monthly: {{ json_encode($content['pricing']['pr.price.basic.monthly'] ?? '199') }},  yearly: {{ json_encode($content['pricing']['pr.price.basic.yearly'] ?? '159') }} },
        pro:    { monthly: {{ json_encode($content['pricing']['pr.price.pro.monthly'] ?? '499') }},  yearly: {{ json_encode($content['pricing']['pr.price.pro.yearly'] ?? '399') }} }
      };
    </script>
  </div>
</section>

<!-- FAQ -->
<section class="section testimonials" id="faq" style="background:var(--bg);border-top:1px solid var(--line)">
  <div class="wrap">
    <h2 data-i18n-html="sec.faqTitle">{!! $content['faq']['sec.faqTitle'] ?? 'الأسئلة <span class="accent">الشائعة</span>' !!}</h2>
    <p class="lead" data-i18n="sec.faqLead">{{ $content['faq']['sec.faqLead'] ?? 'إجابات على أكثر الأسئلة شيوعًا حول منصة Retont Business.' }}</p>
    <div class="faq-grid">
      <div class="faq"><h5 data-i18n="faq.q1">{{ $content['faq']['faq.q1'] ?? 'هل أستطيع تجربة المنصة قبل الاشتراك؟' }}</h5><p data-i18n="faq.a1">{{ $content['faq']['faq.a1'] ?? 'نعم، نقدّم تجربة مجانية لمدة ٣٠ يومًا بكل المميزات دون الحاجة لبطاقة ائتمانية.' }}</p></div>
      <div class="faq"><h5 data-i18n="faq.q2">{{ $content['faq']['faq.q2'] ?? 'هل المنصة سحابية أم تعمل على خوادمي؟' }}</h5><p data-i18n="faq.a2">{{ $content['faq']['faq.a2'] ?? 'المنصة سحابية بالكامل — لا حاجة لتثبيت أو صيانة. تعمل من أي متصفّح أو جهاز.' }}</p></div>
      <div class="faq"><h5 data-i18n="faq.q3">{{ $content['faq']['faq.q3'] ?? 'هل تدعم المنصة الفوترة الإلكترونية؟' }}</h5><p data-i18n="faq.a3">{{ $content['faq']['faq.a3'] ?? 'نعم، نحن متوافقون بالكامل مع متطلبات هيئة الزكاة والضريبة والجمارك للفوترة الإلكترونية.' }}</p></div>
      <div class="faq"><h5 data-i18n="faq.q4">{{ $content['faq']['faq.q4'] ?? 'هل يمكنني نقل بياناتي من نظام آخر؟' }}</h5><p data-i18n="faq.a4">{{ $content['faq']['faq.a4'] ?? 'بالتأكيد، فريقنا التقني يساعدك في نقل بيانات العملاء والمشاريع والفواتير مجانًا.' }}</p></div>
      <div class="faq"><h5 data-i18n="faq.q5">{{ $content['faq']['faq.q5'] ?? 'ما طرق الدفع المقبولة من عملائي؟' }}</h5><p data-i18n="faq.a5">{{ $content['faq']['faq.a5'] ?? 'يمكن لعملائك الدفع عبر البطاقات الائتمانية ومدى وعدد من بوابات الدفع الإلكترونية والتحويل البنكي.' }}</p></div>
      <div class="faq"><h5 data-i18n="faq.q6">{{ $content['faq']['faq.q6'] ?? 'هل أستطيع إلغاء الاشتراك في أي وقت؟' }}</h5><p data-i18n="faq.a6">{{ $content['faq']['faq.a6'] ?? 'نعم، الاشتراك شهري ويمكنك إلغاؤه في أي وقت دون رسوم إضافية.' }}</p></div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section class="section" id="contact" style="padding-top:40px">
  <div class="wrap">
    <h2 data-i18n-html="sec.contactTitle">{!! $content['contact']['sec.contactTitle'] ?? 'تواصل <span class="accent">معنا</span>' !!}</h2>
    <p class="lead" data-i18n="sec.contactLead">{{ $content['contact']['sec.contactLead'] ?? 'فريقنا جاهز للإجابة على استفساراتك وتقديم عرض توضيحي مخصص لعملك.' }}</p>
    <div class="contact-row">

      {{-- Left: Contact info --}}
      <div class="contact-info">
        <div class="field">
          <h4 data-i18n="ct.loc">{{ $content['contact']['ct.loc'] ?? 'الموقع' }}</h4>
          <div class="v" data-i18n="ct.locV">{{ $content['contact']['ct.locV'] ?? 'الرياض، المملكة العربية السعودية' }}</div>
          <div class="l" data-i18n="ct.locL">{{ $content['contact']['ct.locL'] ?? 'طريق الملك فهد، حي العليا' }}</div>
        </div>
        <div class="field">
          <h4 data-i18n="ct.email">{{ $content['contact']['ct.email'] ?? 'البريد الإلكتروني' }}</h4>
          <div class="v">hello@retont.business</div>
          <div class="l" data-i18n="ct.emailL">{{ $content['contact']['ct.emailL'] ?? 'للاستفسارات العامة والمبيعات' }}</div>
        </div>
        <div class="field">
          <h4 data-i18n="ct.phone">{{ $content['contact']['ct.phone'] ?? 'الهاتف' }}</h4>
          <div class="v">+966 555 123 456</div>
          <div class="l" data-i18n="ct.phoneL">{{ $content['contact']['ct.phoneL'] ?? 'من الأحد إلى الخميس · ٩ صباحًا – ٦ مساءً' }}</div>
        </div>
      </div>

      {{-- Right: Contact form --}}
      <div style="flex:1;min-width:280px;">
        @if(session('contact_success'))
        <div style="padding:1.5rem;border-radius:1rem;background:color-mix(in srgb,var(--brand) 8%,transparent);border:1px solid color-mix(in srgb,var(--brand) 25%,transparent);text-align:center;">
          <svg style="margin:0 auto 1rem;display:block;width:40px;height:40px;color:var(--brand);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p style="color:var(--brand);font-weight:700;font-size:1rem;">{{ $lang === 'ar' ? 'تم إرسال رسالتك بنجاح!' : 'Message sent successfully!' }}</p>
          <p style="color:#666;font-size:.9rem;margin-top:.5rem;">{{ $lang === 'ar' ? 'سنتواصل معك قريباً.' : 'We\'ll get back to you shortly.' }}</p>
        </div>
        @else
        <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" style="display:flex;flex-direction:column;gap:1rem;">
          @csrf
          @if($errors->any())
          <div style="padding:.75rem 1rem;border-radius:.75rem;background:#fff0f0;border:1px solid #fca5a5;font-size:.875rem;color:#dc2626;">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
          </div>
          @endif

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div style="display:flex;flex-direction:column;gap:.4rem;">
              <label style="font-size:.8rem;font-weight:600;color:var(--text-muted,#666);">{{ $lang === 'ar' ? 'الاسم' : 'Name' }} *</label>
              <input type="text" name="name" value="{{ old('name') }}" required
                style="padding:.7rem 1rem;border-radius:.65rem;border:1.5px solid #e2e8f0;font-size:.9rem;outline:none;transition:border-color .2s;background:var(--card-bg,#fff);color:var(--text,#1a1a1a);width:100%;box-sizing:border-box;"
                onfocus="this.style.borderColor='var(--brand)'" onblur="this.style.borderColor='#e2e8f0'"
                placeholder="{{ $lang === 'ar' ? 'اكتب اسمك' : 'Full name' }}">
            </div>
            <div style="display:flex;flex-direction:column;gap:.4rem;">
              <label style="font-size:.8rem;font-weight:600;color:var(--text-muted,#666);">{{ $lang === 'ar' ? 'البريد الإلكتروني' : 'Email' }} *</label>
              <input type="email" name="email" value="{{ old('email') }}" required
                style="padding:.7rem 1rem;border-radius:.65rem;border:1.5px solid #e2e8f0;font-size:.9rem;outline:none;transition:border-color .2s;background:var(--card-bg,#fff);color:var(--text,#1a1a1a);width:100%;box-sizing:border-box;"
                onfocus="this.style.borderColor='var(--brand)'" onblur="this.style.borderColor='#e2e8f0'"
                placeholder="{{ $lang === 'ar' ? 'بريدك الإلكتروني' : 'your@email.com' }}">
            </div>
          </div>

          <div style="display:flex;flex-direction:column;gap:.4rem;">
            <label style="font-size:.8rem;font-weight:600;color:var(--text-muted,#666);">{{ $lang === 'ar' ? 'الرسالة' : 'Message' }} *</label>
            <textarea name="message" required rows="5"
              style="padding:.7rem 1rem;border-radius:.65rem;border:1.5px solid #e2e8f0;font-size:.9rem;outline:none;transition:border-color .2s;resize:vertical;background:var(--card-bg,#fff);color:var(--text,#1a1a1a);font-family:inherit;width:100%;box-sizing:border-box;"
              onfocus="this.style.borderColor='var(--brand)'" onblur="this.style.borderColor='#e2e8f0'"
              placeholder="{{ $lang === 'ar' ? 'اكتب رسالتك هنا…' : 'How can we help you?' }}">{{ old('message') }}</textarea>
          </div>

          <button type="submit" class="btn btn-primary" style="align-self:flex-start;padding:.75rem 2rem;font-size:.95rem;">
            {{ $lang === 'ar' ? 'إرسال الرسالة' : 'Send Message' }}
            <svg style="width:16px;height:16px;margin-{{ $lang === 'ar' ? 'right' : 'left' }}:.4rem;display:inline-block;vertical-align:middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
          </button>
        </form>
        @endif
      </div>

    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="wrap foot">
    <div class="brand">
      @if(!empty($settings['logo_url']) || !empty($settings['logo_path']))
        @php $footerLogoUrl = $settings['logo_url'] ?? asset($settings['logo_path']); @endphp
        @if(!empty($settings['dark_logo_url']))
          <img src="{{ $footerLogoUrl }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-light" style="width:28px;height:28px;object-fit:contain" />
          <img src="{{ $settings['dark_logo_url'] }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" class="logo-dark" style="width:28px;height:28px;object-fit:contain" />
        @else
          <img src="{{ $footerLogoUrl }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" style="width:28px;height:28px;object-fit:contain" />
        @endif
      @else
      <img src="{{ asset('assets/logo.png') }}" alt="{{ $settings['site_name'] ?? 'Retont Business' }}" style="width:28px;height:28px;object-fit:contain" />
      @endif
      <span style="font-size:16px">{{ $settings['site_name'] ?? 'Retont Business' }}</span>
    </div>
    <div data-i18n="footer.rights">{{ $content['footer']['footer.rights'] ?? '© ٢٠٢٦ Retont Business. جميع الحقوق محفوظة.' }}</div>
    <div class="social">
      <a href="#" aria-label="X (Twitter)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2H21.5l-7.41 8.466L23 22h-6.8l-5.32-6.95L4.8 22H1.54l7.93-9.06L1 2h6.96l4.81 6.36L18.244 2zm-1.19 18h1.88L7.05 4H5.04l12.014 16z"/></svg>
      </a>
      <a href="#" aria-label="Instagram">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
      </a>
      <a href="#" aria-label="Facebook">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88V14.9H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.9h-2.33v6.98A10 10 0 0 0 22 12z"/></svg>
      </a>
      <div class="lang-wrap">
        <button id="langBtn" aria-label="Language" aria-haspopup="true" aria-expanded="false">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a13.5 13.5 0 0 1 0 18M12 3a13.5 13.5 0 0 0 0 18"/></svg>
        </button>
        <div class="lang-menu" id="langMenu" role="menu">
          <a href="?lang=ar" role="menuitem" class="{{ $lang === 'ar' ? 'active' : '' }}" onclick="localStorage.setItem('rt_lang','ar')"><span class="flag">🇸🇦</span><span class="lbl">العربية</span>@if($lang === 'ar')<span class="ck">✓</span>@endif</a>
          <a href="?lang=en" role="menuitem" class="{{ $lang === 'en' ? 'active' : '' }}" onclick="localStorage.setItem('rt_lang','en')"><span class="flag">🇬🇧</span><span class="lbl">English</span>@if($lang === 'en')<span class="ck">✓</span>@endif</a>
          <a href="?lang=nl" role="menuitem" class="{{ $lang === 'nl' ? 'active' : '' }}" onclick="localStorage.setItem('rt_lang','nl')"><span class="flag">🇳🇱</span><span class="lbl">Nederlands</span>@if($lang === 'nl')<span class="ck">✓</span>@endif</a>
          <a href="?lang=de" role="menuitem" class="{{ $lang === 'de' ? 'active' : '' }}" onclick="localStorage.setItem('rt_lang','de')"><span class="flag">🇩🇪</span><span class="lbl">Deutsch</span>@if($lang === 'de')<span class="ck">✓</span>@endif</a>
        </div>
      </div>
    </div>
  </div>
</footer>

<script src="{{ asset('i18n.js') }}"></script>
<script src="{{ asset('app.js') }}"></script>
</body>
</html>
