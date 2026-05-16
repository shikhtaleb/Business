@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    {{-- ── Stat Cards ─────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Views Today --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Views Today</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background-color:#FFF4EA;">
                    <svg class="w-5 h-5" style="color:#FF8528;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['views_today'] ?? 0) }}</p>
            <p class="mt-1 text-xs text-gray-400">page views</p>
        </div>

        {{-- Views This Week --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">This Week</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-blue-50">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['views_week'] ?? 0) }}</p>
            <p class="mt-1 text-xs text-gray-400">page views</p>
        </div>

        {{-- Views This Month --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">This Month</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-purple-50">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['views_month'] ?? 0) }}</p>
            <p class="mt-1 text-xs text-gray-400">page views</p>
        </div>

        {{-- Unique Today --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Unique Today</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center bg-green-50">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['unique_today'] ?? 0) }}</p>
            <p class="mt-1 text-xs text-gray-400">unique visitors</p>
        </div>
    </div>

    {{-- ── Module Stats ────────────────────────────────────────────────────────── --}}
    @if(!empty($extStats))
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3 mb-6">
        @php
        $moduleCards = [
            'unread_messages'  => ['رسائل جديدة',       '#3B82F6', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'admin.messages.index'],
            'new_leads'        => ['عملاء محتملون',     '#8B5CF6', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'admin.leads.index'],
            'subscribers'      => ['مشتركون نشطون',     '#10B981', 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'admin.subscribers.index'],
            'published_posts'  => ['مقالات منشورة',     '#F59E0B', 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15', 'admin.posts.index'],
            'open_tickets'     => ['تذاكر مفتوحة',      '#EF4444', 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'admin.tickets.index'],
            'active_plans'     => ['خطط نشطة',          '#FF8528', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'admin.plans.index'],
            'sent_campaigns'   => ['حملات أُرسلت',      '#06B6D4', 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'admin.campaigns.index'],
            'active_coupons'   => ['كوبونات نشطة',      '#EC4899', 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'admin.coupons.index'],
        ];
        @endphp
        @foreach($moduleCards as $key => [$label, $color, $path, $route])
            @if(isset($extStats[$key]))
            <a href="{{ route($route) }}"
               class="bg-white rounded-2xl border border-gray-100 p-4 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-4 h-4 flex-shrink-0 opacity-70" style="color:{{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($extStats[$key]) }}</div>
                <div class="text-xs text-gray-500 mt-0.5 leading-tight">{{ $label }}</div>
            </a>
            @endif
        @endforeach
    </div>
    @endif

    {{-- ── Chart ───────────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-gray-800">Traffic — Last 30 Days</h2>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full" style="background:#FF8528;"></span>
                    Total Views
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span>
                    Unique Visitors
                </span>
            </div>
        </div>
        <div class="relative" style="height:280px;">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

    {{-- ── Bottom row: Recent Activity + Quick Links ─────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Activity --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Recent Activity</h2>
                <a href="{{ route('admin.activity.index') }}"
                   class="text-xs font-medium hover:underline" style="color:#FF8528;">
                    View all →
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($recentActivity ?? [] as $activity)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background-color:#FFF4EA;">
                            <svg class="w-4 h-4" style="color:#FF8528;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800 leading-snug">{{ $activity->description }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                @if($activity->log_name)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        {{ $activity->log_name }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-400">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <svg class="mx-auto w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm text-gray-400">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">روابط سريعة</h2>
            </div>
            <div class="p-4 grid grid-cols-2 gap-2">
                @php
                $quickLinks = [
                    [route('admin.posts.create'),        '#FFF4EA', 'color:#FF8528;', 'M12 4v16m8-8H4',                         'مقال جديد'],
                    [route('admin.pages.index'),         '#EFF6FF', 'color:#3B82F6;', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'الصفحات'],
                    [route('admin.leads.index'),         '#F5F3FF', 'color:#8B5CF6;', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'العملاء'],
                    [route('admin.tickets.index'),       '#FEF2F2', 'color:#EF4444;', 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'تذاكر الدعم'],
                    [route('admin.campaigns.create'),    '#ECFDF5', 'color:#10B981;', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'حملة جديدة'],
                    [route('admin.media.index'),         '#FFF7ED', 'color:#F59E0B;', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'الوسائط'],
                    [route('admin.backups.index'),       '#F0FDF4', 'color:#22C55E;', 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8 1.79 8-4M4 7c0-2.21 3.582 4 8 4s8-1.79 8-4', 'النسخ الاحتياطية'],
                    [route('admin.system.health'),       '#F9FAFB', 'color:#6B7280;', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'صحة النظام'],
                ];
                @endphp
                @foreach($quickLinks as [$href, $bg, $color, $path, $label])
                <a href="{{ $href }}"
                   class="flex flex-col items-center gap-1.5 p-3 rounded-xl border border-gray-100 hover:border-orange-200 hover:bg-orange-50 transition-colors group">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background:{{ $bg }};">
                        <svg class="w-5 h-5" style="{{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600 text-center leading-tight">{{ $label }}</span>
                </a>
                @endforeach

                {{-- Dummy placeholder for grid --}}
                <a href="{{ route('admin.roles.index') }}"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl border border-gray-100 hover:border-orange-200 hover:bg-orange-50 transition-colors group">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform bg-red-50">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600">الأدوار</span>
                </a>

            </div>
        </div>
    </div>

    {{-- ── Recent Messages ──────────────────────────────────────────────────────── --}}
    @if(!empty($recentMessages) && count($recentMessages) > 0)
    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-800">آخر الرسائل</h2>
            <a href="{{ route('admin.messages.index') }}" class="text-xs font-medium hover:underline" style="color:#FF8528;">عرض الكل →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($recentMessages as $msg)
            <div class="px-5 py-3.5 flex items-start gap-3 hover:bg-gray-50">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-white" style="background:#FF8528;">
                    {{ strtoupper(substr($msg->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-medium text-gray-800">{{ $msg->name }}</span>
                        @if($msg->status === 'unread')
                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:#FF8528;"></span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 truncate mt-0.5">{{ Str::limit($msg->body, 60) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $msg->created_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('admin.messages.show', $msg) }}" class="text-xs text-gray-400 hover:text-gray-600 flex-shrink-0 mt-1">عرض</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const days    = @json($chartDays ?? []);
    const totals  = @json($chartTotals ?? []);
    const unique  = @json($chartUnique ?? []);

    const ctx = document.getElementById('trafficChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [
                {
                    label: 'Total Views',
                    data: totals,
                    borderColor: '#FF8528',
                    backgroundColor: 'rgba(255,133,40,0.10)',
                    borderWidth: 2,
                    pointBackgroundColor: '#FF8528',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Unique Visitors',
                    data: unique,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: '#3B82F6',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1F2937',
                    titleColor: '#F9FAFB',
                    bodyColor: '#D1D5DB',
                    cornerRadius: 8,
                    padding: 10,
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#9CA3AF', font: { size: 11 } },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6' },
                    ticks: { color: '#9CA3AF', font: { size: 11 } },
                },
            },
            interaction: { mode: 'nearest', axis: 'x', intersect: false },
        },
    });
})();
</script>
@endpush
