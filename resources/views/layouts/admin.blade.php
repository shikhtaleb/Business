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
    <title>@yield('title', __('admin.dashboard')) — Retont Business</title>

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

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @php $isRtl = app()->getLocale() === 'ar'; @endphp

    <style>
        [x-cloak] { display: none !important; }

        /* ── Sidebar nav items ── */
        .sidebar-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.625rem;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s, background-color 0.15s;
            font-size: 0.8125rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar-link:hover {
            color: #e2e8f0;
            background-color: rgba(255,255,255,0.05);
        }
        .sidebar-link.active {
            color: #ffffff;
            background-color: rgba(255,133,40,0.12);
        }
        .sidebar-link.active svg { color: #FF8528 !important; stroke: #FF8528 !important; }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            {{ $isRtl ? 'right' : 'left' }}: 0;
            top: 20%; height: 60%;
            width: 3px;
            border-radius: {{ $isRtl ? '4px 0 0 4px' : '0 4px 4px 0' }};
            background-color: #FF8528;
        }
        .sidebar-group-label {
            display: block;
            padding: 0.5rem 0.75rem 0.2rem;
            font-size: 0.65rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.5rem;
        }

        /* ── Dark mode overrides ── */
        [data-admin-theme="dark"] .adm-bg   { background-color: #0b1120 !important; }
        [data-admin-theme="dark"] .adm-header {
            background-color: #1e293b !important;
            border-color: #1e293b !important;
        }
        [data-admin-theme="dark"] .adm-footer {
            background-color: #1e293b !important;
            border-color: #1e293b !important;
        }
        [data-admin-theme="dark"] .bg-white      { background-color: #1e293b !important; }
        [data-admin-theme="dark"] .bg-gray-100   { background-color: #0b1120 !important; }
        [data-admin-theme="dark"] .bg-gray-50    { background-color: #1e293b !important; }
        [data-admin-theme="dark"] .border-gray-100 { border-color: #334155 !important; }
        [data-admin-theme="dark"] .border-gray-200 { border-color: #334155 !important; }
        [data-admin-theme="dark"] .border-gray-300 { border-color: #334155 !important; }
        [data-admin-theme="dark"] .text-gray-900 { color: #f8fafc !important; }
        [data-admin-theme="dark"] .text-gray-800 { color: #f1f5f9 !important; }
        [data-admin-theme="dark"] .text-gray-700 { color: #e2e8f0 !important; }
        [data-admin-theme="dark"] .text-gray-600 { color: #cbd5e1 !important; }
        [data-admin-theme="dark"] .text-gray-500 { color: #94a3b8 !important; }
        [data-admin-theme="dark"] .text-gray-400 { color: #64748b !important; }
        [data-admin-theme="dark"] input,
        [data-admin-theme="dark"] textarea,
        [data-admin-theme="dark"] select {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        [data-admin-theme="dark"] input::placeholder,
        [data-admin-theme="dark"] textarea::placeholder { color: #64748b !important; }
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
        [data-admin-theme="dark"] .adm-topbar-text { color: #94a3b8 !important; }
    </style>
</head>
<body class="h-full adm-bg" :class="darkMode ? 'bg-slate-950' : 'bg-gray-100'">

<script>
function adminApp() {
    return {
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        darkMode: localStorage.getItem('adminTheme') === 'dark',
        userMenuOpen: false,
        langMenuOpen: false,
        get isExpanded() { return !this.sidebarCollapsed || this.sidebarOpen; },
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('adminTheme', this.darkMode ? 'dark' : 'light');
        },
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed ? 'true' : 'false');
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
    class="fixed inset-y-0 {{ $isRtl ? 'right-0' : 'left-0' }} z-50 flex flex-col transition-all duration-300 ease-in-out lg:translate-x-0 overflow-hidden"
    style="background: #0f172a;">

    {{-- ─── Header: Logo + collapse toggle ─── --}}
    <div class="flex items-center justify-between h-16 px-3 flex-shrink-0 border-b border-white/5">
        <div class="flex items-center gap-2.5 overflow-hidden min-w-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background: linear-gradient(135deg,#FF8528,#c45e00);">
                <svg style="width:18px;height:18px;" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div x-show="isExpanded" x-cloak class="overflow-hidden leading-none">
                <p class="text-white font-bold text-sm">Retont</p>
                <p class="text-xs font-medium" style="color:#FF8528;">Business</p>
            </div>
        </div>

        {{-- Desktop collapse button --}}
        <button @click="toggleSidebar()"
                class="hidden lg:flex w-7 h-7 items-center justify-center rounded-lg text-slate-500 hover:text-white hover:bg-white/8 transition-all flex-shrink-0"
                style="--tw-bg-opacity:1;">
            <svg class="w-4 h-4 transition-transform duration-300"
                 :class="sidebarCollapsed ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="{{ $isRtl ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
            </svg>
        </button>

        {{-- Mobile close button --}}
        <button @click="sidebarOpen = false"
                class="lg:hidden w-7 h-7 flex items-center justify-center rounded-lg text-slate-500 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ─── User Info ─── --}}
    <div class="px-3 py-3 flex-shrink-0 border-b border-white/5">
        <div class="flex items-center gap-2.5">
            <div class="relative flex-shrink-0">
                <a href="{{ route('admin.profile') }}"
                   class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                   style="background: linear-gradient(135deg,#FF8528,#c45e00);">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </a>
                <span class="absolute -bottom-0.5 {{ $isRtl ? '-left-0.5' : '-right-0.5' }} w-2.5 h-2.5 bg-emerald-400 rounded-full border-2"
                      style="border-color:#0f172a;"></span>
            </div>
            <div x-show="isExpanded" x-cloak class="overflow-hidden min-w-0 flex-1">
                <p class="text-white text-xs font-semibold truncate leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-emerald-400 text-xs leading-tight mt-0.5">● Online</p>
            </div>
        </div>
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
        <div x-show="isExpanded" x-cloak class="sidebar-group-label">{{ __('admin.content_label') }}</div>
        <div x-show="!isExpanded" class="my-1 border-t border-white/5"></div>

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

        {{-- Settings group --}}
        <div x-show="isExpanded" x-cloak class="sidebar-group-label">{{ __('admin.settings_label') }}</div>
        <div x-show="!isExpanded" class="my-1 border-t border-white/5"></div>

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

        {{-- Users & Access group --}}
        <div x-show="isExpanded" x-cloak class="sidebar-group-label">{{ __('admin.users_access_label') }}</div>
        <div x-show="!isExpanded" class="my-1 border-t border-white/5"></div>

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

        {{-- Reporting group --}}
        <div x-show="isExpanded" x-cloak class="sidebar-group-label">{{ __('admin.reporting_label') }}</div>
        <div x-show="!isExpanded" class="my-1 border-t border-white/5"></div>

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

        {{-- Site --}}
        <div x-show="isExpanded" x-cloak class="sidebar-group-label">{{ __('admin.site_label') }}</div>
        <div x-show="!isExpanded" class="my-1 border-t border-white/5"></div>

        <a href="{{ url('/') }}" target="_blank" title="{{ __('admin.view_site') }}"
           :class="!isExpanded && 'justify-center !px-2'"
           class="sidebar-link">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span x-show="isExpanded" x-cloak class="truncate">{{ __('admin.view_site') }}</span>
        </a>
    </nav>

    {{-- ─── Sign Out ─── --}}
    <div class="px-2 py-3 flex-shrink-0 border-t border-white/5">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" title="{{ __('admin.sign_out') }}"
                    :class="!isExpanded && 'justify-center !px-2'"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-all text-sm font-medium">
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
    <header class="sticky top-0 z-30 border-b shadow-sm adm-header"
            style="background:#1e293b; border-color:#1e293b;">
        <div class="flex items-center justify-between px-4 sm:px-6 h-14">

            {{-- Left: hamburger + page title --}}
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden text-slate-400 hover:text-white focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-sm font-semibold text-white/90 adm-topbar-text">
                    @yield('page-title', __('admin.dashboard'))
                </h1>
            </div>

            {{-- Right: Dark mode + Language + User --}}
            <div class="flex items-center gap-1.5">

                {{-- Dark Mode Toggle --}}
                <button @click="toggleDark()"
                        :title="darkMode ? '{{ __('admin.light_mode') }}' : '{{ __('admin.dark_mode') }}'"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors">
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
                            class="flex items-center gap-1 px-2 py-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 text-xs font-semibold transition-colors">
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
                         class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-44 rounded-xl shadow-xl border py-1 z-50"
                         style="background:#1e293b; border-color:#334155;">
                        @foreach(['en' => ['🇬🇧','English'],'ar' => ['🇸🇦','العربية'],'nl' => ['🇳🇱','Nederlands'],'de' => ['🇩🇪','Deutsch']] as $code => [$flag, $label])
                            <form method="POST" action="{{ route('admin.language') }}">
                                @csrf
                                <input type="hidden" name="lang" value="{{ $code }}">
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-white/5 transition-colors {{ session('admin_lang','en') === $code ? 'text-white font-semibold' : 'text-slate-400' }}">
                                    <span>{{ $flag }}</span>
                                    <span>{{ $label }}</span>
                                    @if(session('admin_lang','en') === $code)
                                        <svg class="w-3 h-3 ms-auto" fill="none" stroke="#FF8528" viewBox="0 0 24 24">
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
                            class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-white/10 transition-colors focus:outline-none">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                             style="background: linear-gradient(135deg,#FF8528,#c45e00);">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="hidden sm:block text-xs text-slate-300 font-medium max-w-24 truncate">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </span>
                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                         class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-52 rounded-xl shadow-xl border py-1 z-50"
                         style="background:#1e293b; border-color:#334155;">
                        <div class="px-4 py-2.5 border-b" style="border-color:#334155;">
                            <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-slate-400 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('admin.profile') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-300 hover:text-white hover:bg-white/5 transition-colors mt-1">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ __('admin.my_profile') }}
                        </a>
                        <div class="border-t my-1" style="border-color:#334155;"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
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
    <footer class="px-6 py-3 border-t adm-footer" style="background:#1e293b; border-color:#1e293b;">
        <p class="text-xs text-slate-600 text-center">&copy; {{ date('Y') }} Retont Business</p>
    </footer>
</div>

@stack('scripts')
</body>
</html>
