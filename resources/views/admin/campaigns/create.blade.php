@extends('layouts.admin')
@section('title', 'حملة جديدة')
@section('page-title', 'إنشاء حملة بريدية')

@section('content')
<div class="max-w-3xl space-y-5">
    <a href="{{ route('admin.campaigns.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        العودة إلى الحملات
    </a>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.campaigns.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">اسم الحملة</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الموضوع (عربي) <span class="text-red-500">*</span></label>
                <input type="text" name="subject_ar" value="{{ old('subject_ar') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الموضوع (إنجليزي)</label>
                <input type="text" name="subject_en" value="{{ old('subject_en') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">محتوى الرسالة (عربي) <span class="text-red-500">*</span></label>
            <textarea name="body_ar" rows="6" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">{{ old('body_ar') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">محتوى الرسالة (إنجليزي)</label>
            <textarea name="body_en" rows="6" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">{{ old('body_en') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">اللغة المستهدفة</label>
            <select name="target_lang" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none">
                <option value="">الكل</option>
                @foreach(['ar'=>'العربية','en'=>'الإنجليزية','nl'=>'الهولندية','de'=>'الألمانية'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('target_lang') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('admin.campaigns.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">إلغاء</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">حفظ كمسودة</button>
        </div>
    </form>
</div>
@endsection
