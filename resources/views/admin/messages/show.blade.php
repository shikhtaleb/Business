@extends('layouts.admin')
@section('title', __('admin.message_from') . ' ' . $message->name)
@section('page-title', __('admin.inbox'))

@section('content')

<div class="max-w-3xl space-y-5">

    <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ app()->getLocale() === 'ar' ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
        </svg>
        {{ __('admin.back_to_inbox') }}
    </a>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Original Message --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
                         style="background:linear-gradient(135deg,#FF8528,#c45e00);">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $message->name }}</p>
                        <a href="mailto:{{ $message->email }}" class="text-sm text-gray-500 hover:underline">{{ $message->email }}</a>
                    </div>
                </div>
                <div class="text-right text-xs text-gray-400">
                    <p>{{ $message->created_at->format('Y-m-d H:i') }}</p>
                    <p class="mt-0.5">{{ $message->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-5">
            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $message->body }}</p>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                @if($message->lang)
                    <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ strtoupper($message->lang) }}</span>
                @endif
                @if($message->ip)
                    <span>IP: {{ $message->ip }}</span>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                  onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                @csrf @method('DELETE')
                <button class="text-xs text-red-500 hover:text-red-700 transition-colors">{{ __('admin.delete_message') }}</button>
            </form>
        </div>
    </div>

    {{-- Previous Replies --}}
    @foreach($replies as $reply)
        <div class="bg-blue-50 rounded-2xl border border-blue-100 px-6 py-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                         style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);">
                        {{ strtoupper(substr($reply->user->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-800">{{ $reply->user->name ?? __('admin.admin') }}</span>
                    @if($reply->sent_email)
                        <span class="text-xs text-green-600 flex items-center gap-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8"/>
                            </svg>
                            {{ __('admin.email_sent') }}
                        </span>
                    @endif
                </div>
                <span class="text-xs text-gray-400">{{ $reply->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $reply->body }}</p>
        </div>
    @endforeach

    {{-- Reply Form --}}
    @if($message->status !== 'replied' || true)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">{{ __('admin.write_reply') }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('admin.reply_to') }}: {{ $message->email }}</p>
            </div>
            <form method="POST" action="{{ route('admin.messages.reply', $message) }}" class="px-6 py-5 space-y-4">
                @csrf
                <textarea name="body" rows="6" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:border-transparent transition-shadow resize-none"
                    placeholder="{{ __('admin.reply_placeholder') }}">{{ old('body') }}</textarea>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="send_email" value="1" checked
                               class="w-4 h-4 rounded border-gray-300" style="accent-color:#FF8528;">
                        <span class="text-sm text-gray-600">{{ __('admin.send_via_email') }}</span>
                        @if(!Setting::get('smtp_host'))
                            <span class="text-xs text-amber-600">({{ __('admin.smtp_not_configured_short') }})</span>
                        @endif
                    </label>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
                        style="background:#FF8528;"
                        onmouseover="this.style.background='#E06800'"
                        onmouseout="this.style.background='#FF8528'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        {{ __('admin.send_reply') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>
@endsection
