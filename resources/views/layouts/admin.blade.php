<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      class="h-full"
      x-data="adminApp()"
      :data-admin-theme="darkMode ? 'dark' : 'light'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('admin.dashboard')) — {{ \App\Models\Setting::get('site_name', config('app.name')) }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            safelist: [
                'lg:pl-16','lg:pl-64','lg:pr-16','lg:pr-64',
                'w-16','w-64','-translate-x-full','translate-x-full','translate-x-0','lg:translate-x-0',
            ],
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT:'#FF8528', 50:'#FFF4EA', 500:'#FF8528', 600:'#E06800' }
                    }
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @php $isRtl = app()->getLocale() === 'ar'; @endphp
    @php
    $activeGroup = match(true) {
        request()->routeIs('admin.content.*', 'admin.media.*', 'admin.messages.*', 'admin.plans.*', 'admin.coupons.*') => 'content',
        request()->routeIs('admin.posts.*', 'admin.categories.*') => 'blog',
        request()->routeIs('admin.pages.*', 'admin.menus.*', 'admin.redirects.*') => 'pages',
        request()->routeIs('admin.leads.*', 'admin.subscribers.*', 'admin.campaigns.*') => 'crm',
        request()->routeIs('admin.tickets.*') => 'support',
        request()->routeIs('admin.settings.*', 'admin.languages.*') => 'settings',
        request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.api-keys.*', 'admin.two-factor.*') => 'users',
        request()->routeIs('admin.analytics.*', 'admin.activity.*', 'admin.system.*', 'admin.backups.*') => 'reports',
        default => null,
    };
    @endphp

    <style>
        [x-cloak] { display: none !important; }

        /* ── Sidebar nav links ── */
        .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.45rem 0.75rem;
            border-radius: 0.5rem;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s, background-color 0.15s;
            font-size: 0.8125rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar-link:hover {
            color: #1e293b;
            background-color: #f1f5f9;
        }
        .sidebar-link.active {
            color: #FF8528;
            background-color: #FFF4EA;
            font-weight: 600;
        }
        .sidebar-link.active svg { color: #FF8528 !important; stroke: #FF8528 !important; }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            {{ $isRtl ? 'right' : 'left' }}: 0;
            top: 15%; height: 70%;
            width: 3px;
            border-radius: {{ $isRtl ? '4px 0 0 4px' : '0 4px 4px 0' }};
            background-color: #FF8528;
        }
        .sidebar-group-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.45rem 0.75rem 0.2rem;
            font-size: 0.625rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            margin-top: 0.5rem;
            cursor: pointer;
            border-radius: 0.375rem;
            transition: color 0.15s;
        }
        .sidebar-group-label:hover { color: #64748b; }

        /* ── Dark mode ── */
        [data-admin-theme="dark"] .adm-bg      { background-color: #0f172a !important; }
        [data-admin-theme="dark"] .adm-sidebar { background-color: #1e293b !important; border-color: #334155 !important; }
        [data-admin-theme="dark"] .adm-header  { background-color: #1e293b !important; border-color: #334155 !important; }
        [data-admin-theme="dark"] .adm-footer  { background-color: #1e293b !important; border-color: #334155 !important; }
        [data-admin-theme="dark"] .adm-dropdown { background-color: #1e293b !important; border-color: #334155 !important; }
        [data-admin-theme="dark"] .sidebar-link { color: #94a3b8 !important; }
        [data-admin-theme="dark"] .sidebar-link:hover { color: #e2e8f0 !important; background-color: rgba(255,255,255,0.06) !important; }
        [data-admin-theme="dark"] .sidebar-link.active { color: #FF8528 !important; background-color: rgba(255,133,40,0.12) !important; }
        [data-admin-theme="dark"] .sidebar-group-label { color: #475569 !important; }
        [data-admin-theme="dark"] .sidebar-group-label:hover { color: #64748b !important; }
        [data-admin-theme="dark"] .bg-white      { background-color: #1e293b !important; }
        [data-admin-theme="dark"] .bg-gray-100   { background-color: #0f172a !important; }
        [data-admin-theme="dark"] .bg-gray-50    { background-color: #1e293b !important; }
        [data-admin-theme="dark"] .bg-slate-50   { background-color: #0f172a !important; }
        [data-admin-theme="dark"] .border-gray-100 { border-color: #334155 !important; }
        [data-admin-theme="dark"] .border-gray-200 { border-color: #475569 !important; }
        [data-admin-theme="dark"] .border-gray-300 { border-color: #475569 !important; }
        [data-admin-theme="dark"] .text-gray-900 { color: #f8fafc !important; }
        [data-admin-theme="dark"] .text-gray-800 { color: #f1f5f9 !important; }
        [data-admin-theme="dark"] .text-gray-700 { color: #e2e8f0 !important; }
        [data-admin-theme="dark"] .text-gray-600 { color: #cbd5e1 !important; }
        [data-admin-theme="dark"] .text-gray-500 { color: #94a3b8 !important; }
        [data-admin-theme="dark"] .text-gray-400 { color: #64748b !important; }
        [data-admin-theme="dark"] input, [data-admin-theme="dark"] textarea, [data-admin-theme="dark"] select {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        [data-admin-theme="dark"] input::placeholder,
        [data-admin-theme="dark"] textarea::placeholder { color: #475569 !important; }
        [data-admin-theme="dark"] thead { background-color: #334155 !important; }
        [data-admin-theme="dark"] .divide-gray-50 > * { border-color: #1e293b !important; }
        [data-admin-theme="dark"] .hover\:bg-gray-50:hover  { background-color: #1e293b !important; }
        [data-admin-theme="dark"] .hover\:bg-gray-100:hover { background-color: #334155 !important; }
        [data-admin-theme="dark"] .shadow-sm  { box-shadow: 0 1px 3px 0 rgba(0,0,0,0.5) !important; }
        [data-admin-theme="dark"] .shadow-md  { box-shadow: 0 4px 12px 0 rgba(0,0,0,0.5) !important; }
        [data-admin-theme="dark"] .bg-green-50    { background-color: #052e16 !important; }
        [data-admin-theme="dark"] .border-green-200 { border-color: #166534 !important; }
        [data-admin-theme="dark"] .text-green-800 { color: #4ade80 !important; }
        [data-admin-theme="dark"] .bg-red-50      { background-color: #2d0a0a !important; }
        [data-admin-theme="dark"] .border-red-200 { border-color: #7f1d1d !important; }
        [data-admin-theme="dark"] .text-red-800   { color: #f87171 !important; }
        [data-admin-theme="dark"] .text-red-600   { color: #f87171 !important; }
        [data-admin-theme="dark"] .hover\:bg-red-50:hover { background-color: #2d0a0a !important; }
        [data-admin-theme="dark"] .bg-blue-100    { background-color: #1e3a5f !important; }
        [data-admin-theme="dark"] .font-mono      { color: #94a3b8 !important; }
    </style>
    @stack('styles')
</head>
<body class="h-full adm-bg" :class="darkMode ? '' : 'bg-slate-50'">

<script>
function adminApp() {
    return {
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        darkMode: localStorage.getItem('adminTheme') === 'dark',
        userMenuOpen: false,
        langMenuOpen: false,
        // Collapsible sidebar groups - default closed
        groups: {
            content:  localStorage.getItem('grp_content')  === 'true',
            blog:     localStorage.getItem('grp_blog')     === 'true',
            pages:    localStorage.getItem('grp_pages')    === 'true',
            crm:      localStorage.getItem('grp_crm')      === 'true',
            support:  localStorage.getItem('grp_support')  === 'true',
            settings: localStorage.getItem('grp_settings') === 'true',
            users:    localStorage.getItem('grp_users')    === 'true',
            reports:  localStorage.getItem('grp_reports')  === 'true',
        },
        toggleGroup(name) {
            this.groups[name] = !this.groups[name];
            localStorage.setItem('grp_' + name, this.groups[name]);
        },
        get isExpanded() { return !this.sidebarCollapsed || this.sidebarOpen; },
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('adminTheme', this.darkMode ? 'dark' : 'light');
        },
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed ? 'true' : 'false');
        },
        init() {
            @if($activeGroup)
            this.groups['{{ $activeGroup }}'] = true;
            @endif
        }
    }
}
</script>

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-cloak
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-black/60 lg:hidden"
     @click="sidebarOpen = false"></div>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- Sidebar                                                           --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<aside
    :class="{
        'w-64': isExpanded,
        'w-16': !isExpanded,
        '{{ $isRtl ? 'translate-x-full' : '-translate-x-full' }} lg:translate-x-0': !sidebarOpen,
        'translate-x-0': sidebarOpen
    }"
    class="fixed inset-y-0 {{ $isRtl ? 'right-0' : 'left-0' }} z-50 flex flex-col transition-all duration-300 ease-in-out lg:translate-x-0 overflow-hidden adm-sidebar"
    style="background:#ffffff; border-{{ $isRtl ? 'left' : 'right' }}:1px solid #e2e8f0; box-shadow:0 0 20px rgba(0,0,0,0.05);">

    {{-- ─── Header: Logo + collapse toggle ─── --}}
    <div class="flex items-center justify-between h-14 px-3 flex-shrink-0 border-b border-gray-100">
        <div class="flex items-center gap-2.5 overflow-hidden min-w-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: linear-gradient(135deg,#FF8528,#c45e00);">
                <svg style="width:18px;height:18px;" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div x-show="isExpanded" x-cloak class="overflow-hidden leading-none min-w-0">
                <p class="text-gray-900 font-bold text-sm truncate max-w-36">{{ \App\Models\Setting::get('site_name', config('app.name')) }}</p>
                <p class="text-xs font-medium" style="color:#FF8528;">لوحة التحكم</p>
            </div>
        </div>

        {{-- Desktop collapse button --}}
        <button @click="toggleSidebar()"
                class="hidden lg:flex w-7 h-7 items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all flex-shrink-0">
            <svg class="w-4 h-4 transition-transform duration-300"
                 :class="sidebarCollapsed ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="{{ $isRtl ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
            </svg>
        </button>

        {{-- Mobile close button --}}
        <button @click="sidebarOpen = false"
                class="lg:hidden w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ─── Navigation ─── --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden px-2 py-3 space-y-0.5 scrollbar-thin">

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" title="{{ __('admin.dashboard') }}"
           :class="!isExpanded && 'justify-center !px-2'"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
            </svg>
            <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.dashboard') }}</span>
        </a>

        {{-- Content group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('content')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>{{ __('admin.content_label') }}</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.content ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.content" x-collapse>
            <a href="{{ route('admin.content.index') }}" title="{{ __('admin.content') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.content') }}</span>
            </a>

            <a href="{{ route('admin.media.index') }}" title="{{ __('admin.media') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.media') }}</span>
            </a>

            {{-- Inbox --}}
            <a href="{{ route('admin.messages.index') }}" title="{{ __('admin.messages') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                <div class="relative flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    @php
                        try { $unread = \App\Models\Message::unreadCount(); } catch (\Throwable) { $unread = 0; }
                    @endphp
                    @if($unread > 0)
                        <span class="absolute -top-1 -end-1 w-4 h-4 rounded-full text-white text-[9px] font-bold flex items-center justify-center" style="background:#FF8528;">{{ $unread > 9 ? '9+' : $unread }}</span>
                    @endif
                </div>
                <span x-show="isExpanded" x-cloak class="truncate flex items-center gap-2">
                    {{ __('admin.messages') }}
                    @if($unread > 0)
                        <span class="ms-auto text-xs font-bold px-1.5 py-0.5 rounded-full text-white" style="background:#FF8528;">{{ $unread }}</span>
                    @endif
                </span>
            </a>

            {{-- Plans --}}
            <a href="{{ route('admin.plans.index') }}" title="{{ __('admin.plans') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.plans') }}</span>
            </a>

            {{-- Coupons --}}
            <a href="{{ route('admin.coupons.index') }}" title="الكوبونات"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">الكوبونات</span>
            </a>
        </div>

        {{-- Blog group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('blog')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>المدونة</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.blog ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.blog" x-collapse>
            <a href="{{ route('admin.posts.index') }}" title="المقالات"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">المقالات</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" title="التصنيفات"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">التصنيفات</span>
            </a>

            <a href="{{ url('/blog') }}" target="_blank" title="عرض المدونة"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">عرض المدونة</span>
            </a>
        </div>

        {{-- Pages & Navigation group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('pages')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>الصفحات والتنقل</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.pages ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.pages" x-collapse>
            <a href="{{ route('admin.pages.index') }}" title="الصفحات"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">الصفحات</span>
            </a>

            <a href="{{ route('admin.menus.index') }}" title="القوائم"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">القوائم</span>
            </a>

            <a href="{{ route('admin.redirects.index') }}" title="إعادة التوجيه"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.redirects.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">إعادة التوجيه</span>
            </a>
        </div>

        {{-- CRM group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('crm')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>العملاء والتسويق</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.crm ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.crm" x-collapse>
            <a href="{{ route('admin.leads.index') }}" title="العملاء المحتملون"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">العملاء المحتملون</span>
            </a>

            <a href="{{ route('admin.subscribers.index') }}" title="المشتركون"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">المشتركون</span>
            </a>

            <a href="{{ route('admin.campaigns.index') }}" title="الحملات البريدية"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.campaigns.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">الحملات البريدية</span>
            </a>
        </div>

        {{-- Support group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('support')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>الدعم الفني</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.support ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.support" x-collapse>
            <a href="{{ route('admin.tickets.index') }}" title="تذاكر الدعم"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">تذاكر الدعم</span>
            </a>
        </div>

        {{-- Settings group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('settings')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>{{ __('admin.settings_label') }}</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.settings ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.settings" x-collapse>
            <a href="{{ route('admin.settings.general') }}" title="{{ __('admin.general') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.settings.general*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.general') }}</span>
            </a>

            <a href="{{ route('admin.settings.appearance') }}" title="{{ __('admin.appearance') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.settings.appearance*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.appearance') }}</span>
            </a>

            <a href="{{ route('admin.settings.seo') }}" title="{{ __('admin.seo') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.settings.seo*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.seo') }}</span>
            </a>

            <a href="{{ route('admin.settings.smtp') }}" title="{{ __('admin.smtp') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.settings.smtp*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.smtp') }}</span>
            </a>

            <a href="{{ route('admin.languages.index') }}" title="{{ __('admin.languages') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.languages') }}</span>
            </a>
        </div>

        {{-- Users & Access group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('users')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>{{ __('admin.users_access_label') }}</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.users ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.users" x-collapse>
            <a href="{{ route('admin.users.index') }}" title="{{ __('admin.users') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.users') }}</span>
            </a>

            <a href="{{ route('admin.roles.index') }}" title="{{ __('admin.roles') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.roles') }}</span>
            </a>

            <a href="{{ route('admin.api-keys.index') }}" title="مفاتيح API"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.api-keys.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">مفاتيح API</span>
            </a>

            <a href="{{ route('admin.two-factor.index') }}" title="المصادقة الثنائية"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.two-factor.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">المصادقة الثنائية</span>
            </a>
        </div>

        {{-- Reporting group --}}
        <button x-show="isExpanded" x-cloak type="button" @click="toggleGroup('reports')"
                class="sidebar-group-label w-full flex items-center justify-between hover:text-gray-300 transition-colors">
            <span>{{ __('admin.reporting_label') }}</span>
            <svg class="w-3 h-3 transition-transform duration-200" :class="groups.reports ? '' : '-rotate-90'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="!isExpanded" class="my-1 border-t border-gray-100"></div>

        <div x-show="!isExpanded || groups.reports" x-collapse>
            <a href="{{ route('admin.analytics.index') }}" title="{{ __('admin.analytics') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.analytics') }}</span>
            </a>

            <a href="{{ route('admin.activity.index') }}" title="{{ __('admin.activity') }}"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.activity') }}</span>
            </a>

            <a href="{{ route('admin.system.health') }}" title="صحة النظام"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">صحة النظام</span>
            </a>

            <a href="{{ route('admin.backups.index') }}" title="النسخ الاحتياطية"
               :class="!isExpanded && 'justify-center !px-2'"
               class="sidebar-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8 1.79 8-4M4 7c0-2.21 3.582 4 8 4s8-1.79 8-4"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">النسخ الاحتياطية</span>
            </a>
        </div>

    </nav>

    {{-- ─── Sign Out ─── --}}
    <div class="px-2 py-3 flex-shrink-0 border-t border-gray-100">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" title="{{ __('admin.sign_out') }}"
                    :class="!isExpanded && 'justify-center !px-2'"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:text-red-500 hover:bg-red-50 transition-all text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.sign_out') }}</span>
            </button>
        </form>
    </div>
</aside>

{{-- ══════════════════════════════════════════════════════════════════ --}}
{{-- Main Wrapper                                                      --}}
{{-- ══════════════════════════════════════════════════════════════════ --}}
<div class="flex flex-col min-h-screen transition-all duration-300"
     :class="sidebarCollapsed ? '{{ $isRtl ? 'lg:pr-16' : 'lg:pl-16' }}' : '{{ $isRtl ? 'lg:pr-64' : 'lg:pl-64' }}'">

    {{-- Top Bar --}}
    <header class="sticky top-0 z-30 border-b adm-header"
            style="background:#ffffff; border-color:#e2e8f0; box-shadow:0 1px 0 #f1f5f9;">
        <div class="flex items-center justify-between px-4 sm:px-6 h-14">

            {{-- Left: hamburger + page title --}}
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden text-gray-500 hover:text-gray-800 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-sm font-semibold text-gray-800">
                    @yield('page-title', __('admin.dashboard'))
                </h1>
            </div>

            {{-- Right: Notifications + Dark mode + Language + User --}}
            <div class="flex items-center gap-1.5">

                {{-- Notifications Bell --}}
                @php
                    try { $unreadNotifCount = auth()->user() ? auth()->user()->unreadNotifications()->count() : 0; }
                    catch (\Throwable) { $unreadNotifCount = 0; }
                @endphp
                <div class="relative" x-data="{notifOpen:false}" @click.outside="notifOpen=false">
                    <button @click="notifOpen=!notifOpen"
                            class="relative w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadNotifCount > 0)
                        <span class="absolute top-1 end-1 w-4 h-4 rounded-full text-white text-[9px] font-bold flex items-center justify-center" style="background:#FF8528;">
                            {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                        </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-80 rounded-2xl shadow-xl border z-50 overflow-hidden adm-dropdown"
                         style="background:#ffffff; border-color:#e2e8f0;">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <p class="text-gray-800 font-semibold text-sm">{{ __('admin.notifications') }}</p>
                            @if($unreadNotifCount > 0)
                            <span class="text-xs px-2 py-0.5 rounded-full text-white font-bold" style="background:#FF8528;">
                                {{ $unreadNotifCount }} {{ __('admin.new') }}
                            </span>
                            @endif
                        </div>
                        @php
                            try { $recentNotifs = auth()->user() ? auth()->user()->notifications()->latest()->limit(5)->get() : collect(); }
                            catch (\Throwable) { $recentNotifs = collect(); }
                        @endphp
                        @if($recentNotifs->isEmpty())
                        <div class="px-4 py-8 text-center">
                            <p class="text-gray-400 text-sm">{{ __('admin.no_notifications') }}</p>
                        </div>
                        @else
                        <ul>
                            @foreach($recentNotifs as $notif)
                            @php $nData = $notif->data; @endphp
                            <li class="px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ $notif->read_at ? '' : 'bg-orange-50/60' }}">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                         style="{{ $notif->read_at ? 'background:#F8FAFC;' : 'background:#FFF4EA;' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="{{ $notif->read_at ? '#94a3b8' : '#FF8528' }}" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800 font-medium truncate">{{ $nData['title'] ?? '' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $nData['body'] ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(! $notif->read_at)
                                        <span class="w-2 h-2 rounded-full flex-shrink-0 mt-1.5" style="background:#FF8528;"></span>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        <a href="{{ route('admin.notifications.index') }}"
                           class="block px-4 py-2.5 text-center text-xs font-semibold transition-colors hover:bg-gray-50 border-t border-gray-100"
                           style="color:#FF8528;">
                            {{ __('admin.view_all_notifications') }} &rarr;
                        </a>
                    </div>
                </div>

                {{-- Dark Mode Toggle --}}
                <button @click="toggleDark()"
                        :title="darkMode ? '{{ __('admin.light_mode') }}' : '{{ __('admin.dark_mode') }}'"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                    <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="4" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                    </svg>
                </button>

                {{-- Language Selector --}}
                <div class="relative" @click.outside="langMenuOpen = false">
                    <button @click="langMenuOpen = !langMenuOpen"
                            class="flex items-center gap-1 px-2 py-1.5 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 text-xs font-semibold transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3a13.5 13.5 0 0 1 0 18M12 3a13.5 13.5 0 0 0 0 18"/>
                        </svg>
                        {{ strtoupper(session('admin_lang', 'en')) }}
                    </button>
                    <div x-show="langMenuOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-44 rounded-xl shadow-xl border py-1 z-50 adm-dropdown"
                         style="background:#ffffff; border-color:#e2e8f0;">
                        @foreach(['en' => ['EN','English'],'ar' => ['AR','العربية'],'nl' => ['NL','Nederlands'],'de' => ['DE','Deutsch']] as $code => [$abbr, $label])
                            @php $active = session('admin_lang','en') === $code; @endphp
                            <form method="POST" action="{{ route('admin.language') }}">
                                @csrf
                                <input type="hidden" name="lang" value="{{ $code }}">
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-gray-50 transition-colors {{ $active ? 'text-gray-900 font-semibold' : 'text-gray-600' }}">
                                    <span class="w-7 h-5 rounded text-[10px] font-bold flex items-center justify-center flex-shrink-0"
                                          style="{{ $active ? 'background:#FF8528;color:#fff;' : 'background:#F1F5F9;color:#64748B;' }}">
                                        {{ $abbr }}
                                    </span>
                                    <span>{{ $label }}</span>
                                    @if($active)
                                        <svg class="w-3 h-3 ms-auto flex-shrink-0" fill="none" stroke="#FF8528" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>

                {{-- User Menu --}}
                <div class="relative" @click.outside="userMenuOpen = false">
                    <button @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-gray-100 transition-colors focus:outline-none">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                             style="background: linear-gradient(135deg,#FF8528,#c45e00);">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-xs text-gray-700 font-medium max-w-24 truncate">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </span>
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="userMenuOpen" x-cloak
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-52 rounded-xl shadow-xl border py-1 z-50 adm-dropdown"
                         style="background:#ffffff; border-color:#e2e8f0;">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-gray-800 text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('admin.profile') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors mt-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('admin.my_profile') }}
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                {{ __('admin.sign_out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1 px-4 sm:px-6 py-6">

        {{-- Flash: success --}}
        @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-cloak
                 x-init="setTimeout(()=>show=false,5000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mb-5 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm flex-1">{{ session('success') }}</p>
                <button @click="show=false" class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Flash: error --}}
        @if(session('error'))
            <div x-data="{show:true}" x-show="show" x-cloak
                 x-init="setTimeout(()=>show=false,6000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm flex-1">{{ session('error') }}</p>
                <button @click="show=false" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="px-6 py-3 border-t adm-footer" style="background:#ffffff; border-color:#e2e8f0;">
        <p class="text-xs text-gray-400 text-center">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', config('app.name')) }}</p>
    </footer>
</div>

@stack('scripts')
</body>
</html>
