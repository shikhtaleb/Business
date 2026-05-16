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
      <a href="#home">{{ $content['nav']['nav.home'] ?? 'Home' }}</a>
      <a href="#features">{{ $content['nav']['nav.features'] ?? 'Features' }}</a>
      <a href="#pricing">{{ $content['nav']['nav.pricing'] ?? 'Pricing' }}</a>
      <a href="#faq">{{ $content['nav']['nav.faq'] ?? 'FAQ' }}</a>
      <a href="#contact">{{ $content['nav']['nav.contact'] ?? 'Contact' }}</a>
    </nav>
    <div class="nav-cta">
      <button class="nav-toggle" id="navToggle" aria-label="menu" aria-expanded="false">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <button class="theme-toggle" id="themeToggle" aria-label="theme" aria-pressed="false">
        <svg class="moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
        <svg class="sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
      </button>
      <a href="#" class="btn btn-ghost">{{ $content['nav']['nav.login'] ?? 'Login' }}</a>
      <a href="#" class="btn btn-primary">{{ $content['nav']['nav.cta'] ?? 'Get Started' }}</a>
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
    <h1>{!! $content['hero']['hero.title'] ?? 'منصة <span class="accent">Retont Business</span><br/>لإدارة مشاريعك وفريقك بسلاسة' !!}</h1>
    <p class="sub">{{ $content['hero']['hero.sub'] ?? 'أدر المشاريع والمهام والعملاء والفواتير من لوحة واحدة.' }}</p>
    <div class="cta-row">
      <a href="#" class="btn btn-primary">{{ $content['hero']['hero.cta1'] ?? 'ابدأ تجربة ٣٠ يومًا مجانًا' }}</a>
      <a href="#" class="btn btn-outline">{{ $content['hero']['hero.cta2'] ?? 'شاهد العرض التوضيحي' }}</a>
    </div>

    <!-- Dashboard mock -->
    <div class="dash-wrap">
      <div class="dash">
        <div class="dash-inner">
          <aside class="ds-side">
            <div class="item active"><span class="ico"></span><span>{{ $content['hero']['ds.dashboard'] ?? 'Dashboard' }}</span></div>
            <div class="group">{{ $content['hero']['ds.manage'] ?? 'Work' }}</div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.projects'] ?? 'Projects' }}</span></div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.tasks'] ?? 'Tasks' }}</span></div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.clients'] ?? 'Clients' }}</span></div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.invoices'] ?? 'Invoices' }}</span></div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.payments'] ?? 'Payments' }}</span></div>
            <div class="group">{{ $content['hero']['ds.general'] ?? 'General' }}</div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.staff'] ?? 'Team' }}</span></div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.attendance'] ?? 'Attendance' }}</span></div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.reports'] ?? 'Reports' }}</span></div>
            <div class="item"><span class="ico"></span><span>{{ $content['hero']['ds.settings'] ?? 'Settings' }}</span></div>
          </aside>
          <main class="ds-main">
            <div class="ds-top">
              <div class="ds-tabs">
                <span class="tab on">{{ $content['hero']['ds.tabToday'] ?? "Today's Tasks" }}</span>
                <span class="tab">{{ $content['hero']['ds.tabRes'] ?? 'Active Projects' }}</span>
                <span class="tab">{{ $content['hero']['ds.tabInv'] ?? 'Time Logs' }}</span>
              </div>
              <div class="ds-search">{{ $content['hero']['ds.search'] ?? '🔍 Quick search…' }}</div>
            </div>
            <div class="ds-grid">
              <div style="display:flex;flex-direction:column;gap:10px">
                <div class="ds-stats">
                  <div class="ds-card">
                    <h4>{{ $content['hero']['ds.totalOrders'] ?? 'Active Projects' }}</h4>
                    <div class="big">24</div>
                    <div class="delta">{{ $content['hero']['ds.delta1'] ?? '▲ 3 new projects this week' }}</div>
                  </div>
                  <div class="ds-card">
                    <h4>{{ $content['hero']['ds.avgInv'] ?? 'Tasks Completed' }}</h4>
                    <div class="big">186</div>
                    <div class="delta">{{ $content['hero']['ds.delta2'] ?? '▲ 12% vs last month' }}</div>
                  </div>
                </div>
                <div class="ds-card">
                  <h4>{{ $content['hero']['ds.weeklySales'] ?? 'Weekly Revenue' }}</h4>
                  <div class="big" style="font-size:22px"><span>{{ $content['hero']['ds.sales'] ?? '$11,400' }}</span></div>
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
                <h4 style="margin-bottom:10px">{{ $content['hero']['ds.todayOrders'] ?? "Today's Tasks" }}</h4>
                <div class="order"><span class="av"></span><div class="name">{{ $content['hero']['ds.order103'] ?? 'Task 1' }}<span class="meta">{{ $content['hero']['ds.order103'] ?? '' }}</span></div><span class="badge">{{ $content['hero']['ds.paid'] ?? 'Done' }}</span></div>
                <div class="order"><span class="av"></span><div class="name">{{ $content['hero']['ds.order102'] ?? 'Task 2' }}<span class="meta">{{ $content['hero']['ds.order102'] ?? '' }}</span></div><span class="badge warn">{{ $content['hero']['ds.preparing'] ?? 'In Progress' }}</span></div>
                <div class="order"><span class="av"></span><div class="name">{{ $content['hero']['ds.order101'] ?? 'Task 3' }}<span class="meta">{{ $content['hero']['ds.order101'] ?? '' }}</span></div><span class="badge">{{ $content['hero']['ds.paid'] ?? 'Done' }}</span></div>
                <div class="order"><span class="av"></span><div class="name">{{ $content['hero']['ds.order100'] ?? 'Task 4' }}<span class="meta">{{ $content['hero']['ds.order100'] ?? '' }}</span></div><span class="badge warn">{{ $content['hero']['ds.preparing'] ?? 'In Progress' }}</span></div>
                <div class="order"><span class="av"></span><div class="name">{{ $content['hero']['ds.order099'] ?? 'Task 5' }}<span class="meta">{{ $content['hero']['ds.order099'] ?? '' }}</span></div><span class="badge">{{ $content['hero']['ds.paid'] ?? 'Done' }}</span></div>
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
    <h2>{!! $content['spotlight1']['sec.controlTitle'] ?? 'Take control of <span class="accent">your business</span>' !!}</h2>
    <p class="lead">{{ $content['spotlight1']['sec.controlLead'] ?? 'Three core modules in one platform.' }}</p>

    <!-- 1. Invoice / Billing -->
    <div class="spotlight">
      <div class="mock inv">
        <div class="head">
          <div class="who"><span>{{ $content['spotlight1']['mock.customer1'] ?? 'Al‑Ofuq Trading Co.' }}</span><span class="date">{{ $content['spotlight1']['mock.date1'] ?? 'Invoice #INV‑2026‑047' }}</span></div>
          <span class="tag">{{ $content['spotlight1']['mock.paid'] ?? 'Paid' }}</span>
        </div>
        <table>
          <thead><tr><th>{{ $content['spotlight1']['mock.item'] ?? 'Item' }}</th><th>{{ $content['spotlight1']['mock.qty'] ?? 'Hours' }}</th><th>{{ $content['spotlight1']['mock.price'] ?? 'Rate/hr' }}</th><th>{{ $content['spotlight1']['mock.sum'] ?? 'Total' }}</th></tr></thead>
          <tbody>
            <tr><td>{{ $content['spotlight1']['mock.burger'] ?? 'UI/UX Design' }}</td><td>{{ $content['spotlight1']['mock.n2'] ?? '32' }}</td><td>150</td><td>4,800</td></tr>
            <tr><td>{{ $content['spotlight1']['mock.juice'] ?? 'Frontend Development' }}</td><td>{{ $content['spotlight1']['mock.n1'] ?? '24' }}</td><td>180</td><td>4,320</td></tr>
            <tr><td>{{ $content['spotlight1']['mock.salad'] ?? 'Project Management' }}</td><td>١٢</td><td>120</td><td>1,440</td></tr>
          </tbody>
        </table>
        <div class="total"><span>{{ $content['spotlight1']['mock.total'] ?? 'Total (incl. VAT)' }}</span><span>{{ $content['spotlight1']['mock.totalVal'] ?? '$3,245' }}</span></div>
        <div class="float f1">
          <span class="a"></span>
          <div class="n"><span>{{ $content['spotlight1']['mock.order047'] ?? 'Project #047' }}</span><span class="sm">{{ $content['spotlight1']['mock.cust2'] ?? 'Nasaem Tech Co.' }}</span></div>
          <span class="badge">{{ $content['spotlight1']['mock.new'] ?? 'In Progress' }}</span>
        </div>
        <div class="float f2">
          <span class="a"></span>
          <div class="n"><span>{{ $content['spotlight1']['mock.amount'] ?? '$1,985' }}</span><span class="sm">{{ $content['spotlight1']['mock.payOk'] ?? 'New payment received' }}</span></div>
        </div>
      </div>
      <div class="sp-text">
        <h3>{{ $content['spotlight1']['sp.orders.h'] ?? 'From task to invoice, effortlessly' }}</h3>
        <p>{{ $content['spotlight1']['sp.orders.p'] ?? 'Turn logged hours into accurate invoices in one click.' }}</p>
        <ul>
          <li>{{ $content['spotlight1']['sp.orders.l1'] ?? 'Professional multi‑currency invoices with tax compliance' }}</li>
          <li>{{ $content['spotlight1']['sp.orders.l2'] ?? 'Automated payment reminders via email and WhatsApp' }}</li>
          <li>{{ $content['spotlight1']['sp.orders.l3'] ?? 'Direct integration with Stripe, PayPal and local gateways' }}</li>
        </ul>
      </div>
    </div>

    <!-- 2. Tasks / Projects board -->
    <div class="spotlight">
      <div class="sp-text">
        <h3>{{ $content['spotlight2']['sp.res.h'] ?? 'A visual board for task management' }}</h3>
        <p>{{ $content['spotlight2']['sp.res.p'] ?? 'Organize your team\'s work visually: from draft to done.' }}</p>
        <ul>
          <li>{{ $content['spotlight2']['sp.res.l1'] ?? 'Kanban, Gantt and list views on the same data' }}</li>
          <li>{{ $content['spotlight2']['sp.res.l2'] ?? 'Assign tasks with due dates and priorities' }}</li>
          <li>{{ $content['spotlight2']['sp.res.l3'] ?? 'Time tracking per task and team member' }}</li>
        </ul>
      </div>
      <div class="mock res">
        <div class="res-title">{{ $content['spotlight2']['res.title'] ?? 'Project Board' }}</div>
        <div class="row">
          <div class="pill"><div><span class="lab">{{ $content['spotlight2']['res.from'] ?? 'Period' }}</span><span class="v">{{ $content['spotlight2']['res.dateA'] ?? 'May 15 – 22' }}</span></div></div>
          <div class="pill"><div><span class="lab">{{ $content['spotlight2']['res.to'] ?? 'Project' }}</span><span class="v">{{ $content['spotlight2']['res.dateB'] ?? 'Sawahel Website' }}</span></div></div>
        </div>
        <div class="row">
          <div class="pill" style="background:var(--soft)">
            <div style="flex:1"><span class="lab">{{ $content['spotlight2']['res.t4'] ?? 'Homepage design' }}</span><span class="v">{{ $content['spotlight2']['res.fam'] ?? 'Sarah Al‑Mutairi · High priority' }}</span></div>
            <span class="chip ok">{{ $content['spotlight2']['res.confirmed'] ?? 'Done' }}</span>
          </div>
        </div>
        <div class="row">
          <div class="pill">
            <div style="flex:1"><span class="lab">{{ $content['spotlight2']['res.t7'] ?? 'Frontend build' }}</span><span class="v">{{ $content['spotlight2']['res.phone'] ?? 'Due May 20 · 12 hours' }}</span></div>
            <span class="chip">{{ $content['spotlight2']['res.pending'] ?? 'In Progress' }}</span>
          </div>
        </div>
        <div class="row">
          <div class="pill">
            <div style="flex:1"><span class="lab">{{ $content['spotlight2']['res.t2'] ?? 'Database setup' }}</span><span class="v">{{ $content['spotlight2']['res.cust3'] ?? 'Faisal Al‑Nemer · Medium priority' }}</span></div>
            <span class="chip ok">{{ $content['spotlight2']['res.arrived'] ?? 'In Review' }}</span>
          </div>
        </div>
        <div class="row">
          <div class="pill">
            <div style="flex:1"><span class="lab">{{ $content['spotlight2']['res.t9'] ?? 'QA testing' }}</span><span class="v">{{ $content['spotlight2']['res.cust4'] ?? 'Noura Al‑Zahrani · 4 subtasks' }}</span></div>
            <span class="chip">{{ $content['spotlight2']['res.pending'] ?? 'In Progress' }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. CRM / Clients -->
    <div class="spotlight">
      <div class="mock menu">
        <div class="tabs">
          <span class="t on"><span>{{ $content['spotlight3']['menu.tab1'] ?? 'Active Clients' }}</span><span class="ct">٤</span></span>
          <span class="t"><span>{{ $content['spotlight3']['menu.tab2'] ?? 'Leads' }}</span><span class="ct">٦</span></span>
          <span class="t"><span>{{ $content['spotlight3']['menu.tab3'] ?? 'Archived' }}</span><span class="ct">٢</span></span>
        </div>
        <div class="menu-head">
          <span class="title">{{ $content['spotlight3']['menu.tab1'] ?? 'Active Clients' }}</span>
          <span class="update">{{ $content['spotlight3']['menu.update'] ?? 'Add Client' }}</span>
        </div>
        <div class="item-row">
          <div class="thumb"></div>
          <div><div class="n">{{ $content['spotlight3']['menu.i1n'] ?? 'Al‑Ofuq Trading Co.' }}</div><div class="d">{{ $content['spotlight3']['menu.i1d'] ?? '3 active projects' }}</div></div>
          <div class="price"><span>{{ $content['spotlight3']['menu.p42'] ?? '$11,200' }}</span></div>
        </div>
        <div class="item-row">
          <div class="thumb b"></div>
          <div><div class="n">{{ $content['spotlight3']['menu.i2n'] ?? 'Nasaem Tech Co.' }}</div><div class="d">{{ $content['spotlight3']['menu.i2d'] ?? '2 active projects' }}</div></div>
          <div class="price"><span>{{ $content['spotlight3']['menu.p38'] ?? '$10,260' }}</span></div>
        </div>
        <div class="item-row">
          <div class="thumb c"></div>
          <div><div class="n">{{ $content['spotlight3']['menu.i3n'] ?? 'Basma E‑Store' }}</div><div class="d">{{ $content['spotlight3']['menu.i3d'] ?? '1 project' }}</div></div>
          <div class="price"><span>{{ $content['spotlight3']['menu.p28'] ?? '$7,520' }}</span></div>
        </div>
        <div class="item-row">
          <div class="thumb d"></div>
          <div><div class="n">{{ $content['spotlight3']['menu.i4n'] ?? 'Riyadh Destination Group' }}</div><div class="d">{{ $content['spotlight3']['menu.i4d'] ?? '4 projects · VIP client since 2023' }}</div></div>
          <div class="price"><span>{{ $content['spotlight3']['menu.p45'] ?? '$12,240' }}</span></div>
        </div>
      </div>
      <div class="sp-text">
        <h3>{{ $content['spotlight3']['sp.menu.h'] ?? 'A built‑in CRM for client relationships' }}</h3>
        <p>{{ $content['spotlight3']['sp.menu.p'] ?? 'Store contacts, track sales opportunities from quote to close.' }}</p>
        <ul>
          <li>{{ $content['spotlight3']['sp.menu.l1'] ?? 'Customizable sales pipeline' }}</li>
          <li>{{ $content['spotlight3']['sp.menu.l2'] ?? 'Full quote and contract history per client' }}</li>
          <li>{{ $content['spotlight3']['sp.menu.l3'] ?? 'Secure client portal for projects and invoices' }}</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES GRID -->
<section class="section" style="padding-top:0">
  <div class="wrap">
    <h2>{!! $content['features']['sec.allInOneTitle'] ?? 'Everything you need <span class="accent">in one place</span>' !!}</h2>
    <p class="lead">{{ $content['features']['sec.allInOneLead'] ?? 'A complete platform for every side of your business.' }}</p>
    <div class="features">
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/></svg></div>
        <h4>{{ $content['features']['ft.pos.h'] ?? 'Project management' }}</h4>
        <p>{{ $content['features']['ft.pos.p'] ?? 'Plan, execute and monitor projects.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18M3 12h18M3 17h12"/></svg></div>
        <h4>{{ $content['features']['ft.rep.h'] ?? 'Smart reports' }}</h4>
        <p>{{ $content['features']['ft.rep.p'] ?? 'Live reports on revenue, productivity and profitability.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 3v6"/></svg></div>
        <h4>{{ $content['features']['ft.inv.h'] ?? 'Invoices & estimates' }}</h4>
        <p>{{ $content['features']['ft.inv.p'] ?? 'E‑quotes, e‑invoices and contracts with full tax compliance.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg></div>
        <h4>{{ $content['features']['ft.stock.h'] ?? 'Time tracking' }}</h4>
        <p>{{ $content['features']['ft.stock.p'] ?? 'Log team hours per task; cost and profitability calculated automatically.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div>
        <h4>{{ $content['features']['ft.staff.h'] ?? 'Team & permissions' }}</h4>
        <p>{{ $content['features']['ft.staff.p'] ?? 'Manage team, leaves, attendance, and role‑based access.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-9.5 8.5L3 21l1-7A8 8 0 1 1 21 11.5z"/></svg></div>
        <h4>{{ $content['features']['ft.comm.h'] ?? 'Team chat' }}</h4>
        <p>{{ $content['features']['ft.comm.p'] ?? 'Project channels, instant notifications, and comments on tasks.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg></div>
        <h4>{{ $content['features']['ft.pay.h'] ?? 'Online payments' }}</h4>
        <p>{{ $content['features']['ft.pay.p'] ?? 'Accept cards, Apple Pay, STC Pay, Stripe, PayPal and bank transfers.' }}</p>
      </div>
      <div class="feat">
        <div class="ico"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h10"/><circle cx="18" cy="17" r="2"/></svg></div>
        <h4>{{ $content['features']['ft.sup.h'] ?? 'Support tickets' }}</h4>
        <p>{{ $content['features']['ft.sup.p'] ?? 'Built‑in helpdesk to receive client requests and respond fast.' }}</p>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section testimonials">
  <div class="wrap">
    <h2>{!! $content['testimonials']['sec.testTitle'] ?? 'What our <span class="accent">customers say</span>' !!}</h2>
    <p class="lead">{{ $content['testimonials']['sec.testLead'] ?? 'Thousands of teams and businesses trust Retont Business.' }}</p>
    <div class="quotes">
      <div class="q">
        <div class="stars">★★★★★</div>
        <div class="body">{{ $content['testimonials']['q1.body'] ?? '"We used to run five different tools. Today it all lives inside Retont Business."' }}</div>
        <div class="who">
          <div class="av"></div>
          <div><div class="name">{{ $content['testimonials']['q1.name'] ?? 'Khaled Al‑Omari' }}</div><div class="role">{{ $content['testimonials']['q1.role'] ?? 'CEO · Ithraa Digital Agency' }}</div></div>
        </div>
      </div>
      <div class="q">
        <div class="stars">★★★★★</div>
        <div class="body">{{ $content['testimonials']['q2.body'] ?? '"Linking time logs to tasks and invoices changed how we work."' }}</div>
        <div class="who">
          <div class="av"></div>
          <div><div class="name">{{ $content['testimonials']['q2.name'] ?? 'Reem Al‑Dosari' }}</div><div class="role">{{ $content['testimonials']['q2.role'] ?? 'Operations Manager · Namat Studio' }}</div></div>
        </div>
      </div>
      <div class="q">
        <div class="stars">★★★★★</div>
        <div class="body">{{ $content['testimonials']['q3.body'] ?? '"The client portal made our company look genuinely professional."' }}</div>
        <div class="who">
          <div class="av"></div>
          <div><div class="name">{{ $content['testimonials']['q3.name'] ?? 'Majed Al‑Shehri' }}</div><div class="role">{{ $content['testimonials']['q3.role'] ?? 'Co‑Founder · Software Development Co.' }}</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="section" id="pricing">
  <div class="wrap">
    <h2>{!! $content['pricing']['sec.pricingTitle'] ?? 'Simple, <span class="accent">transparent pricing</span>' !!}</h2>
    <p class="lead">{{ $content['pricing']['sec.pricingLead'] ?? 'Pick the plan that fits your team. No hidden fees — cancel anytime.' }}</p>
    <div class="pricing-toggle">
      <div class="pt">
        <button class="on">{{ $content['pricing']['pr.monthly'] ?? 'Monthly' }}</button>
        <button>{{ $content['pricing']['pr.yearly'] ?? 'Yearly · save 20%' }}</button>
      </div>
    </div>
    <div class="price-cards">
      <div class="pc">
        <div class="label">{{ $content['pricing']['pr.basic'] ?? 'Basic' }}</div>
        <div class="price">
          <span class="per">{{ $content['pricing']['pr.per'] ?? 'USD/month' }}</span>
          <span id="price-basic">{{ $content['pricing']['pr.price.basic.monthly'] ?? '49' }}</span>
        </div>
        <div class="note">{{ $content['pricing']['pr.basicNote'] ?? 'For small teams of up to 5 users' }}</div>
        <a class="btn btn-outline" href="#">{{ $content['pricing']['pr.start'] ?? 'Start your trial' }}</a>
      </div>
      <div class="pc pop" data-popular="{{ $content['pricing']['pr.popular'] ?? 'Most popular' }}">
        <div class="label">{{ $content['pricing']['pr.pro'] ?? 'Professional' }}</div>
        <div class="price">
          <span class="per">{{ $content['pricing']['pr.per'] ?? 'USD/month' }}</span>
          <span id="price-pro">{{ $content['pricing']['pr.price.pro.monthly'] ?? '129' }}</span>
        </div>
        <div class="note">{{ $content['pricing']['pr.proNote'] ?? 'For growing companies with unlimited users' }}</div>
        <a class="btn btn-primary" href="#">{{ $content['pricing']['pr.start'] ?? 'Start your trial' }}</a>
      </div>
    </div>

    <table class="price-table">
      <thead>
        <tr>
          <th style="text-align:start">{{ $content['pricing']['pt.features'] ?? 'Features' }}</th>
          <th>{{ $content['pricing']['pr.basic'] ?? 'Basic' }}</th>
          <th>{{ $content['pricing']['pr.pro'] ?? 'Professional' }}</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>{{ $content['pricing']['pt.branches'] ?? 'Projects' }}</td><td>{{ $content['pricing']['pt.one'] ?? 'Up to 10' }}</td><td>{{ $content['pricing']['pt.unlim'] ?? 'Unlimited' }}</td></tr>
        <tr><td>{{ $content['pricing']['pt.staffN'] ?? 'Users' }}</td><td>{{ $content['pricing']['pt.upto5'] ?? 'Up to 5' }}</td><td>{{ $content['pricing']['pt.unlim'] ?? 'Unlimited' }}</td></tr>
        <tr><td>{{ $content['pricing']['pt.posR'] ?? 'Projects & tasks' }}</td><td><span class="check">✓</span></td><td><span class="check">✓</span></td></tr>
        <tr><td>{{ $content['pricing']['pt.ordRes'] ?? 'Invoices & estimates' }}</td><td><span class="check">✓</span></td><td><span class="check">✓</span></td></tr>
        <tr><td>{{ $content['pricing']['pt.invR'] ?? 'CRM & client portal' }}</td><td><span class="dash">—</span></td><td><span class="check">✓</span></td></tr>
        <tr><td>{{ $content['pricing']['pt.advRep'] ?? 'Advanced reports' }}</td><td><span class="dash">—</span></td><td><span class="check">✓</span></td></tr>
        <tr><td>{{ $content['pricing']['pt.deliv'] ?? 'Online payment integrations' }}</td><td><span class="dash">—</span></td><td><span class="check">✓</span></td></tr>
        <tr><td>{{ $content['pricing']['pt.supp'] ?? 'Support' }}</td><td>{{ $content['pricing']['pt.email'] ?? 'Email' }}</td><td>{{ $content['pricing']['pt.support247'] ?? 'Dedicated 24/7' }}</td></tr>
      </tbody>
    </table>

    {{-- Store pricing data for JS toggle --}}
    <script>
      window.PRICING = {
        basic:  { monthly: {{ json_encode($content['pricing']['pr.price.basic.monthly'] ?? '49') }},  yearly: {{ json_encode($content['pricing']['pr.price.basic.yearly'] ?? '39') }} },
        pro:    { monthly: {{ json_encode($content['pricing']['pr.price.pro.monthly'] ?? '129') }},  yearly: {{ json_encode($content['pricing']['pr.price.pro.yearly'] ?? '103') }} }
      };
    </script>
  </div>
</section>

<!-- FAQ -->
<section class="section testimonials" id="faq" style="background:var(--bg);border-top:1px solid var(--line)">
  <div class="wrap">
    <h2>{!! $content['faq']['sec.faqTitle'] ?? 'Frequently asked <span class="accent">questions</span>' !!}</h2>
    <p class="lead">{{ $content['faq']['sec.faqLead'] ?? 'Answers to the most common questions about the Retont Business platform.' }}</p>
    <div class="faq-grid">
      <div class="faq"><h5>{{ $content['faq']['faq.q1'] ?? 'Can I try the platform before subscribing?' }}</h5><p>{{ $content['faq']['faq.a1'] ?? 'Yes — a 30‑day free trial with all features, no credit card required.' }}</p></div>
      <div class="faq"><h5>{{ $content['faq']['faq.q2'] ?? 'Is the platform cloud‑based or self‑hosted?' }}</h5><p>{{ $content['faq']['faq.a2'] ?? 'Fully cloud‑based (SaaS) — no installation, no maintenance.' }}</p></div>
      <div class="faq"><h5>{{ $content['faq']['faq.q3'] ?? 'Do you support e‑invoicing?' }}</h5><p>{{ $content['faq']['faq.a3'] ?? 'Yes, we\'re fully compliant with Saudi ZATCA e‑invoicing (Fatoora) requirements.' }}</p></div>
      <div class="faq"><h5>{{ $content['faq']['faq.q4'] ?? 'Can I migrate my data from another system?' }}</h5><p>{{ $content['faq']['faq.a4'] ?? 'Absolutely. Our team migrates your clients, projects and invoices for free.' }}</p></div>
      <div class="faq"><h5>{{ $content['faq']['faq.q5'] ?? 'Which payment methods can my clients use?' }}</h5><p>{{ $content['faq']['faq.a5'] ?? 'Credit cards, mada, STC Pay, Apple Pay, Stripe, PayPal, and direct bank transfer.' }}</p></div>
      <div class="faq"><h5>{{ $content['faq']['faq.q6'] ?? 'Can I cancel anytime?' }}</h5><p>{{ $content['faq']['faq.a6'] ?? 'Yes — the subscription is monthly and can be cancelled anytime, no extra fees.' }}</p></div>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section class="section" id="contact" style="padding-top:40px">
  <div class="wrap">
    <h2>{!! $content['contact']['sec.contactTitle'] ?? 'Get in <span class="accent">touch</span>' !!}</h2>
    <p class="lead">{{ $content['contact']['sec.contactLead'] ?? 'Our team is ready to answer your questions and prepare a custom demo for your business.' }}</p>
    <div class="contact-row">

      {{-- Left: Contact info --}}
      <div class="contact-info">
        <div class="field">
          <h4>{{ $content['contact']['ct.loc'] ?? 'Location' }}</h4>
          <div class="v">{{ $content['contact']['ct.locV'] ?? 'Riyadh, Kingdom of Saudi Arabia' }}</div>
          <div class="l">{{ $content['contact']['ct.locL'] ?? 'King Fahd Road, Al‑Olaya' }}</div>
        </div>
        <div class="field">
          <h4>{{ $content['contact']['ct.email'] ?? 'Email' }}</h4>
          <div class="v">hello@retont.business</div>
          <div class="l">{{ $content['contact']['ct.emailL'] ?? 'For general inquiries and sales' }}</div>
        </div>
        <div class="field">
          <h4>{{ $content['contact']['ct.phone'] ?? 'Phone' }}</h4>
          <div class="v">+966 555 123 456</div>
          <div class="l">{{ $content['contact']['ct.phoneL'] ?? 'Sunday – Thursday · 9 AM – 6 PM' }}</div>
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
    <div>{{ $content['footer']['footer.rights'] ?? '© 2026 Retont Business. All rights reserved.' }}</div>
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
          <a href="?lang=ar" role="menuitem" class="{{ $lang === 'ar' ? 'active' : '' }}"><span class="flag">🇸🇦</span><span class="lbl">العربية</span>@if($lang === 'ar')<span class="ck">✓</span>@endif</a>
          <a href="?lang=en" role="menuitem" class="{{ $lang === 'en' ? 'active' : '' }}"><span class="flag">🇬🇧</span><span class="lbl">English</span>@if($lang === 'en')<span class="ck">✓</span>@endif</a>
          <a href="?lang=nl" role="menuitem" class="{{ $lang === 'nl' ? 'active' : '' }}"><span class="flag">🇳🇱</span><span class="lbl">Nederlands</span>@if($lang === 'nl')<span class="ck">✓</span>@endif</a>
          <a href="?lang=de" role="menuitem" class="{{ $lang === 'de' ? 'active' : '' }}"><span class="flag">🇩🇪</span><span class="lbl">Deutsch</span>@if($lang === 'de')<span class="ck">✓</span>@endif</a>
        </div>
      </div>
    </div>
  </div>
</footer>

<script src="{{ asset('i18n.js') }}"></script>
<script src="{{ asset('app.js') }}"></script>
</body>
</html>
