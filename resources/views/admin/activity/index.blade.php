@extends('layouts.admin')

@section('title', 'سجل النشاط')
@section('page-title', 'سجل النشاط')

@section('content')
<div class="space-y-5">

    {{-- Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
        <form method="GET" action="{{ route('admin.activity.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">التصنيف</label>
                <select name="log" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm text-gray-800 focus:outline-none focus:ring-2 transition-shadow">
                    <option value="all" {{ $logName === 'all' || !$logName ? 'selected' : '' }}>جميع التصنيفات</option>
                    @foreach($logNames as $name)
                        <option value="{{ $name }}" {{ $logName === $name ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">البحث</label>
                <input type="text" name="search" value="{{ $search }}"
                    class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    placeholder="ابحث في الأوصاف...">
            </div>
            <button type="submit"
                class="px-5 py-2 rounded-xl text-white text-sm font-semibold shadow-sm hover:shadow-md transition-all"
                style="background-color:#FF8528;"
                onmouseover="this.style.backgroundColor='#E06800'"
                onmouseout="this.style.backgroundColor='#FF8528'">
                تصفية
            </button>
            @if($logName && $logName !== 'all' || $search)
                <a href="{{ route('admin.activity.index') }}"
                   class="px-4 py-2 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
                    مسح
                </a>
            @endif
        </form>
    </div>

    {{-- Log Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-800">الأحداث</h2>
            <span class="text-xs text-gray-400">{{ $logs->total() }} إجمالي</span>
        </div>

        @if($logs->count())
        <div class="divide-y divide-gray-50">
            @foreach($logs as $log)
            <div class="px-6 py-3.5 flex items-start gap-4">
                {{-- Icon by category --}}
                <div class="mt-0.5 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                    @if($log->log_name === 'auth') bg-blue-50 text-blue-500
                    @elseif($log->log_name === 'content') bg-purple-50 text-purple-500
                    @elseif($log->log_name === 'media') bg-pink-50 text-pink-500
                    @elseif($log->log_name === 'settings') bg-yellow-50 text-yellow-600
                    @elseif($log->log_name === 'users') bg-green-50 text-green-500
                    @elseif($log->log_name === 'contact') bg-teal-50 text-teal-500
                    @else bg-gray-100 text-gray-400 @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($log->log_name === 'auth')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        @elseif($log->log_name === 'content')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        @elseif($log->log_name === 'media')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        @elseif($log->log_name === 'settings')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        @elseif($log->log_name === 'contact')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        @endif
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800">{{ $log->description }}</p>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            @if($log->log_name === 'auth') bg-blue-100 text-blue-600
                            @elseif($log->log_name === 'content') bg-purple-100 text-purple-600
                            @elseif($log->log_name === 'media') bg-pink-100 text-pink-600
                            @elseif($log->log_name === 'settings') bg-yellow-100 text-yellow-700
                            @elseif($log->log_name === 'users') bg-green-100 text-green-600
                            @elseif($log->log_name === 'contact') bg-teal-100 text-teal-600
                            @else bg-gray-100 text-gray-500 @endif">
                            {{ $log->log_name ?? 'general' }}
                        </span>
                        @if($log->causer_id)
                            <span class="text-xs text-gray-400">بواسطة المستخدم #{{ $log->causer_id }}</span>
                        @endif
                    </div>
                </div>

                <time class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0 mt-0.5"
                      title="{{ $log->created_at?->format('Y-m-d H:i:s') }}">
                    {{ $log->created_at?->diffForHumans() }}
                </time>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
        @endif

        @else
        <div class="py-14 text-center">
            <svg class="mx-auto w-10 h-10 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm text-gray-400">لا يوجد نشاط.</p>
        </div>
        @endif
    </div>

</div>
@endsection
