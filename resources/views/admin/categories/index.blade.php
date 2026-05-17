@extends('layouts.admin')
@section('title', 'تصنيفات المقالات')
@section('page-title', 'تصنيفات المقالات')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Create Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">إضافة تصنيف جديد</h3>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-4">
                <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">الاسم (عربي) <span class="text-red-500">*</span></label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">الاسم (إنجليزي)</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">الرابط (Slug)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="auto-generated">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">التصنيف الأب</label>
                <select name="parent_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">لا يوجد (رئيسي)</option>
                    @foreach($roots as $root)
                        <option value="{{ $root->id }}" {{ old('parent_id') == $root->id ? 'selected' : '' }}>{{ $root->name_ar }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">الوصف</label>
                <textarea name="description_ar" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">{{ old('description_ar') }}</textarea>
            </div>
            <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">
                إضافة التصنيف
            </button>
        </form>
    </div>

    {{-- List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">التصنيفات ({{ $categories->count() }})</h3>
            <a href="{{ route('admin.posts.index') }}" class="text-sm" style="color:#FF8528;">المقالات</a>
        </div>

        @if($categories->isEmpty())
            <div class="py-12 text-center text-gray-400">
                <p class="text-sm">لا توجد تصنيفات بعد</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($categories as $cat)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50">
                        <div>
                            <span class="text-sm font-medium text-gray-800">
                                {{ $cat->parent ? '&nbsp;&nbsp;↳&nbsp;' : '' }}{{ $cat->name_ar }}
                            </span>
                            @if($cat->name_en)
                                <span class="text-xs text-gray-400 ms-2">/ {{ $cat->name_en }}</span>
                            @endif
                            <span class="text-xs text-gray-400 ms-2">{{ $cat->posts_count ?? 0 }} مقال</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $cat) }}"
                               class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">تعديل</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('حذف هذا التصنيف؟')">
                                @csrf @method('DELETE')
                                <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
