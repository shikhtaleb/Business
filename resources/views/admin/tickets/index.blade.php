@extends('layouts.admin')
@section('title', 'تذاكر الدعم')
@section('page-title', 'تذاكر الدعم')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">تذاكر الدعم</h2>
            <p class="text-sm text-gray-500 mt-0.5">إجمالي {{ $tickets->total() }} تذكرة</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Status Tabs --}}
    <div class="flex flex-wrap gap-2">
        @foreach(['all' => 'الكل', 'open' => 'مفتوح', 'in_progress' => 'قيد المعالجة', 'resolved' => 'محلول', 'closed' => 'مغلق'] as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors
                      {{ $status === $key ? 'text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}"
               @if($status === $key) style="background:#FF8528;" @endif>
                {{ $label }} ({{ $counts[$key] ?? 0 }})
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-2">
        <input type="hidden" name="status" value="{{ $status }}">
        <select name="priority" class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 bg-white">
            <option value="">كل الأولويات</option>
            @foreach(['low' => 'منخفضة', 'normal' => 'عادية', 'high' => 'عالية', 'urgent' => 'عاجلة'] as $k => $v)
                <option value="{{ $k }}" {{ $priority === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ $search }}" placeholder="بحث..." class="border border-gray-200 rounded-lg px-3 py-2 text-sm flex-1 min-w-[200px]">
        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:#FF8528;">بحث</button>
        <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50">مسح</a>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($tickets->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <p class="text-sm">لا توجد تذاكر</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الموضوع</th>
                            <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">مقدم الطلب</th>
                            <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                            <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الأولوية</th>
                            <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">المسؤول</th>
                            <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">آخر تحديث</th>
                            <th class="px-5 py-3.5 text-end text-xs font-semibold text-gray-500 uppercase">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($tickets as $ticket)
                            @php
                                $statusColors = ['open'=>'bg-blue-100 text-blue-700','in_progress'=>'bg-yellow-100 text-yellow-700','resolved'=>'bg-green-100 text-green-700','closed'=>'bg-gray-100 text-gray-600'];
                                $statusLabels = ['open'=>'مفتوح','in_progress'=>'قيد المعالجة','resolved'=>'محلول','closed'=>'مغلق'];
                                $prioColors   = ['low'=>'bg-gray-100 text-gray-600','normal'=>'bg-blue-100 text-blue-700','high'=>'bg-orange-100 text-orange-700','urgent'=>'bg-red-100 text-red-700'];
                                $prioLabels   = ['low'=>'منخفضة','normal'=>'عادية','high'=>'عالية','urgent'=>'عاجلة'];
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ $ticket->ticket_number }}</td>
                                <td class="px-5 py-3.5 font-medium text-gray-900">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="hover:underline" style="color:#FF8528;">{{ Str::limit($ticket->subject, 50) }}</a>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="text-gray-800 text-xs font-medium">{{ $ticket->requester_name }}</div>
                                    <div class="text-gray-400 text-xs">{{ $ticket->requester_email }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $prioColors[$ticket->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $prioLabels[$ticket->priority] ?? $ticket->priority }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 text-xs">{{ $ticket->assignedTo?->name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $ticket->updated_at->diffForHumans() }}</td>
                                <td class="px-5 py-3.5 text-end">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">
                                        عرض
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($tickets->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">{{ $tickets->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection
