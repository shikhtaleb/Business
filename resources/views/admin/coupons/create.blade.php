@extends('layouts.admin')
@section('title', 'كوبون جديد')
@section('page-title', 'إنشاء كوبون')

@section('content')
<div class="max-w-xl space-y-5">
    <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        العودة إلى الكوبونات
    </a>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.coupons.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">كود الكوبون <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code') }}" required placeholder="SUMMER30"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm uppercase font-mono tracking-wider focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">النوع</label>
                <select name="type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none">
                    <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>قيمة ثابتة ($)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">القيمة <span class="text-red-500">*</span></label>
                <input type="number" name="value" value="{{ old('value') }}" required min="0" step="0.01"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأقصى للاستخدام</label>
                <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1" placeholder="غير محدود"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الانتهاء</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">وصف (اختياري)</label>
            <input type="text" name="description" value="{{ old('description') }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300">
            تفعيل الكوبون فور الإنشاء
        </label>
        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">إلغاء</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">إنشاء الكوبون</button>
        </div>
    </form>
</div>
@endsection
