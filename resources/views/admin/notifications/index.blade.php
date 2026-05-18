@extends('layouts.admin')

@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">الإشعارات</h2>
            @php
                try { $unreadCount = auth()->user()->unreadNotifications()->count(); } catch (\Throwable $e) { $unreadCount = 0; \Illuminate\Support\Facades\Log::warning('notifications unreadCount: '.$e->getMessage()); }
            @endphp
            <p class="text-sm text-gray-500 mt-0.5">
                {{ $notifications->total() }} إشعار
                @if($unreadCount > 0)
                    &mdash; <span class="font-semibold" style="color:#FF8528;">{{ $unreadCount }} غير مقروء</span>
                @endif
            </p>
        </div>
        @if($unreadCount > 0)
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                تعليم الكل كمقروء
            </button>
        </form>
        @endif
    </div>

    {{-- List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($notifications->isEmpty())
        <div class="px-6 py-16 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-50">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">لا توجد إشعارات!</p>
            <p class="text-sm text-gray-400 mt-1">لم يتم استلام أي إشعارات بعد.</p>
        </div>
        @else
        <ul class="divide-y divide-gray-50">
            @foreach($notifications as $notification)
            @php
                $data    = $notification->data;
                $isRead  = $notification->read_at !== null;
                $icons = [
                    'message' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                    'lead'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                    'default' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                ];
                $iconPath = $icons[$data['icon'] ?? 'default'] ?? $icons['default'];
            @endphp
            <li x-data="{}"
                class="flex items-start gap-4 px-6 py-4 transition-colors {{ $isRead ? 'opacity-70' : 'bg-orange-50/30' }} hover:bg-gray-50"
                id="notif-{{ $notification->id }}">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                     style="{{ $isRead ? 'background:#f1f5f9;' : 'background:#FFF4EA;' }}">
                    <svg class="w-5 h-5" fill="none" stroke="{{ $isRead ? '#94a3b8' : '#FF8528' }}" viewBox="0 0 24 24">
                        {!! $iconPath !!}
                    </svg>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 {{ $isRead ? 'font-normal' : '' }}">
                                {{ $data['title'] ?? 'إشعار' }}
                                @if(! $isRead)
                                    <span class="inline-block w-2 h-2 rounded-full ms-1.5 align-middle" style="background:#FF8528;"></span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $data['body'] ?? '' }}</p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        @if(isset($data['url']))
                        <a href="{{ $data['url'] }}"
                           @if(! $isRead)
                           @click="fetch('{{ route('admin.notifications.read', $notification->id) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}})"
                           @endif
                           class="text-xs font-medium hover:underline"
                           style="color:#FF8528;">
                            عرض &rarr;
                        </a>
                        @endif
                        @if(! $isRead)
                        <button @click="fetch('{{ route('admin.notifications.read', $notification->id) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}}).then(()=>document.getElementById('notif-{{ $notification->id }}').classList.add('opacity-70'))"
                                class="text-xs text-gray-400 hover:text-gray-600 transition-colors">
                            تعليم كمقروء
                        </button>
                        @endif
                        <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}"
                              onsubmit="return confirm('حذف هذا الإشعار؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection
