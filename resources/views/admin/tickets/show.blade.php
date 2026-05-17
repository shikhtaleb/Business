@extends('layouts.admin')
@section('title', 'تذكرة #' . $ticket->ticket_number)
@section('page-title', 'تذكرة #' . $ticket->ticket_number)

@section('content')
<div class="space-y-5">

    {{-- Back --}}
    <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        العودة إلى التذاكر
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main: Thread --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Original message --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $ticket->subject }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $ticket->requester_name }} &lt;{{ $ticket->requester_email }}&gt;</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="prose prose-sm max-w-none text-gray-700">{!! nl2br(e($ticket->body)) !!}</div>
            </div>

            {{-- Replies --}}
            @foreach($ticket->replies as $reply)
                <div class="bg-white rounded-2xl shadow-sm border {{ $reply->is_internal ? 'border-yellow-200 bg-yellow-50' : 'border-gray-100' }} p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-orange-100 flex items-center justify-center text-xs font-bold text-orange-600">
                                {{ strtoupper(substr($reply->user?->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-gray-800">{{ $reply->user?->name ?? 'Admin' }}</span>
                            @if($reply->is_internal)
                                <span class="text-xs px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">داخلي فقط</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400">{{ $reply->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="text-sm text-gray-700">{!! nl2br(e($reply->body)) !!}</div>
                </div>
            @endforeach

            {{-- Reply Form --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">إضافة رد</h4>
                <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}">
                    @csrf
                    <textarea name="body" rows="4" required
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200"
                              placeholder="اكتب ردك هنا..."></textarea>
                    <div class="flex items-center justify-between mt-3">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300">
                            رد داخلي (لا يُرسل للعميل)
                        </label>
                        <button type="submit" class="px-5 py-2 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">
                            إرسال الرد
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar: Info & Actions --}}
        <div class="space-y-4">

            {{-- Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">الحالة</h4>
                <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}">
                    @csrf
                    <select name="status" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                        @foreach(['open'=>'مفتوح','in_progress'=>'قيد المعالجة','resolved'=>'محلول','closed'=>'مغلق'] as $k=>$v)
                            <option value="{{ $k }}" {{ $ticket->status === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Priority --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">الأولوية</h4>
                <form method="POST" action="{{ route('admin.tickets.priority', $ticket) }}">
                    @csrf
                    <select name="priority" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                        @foreach(['low'=>'منخفضة','normal'=>'عادية','high'=>'عالية','urgent'=>'عاجلة'] as $k=>$v)
                            <option value="{{ $k }}" {{ $ticket->priority === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Assign --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">تعيين إلى</h4>
                <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}">
                    @csrf
                    <select name="assigned_to" onchange="this.form.submit()" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                        <option value="">غير معين</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3 text-sm">
                <h4 class="font-semibold text-gray-700">معلومات التذكرة</h4>
                <div class="flex justify-between"><span class="text-gray-500">رقم التذكرة</span><span class="font-mono text-xs">{{ $ticket->ticket_number }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">القناة</span><span>{{ $ticket->channel ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">تاريخ الإنشاء</span><span class="text-xs">{{ $ticket->created_at->format('Y-m-d H:i') }}</span></div>
                @if($ticket->resolved_at)
                    <div class="flex justify-between"><span class="text-gray-500">تاريخ الحل</span><span class="text-xs">{{ $ticket->resolved_at->format('Y-m-d H:i') }}</span></div>
                @endif
            </div>

            {{-- Delete --}}
            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('حذف هذه التذكرة نهائياً؟')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 text-sm font-medium hover:bg-red-50 transition-colors">
                    حذف التذكرة
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
