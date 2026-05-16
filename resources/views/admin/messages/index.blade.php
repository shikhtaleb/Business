@extends('layouts.admin')
@section('title', __('admin.messages'))
@section('page-title', __('admin.messages'))

@section('content')

<div class="space-y-5">

    {{-- Tabs --}}
    <div class="flex items-center gap-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-1 w-fit">
        @foreach(['all' => __('admin.all'), 'unread' => __('admin.unread'), 'read' => __('admin.read'), 'replied' => __('admin.replied')] as $tab => $label)
            <a href="{{ route('admin.messages.index', ['status' => $tab]) }}"
               class="px-4 py-2 rounded-xl text-sm font-medium transition-all
                      {{ $status === $tab ? 'text-white shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
               @if($status === $tab) style="background:#FF8528;" @endif>
                {{ $label }}
                @if($tab === 'unread' && $unreadCount > 0)
                    <span class="ms-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-white text-xs font-bold" style="color:#FF8528;">{{ $unreadCount }}</span>
                @endif
            </a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    {{-- Messages List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($messages->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">{{ __('admin.no_messages') }}</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($messages as $msg)
                    <a href="{{ route('admin.messages.show', $msg) }}"
                       class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition-colors {{ $msg->status === 'unread' ? 'bg-orange-50/40' : '' }}">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                             style="background:linear-gradient(135deg,#FF8528,#c45e00);">
                            {{ strtoupper(substr($msg->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="text-sm font-semibold text-gray-900">{{ $msg->name }}</span>
                                @if($msg->status === 'unread')
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:#FF8528;"></span>
                                @endif
                                <span class="ms-auto text-xs text-gray-400 flex-shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">{{ $msg->email }}</p>
                            <p class="text-sm text-gray-600 truncate mt-0.5">{{ Str::limit($msg->body, 90) }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            @if($msg->status === 'replied')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ __('admin.replied') }}</span>
                            @elseif($msg->status === 'read')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ __('admin.read') }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background:#FF8528;">{{ __('admin.new') }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $messages->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
