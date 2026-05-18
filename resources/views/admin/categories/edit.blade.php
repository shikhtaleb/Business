@extends('layouts.admin')
@section('title', 'تعديل التصنيف')
@section('page-title', 'تعديل التصنيف')

@section('content')
<div class="max-w-xl space-y-5">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        العودة إلى التصنيفات
    </a>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">الاسم (عربي) <span class="text-red-500">*</span></label>
                <input type="text" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">الاسم (إنجليزي)</label>
                <input type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">الاسم (هولندي)</label>
                <input type="text" name="name_nl" value="{{ old('name_nl', $category->name_nl) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">الاسم (ألماني)</label>
                <input type="text" name="name_de" value="{{ old('name_de', $category->name_de) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">الرابط (Slug)</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">الوصف</label>
            <textarea name="description_ar" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">{{ old('description_ar', $category->description_ar) }}</textarea>
        </div>
        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">إلغاء</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">حفظ التغييرات</button>
        </div>
    </form>
</div>
@endsection
