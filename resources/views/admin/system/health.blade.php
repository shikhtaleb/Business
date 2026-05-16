@extends('layouts.admin')
@section('title', 'صحة النظام')
@section('page-title', 'صحة النظام')

@section('content')
<div class="space-y-5">

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-green-50 border border-green-100 rounded-2xl p-4 text-center">
            <div class="text-3xl font-bold text-green-600">{{ $summary['ok'] }}</div>
            <div class="text-sm text-green-700 mt-1">سليم</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-4 text-center">
            <div class="text-3xl font-bold text-yellow-600">{{ $summary['warning'] }}</div>
            <div class="text-sm text-yellow-700 mt-1">تحذير</div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-4 text-center">
            <div class="text-3xl font-bold text-red-600">{{ $summary['error'] }}</div>
            <div class="text-sm text-red-700 mt-1">خطأ</div>
        </div>
    </div>

    {{-- Info --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 text-sm text-blue-700">
        آخر فحص: {{ now()->format('Y-m-d H:i:s') }} —
        <a href="{{ route('admin.system.health') }}" class="underline font-medium">إعادة الفحص</a>
    </div>

    {{-- Checks --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">نتائج الفحص</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($checks as $check)
                @php
                    $iconColor = match($check['status']) {
                        'ok'      => 'text-green-500',
                        'warning' => 'text-yellow-500',
                        default   => 'text-red-500',
                    };
                    $icon = match($check['status']) {
                        'ok'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                        default   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    };
                @endphp
                <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ $iconColor }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $icon !!}
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-gray-800">{{ $check['label'] }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $check['note'] }}</div>
                        </div>
                    </div>
                    <div class="text-sm font-mono text-gray-600">{{ $check['value'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.settings.cache.clear') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    مسح الكاش
                </button>
            </form>
            <a href="{{ route('admin.backups.index') }}"
               class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                إدارة النسخ الاحتياطية
            </a>
            <a href="{{ route('admin.activity.index') }}"
               class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                سجل النشاط
            </a>
        </div>
    </div>
</div>
@endsection
