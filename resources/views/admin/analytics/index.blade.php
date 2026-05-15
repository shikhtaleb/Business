@extends('layouts.admin')

@section('title', 'Analytics')
@section('page-title', 'Analytics')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels  = @json(array_column($dailyData, 'label'));
    const totals  = @json(array_column($dailyData, 'total'));
    const uniques = @json(array_column($dailyData, 'unique_visitors'));

    const ctx = document.getElementById('visitsChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Views',
                    data: totals,
                    borderColor: '#FF8528',
                    backgroundColor: 'rgba(255,133,40,0.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#FF8528',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Unique Visitors',
                    data: uniques,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.06)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#3B82F6',
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Filter --}}
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-600">Period:</span>
        @foreach(['7' => '7 Days', '30' => '30 Days', '90' => '90 Days'] as $d => $label)
            <a href="{{ route('admin.analytics.index', ['filter' => $d]) }}"
               class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                      {{ ($filter ?? '30') === $d ? 'text-white border-orange-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}"
               @if(($filter ?? '30') === $d) style="background-color:#FF8528;" @endif>
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @php
            $statCards = [
                ['label' => 'Views Today',   'value' => $stats['views_today'],  'color' => '#FF8528', 'bg' => '#FFF4EA'],
                ['label' => 'Views Week',    'value' => $stats['views_week'],   'color' => '#3B82F6', 'bg' => '#EFF6FF'],
                ['label' => 'Views Month',   'value' => $stats['views_month'],  'color' => '#10B981', 'bg' => '#ECFDF5'],
                ['label' => 'Unique Today',  'value' => $stats['unique_today'], 'color' => '#8B5CF6', 'bg' => '#F5F3FF'],
                ['label' => 'Unique Month',  'value' => $stats['unique_month'], 'color' => '#F59E0B', 'bg' => '#FFFBEB'],
            ];
        @endphp
        @foreach($statCards as $card)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:{{ $card['color'] }}">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($card['value']) }}</p>
            </div>
        @endforeach
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Daily Visits — Last {{ $filter ?? 30 }} Days</h2>
        <div class="relative" style="height: 280px;">
            <canvas id="visitsChart"></canvas>
        </div>
    </div>

    {{-- Top Pages + Referrers --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Top Pages --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-800">Top Pages</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topPages as $page)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <span class="text-sm text-gray-700 font-mono truncate max-w-xs" title="{{ $page->page }}">{{ $page->page }}</span>
                        <span class="text-sm font-semibold text-gray-900 ml-4 flex-shrink-0">{{ number_format($page->total) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-6 text-center text-sm text-gray-400">No data</div>
                @endforelse
            </div>
        </div>

        {{-- Top Referrers --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-gray-800">Top Referrers</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topReferrers as $referrer)
                    <div class="px-5 py-3 flex items-center justify-between gap-3">
                        <span class="text-sm text-gray-600 truncate" title="{{ $referrer->referrer }}">
                            {{ parse_url($referrer->referrer, PHP_URL_HOST) ?: $referrer->referrer }}
                        </span>
                        <span class="text-sm font-semibold text-gray-900 flex-shrink-0">{{ number_format($referrer->total) }}</span>
                    </div>
                @empty
                    <div class="px-5 py-6 text-center text-sm text-gray-400">No referrer data</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
