@extends('layouts.admin')
@section('title', 'الكوبونات')
@section('page-title', 'إدارة الكوبونات')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">الكوبونات والخصومات</h2>
            <p class="text-sm text-gray-500 mt-0.5">إجمالي {{ $coupons->total() }} كوبون</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-sm"
           style="background:#FF8528;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            كوبون جديد
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">بحث</label>
                <div class="relative">
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="كود الكوبون أو الوصف..."
                           class="w-full rounded-xl border border-gray-200 pr-9 pl-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold text-white shadow-sm" style="background:#FF8528;">بحث</button>
                @if(request('q'))
                    <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">مسح</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($coupons->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p class="text-sm">لا توجد كوبونات بعد</p>
                <a href="{{ route('admin.coupons.create') }}" class="text-sm mt-2 inline-block" style="color:#FF8528;">إنشاء كوبون</a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الكود</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">النوع</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">القيمة</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الاستخدام</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">تاريخ الانتهاء</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                        <th class="px-5 py-3.5 text-end text-xs font-semibold text-gray-500 uppercase">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($coupons as $coupon)
                        @php
                            $expired   = $coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast();
                            $exhausted = $coupon->max_uses && $coupon->times_used >= $coupon->max_uses;
                            $active    = $coupon->is_active && !$expired && !$exhausted;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="font-mono font-bold text-gray-800 tracking-wider">{{ $coupon->code }}</span>
                                @if($coupon->description)
                                    <p class="text-xs text-gray-400">{{ $coupon->description }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $coupon->type === 'percent' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $coupon->type === 'percent' ? 'نسبة مئوية' : 'قيمة ثابتة' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-gray-800">
                                {{ $coupon->type === 'percent' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 text-xs">
                                {{ $coupon->times_used }} / {{ $coupon->max_uses ?? '∞' }}
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">
                                @if($coupon->expires_at)
                                    <span class="{{ $expired ? 'text-red-500 font-medium' : '' }}">
                                        {{ \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">لا ينتهي</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $active ? 'نشط' : ($expired ? 'منتهي' : ($exhausted ? 'مستنفد' : 'معطل')) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                       class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">تعديل</a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" onsubmit="return confirm('حذف هذا الكوبون؟')">
                                        @csrf @method('DELETE')
                                        <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($coupons->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">{{ $coupons->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection
