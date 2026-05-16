@extends('layouts.admin')
@section('title', 'تعديل الكوبون')
@section('page-title', 'تعديل الكوبون')

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

    <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">كود الكوبون</label>
            <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm uppercase font-mono tracking-wider focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">النوع</label>
                <select name="type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none">
                    <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>قيمة ثابتة ($)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">القيمة</label>
                <input type="number" name="value" value="{{ old('value', $coupon->value) }}" required min="0" step="0.01"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأقصى للاستخدام</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" min="1" placeholder="غير محدود"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الانتهاء</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d') : '') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">وصف</label>
            <input type="text" name="description" value="{{ old('description', $coupon->description) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <div class="flex items-center justify-between text-sm text-gray-500 bg-gray-50 rounded-xl px-4 py-3">
            <span>استُخدم {{ $coupon->times_used }} {{ $coupon->max_uses ? 'من ' . $coupon->max_uses : '' }} مرة</span>
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="rounded border-gray-300">
            الكوبون نشط
        </label>
        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">إلغاء</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">حفظ التغييرات</button>
        </div>
    </form>
</div>
@endsection
