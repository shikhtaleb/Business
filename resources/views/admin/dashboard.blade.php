@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">مرحباً، {{ auth()->user()->name ?? 'مدير النظام' }}</h2>
            <p class="text-sm text-gray-400 mt-0.5">{{ now()->translatedFormat('l، d F Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.posts.create') }}"
               class="hidden sm:flex items-center gap-2 px-3.5 py-2 rounded-xl text-white text-sm font-medium transition-colors"
               style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                مقال جديد
            </a>
            <a href="{{ url('/') }}" target="_blank"
               class="hidden sm:flex items-center gap-2 px-3.5 py-2 rounded-xl border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                عرض الموقع
            </a>
        </div>
    </div>

    {{-- Visit Stats --}}
    @php
    $statItems = [
        ['زيارات اليوم',      $stats['views_today']  ?? 0, '#FF8528', '#FFF4EA', 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
        ['هذا الأسبوع',       $stats['views_week']   ?? 0, '#3B82F6', '#EFF6FF', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['هذا الشهر',         $stats['views_month']  ?? 0, '#8B5CF6', '#F5F3FF', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ['زوار فريدون اليوم', $stats['unique_today'] ?? 0, '#10B981', '#ECFDF5', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
    ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        @foreach($statItems as [$label, $value, $color, $bg, $path])
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500">{{ $label }}</span>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:{{ $bg }};">
                    <svg class="w-4 h-4" style="color:{{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($value) }}</p>
        </div>
        @endforeach
    </div>

    {{-- Key Module Counters --}}
    @if(!empty($extStats))
    @php
    $keyModules = [
        'unread_messages' => ['رسائل جديدة',   '#3B82F6', 'admin.messages.index', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        'new_leads'       => ['عملاء محتملون', '#8B5CF6', 'admin.leads.index',    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        'open_tickets'    => ['تذاكر مفتوحة',  '#EF4444', 'admin.tickets.index',  'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
        'published_posts' => ['مقالات منشورة', '#10B981', 'admin.posts.index',    'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15'],
    ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        @foreach($keyModules as $key => [$label, $color, $route, $path])
        @if(isset($extStats[$key]))
        <a href="{{ route($route) }}"
           class="bg-white rounded-2xl border border-gray-100 p-4 hover:shadow-md hover:border-gray-200 transition-all flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 opacity-90 group-hover:opacity-100 transition-opacity"
                 style="background:{{ $color }}18;">
                <svg class="w-4 h-4" style="color:{{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xl font-bold text-gray-900">{{ number_format($extStats[$key]) }}</p>
                <p class="text-xs text-gray-500 truncate">{{ $label }}</p>
            </div>
        </a>
        @endif
        @endforeach
    </div>
    @endif

    {{-- Traffic Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-800">الزيارات — آخر 30 يوماً</h3>
            <div class="flex items-center gap-4 text-xs text-gray-400">
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background:#FF8528;"></span>
                    إجمالي
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    فريدون
                </span>
            </div>
        </div>
        <div class="relative" style="height:240px;">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

    {{-- Activity + Messages --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Recent Activity --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">آخر الأنشطة</h3>
                <a href="{{ route('admin.activity.index') }}"
                   class="text-xs font-medium hover:underline" style="color:#FF8528;">عرض الكل</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($recentActivity ?? [] as $activity)
                <div class="px-5 py-3 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                         style="background:#FFF4EA;">
                        <svg class="w-3.5 h-3.5" style="color:#FF8528;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 leading-snug">{{ $activity->description }}</p>
                        <span class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-gray-400">لا توجد أنشطة حديثة</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Messages --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">آخر الرسائل</h3>
                <a href="{{ route('admin.messages.index') }}"
                   class="text-xs font-medium hover:underline" style="color:#FF8528;">عرض الكل</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse ($recentMessages ?? [] as $msg)
                <div class="px-5 py-3 flex items-start gap-3 hover:bg-gray-50 transition-colors">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold text-white"
                         style="background:#FF8528;">
                        {{ strtoupper(substr($msg->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-medium text-gray-800 truncate">{{ $msg->name }}</span>
                            @if($msg->status === 'unread')
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#FF8528;"></span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ Str::limit($msg->body, 55) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $msg->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.messages.show', $msg) }}"
                       class="text-xs text-gray-400 hover:text-gray-600 flex-shrink-0 mt-1 transition-colors">عرض</a>
                </div>
                @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-gray-400">لا توجد رسائل</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const days   = @json($chartDays ?? []);
    const totals = @json($chartTotals ?? []);
    const unique = @json($chartUnique ?? []);

    const ctx = document.getElementById('trafficChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [
                {
                    label: 'إجمالي الزيارات',
                    data: totals,
                    borderColor: '#FF8528',
                    backgroundColor: 'rgba(255,133,40,0.08)',
                    borderWidth: 2,
                    pointBackgroundColor: '#FF8528',
                    pointRadius: 2,
                    pointHoverRadius: 4,
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'زوار فريدون',
                    data: unique,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.06)',
                    borderWidth: 2,
                    pointBackgroundColor: '#3B82F6',
                    pointRadius: 2,
                    pointHoverRadius: 4,
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
