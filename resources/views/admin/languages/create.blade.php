@extends('layouts.admin')
@section('title', 'إضافة لغة')
@section('page-title', __('admin.languages'))

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">إضافة لغة جديدة</h2>
        </div>
        <form method="POST" action="{{ route('admin.languages.store') }}" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    @foreach ($errors->all() as $e)
                        <p class="text-sm text-red-700">{{ $e }}</p>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">كود اللغة <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required maxlength="10"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm font-mono focus:outline-none focus:ring-2"
                           placeholder="fr / es / zh">
                    <p class="mt-1 text-xs text-gray-400">رمز ISO مثل: ar, en, fr, de</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">رمز العلم (اختياري)</label>
                    <input type="text" name="flag_code" value="{{ old('flag_code') }}" maxlength="10"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm font-mono focus:outline-none focus:ring-2"
                           placeholder="SA / GB / FR">
                    <p class="mt-1 text-xs text-gray-400">يُعرض كاختصار في القائمة</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم بالإنجليزية <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2"
                           placeholder="French">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم الأصلي <span class="text-red-500">*</span></label>
                    <input type="text" name="native_name" value="{{ old('native_name') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2"
                           placeholder="Français">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">اتجاه اللغة <span class="text-red-500">*</span></label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_rtl" value="0" {{ old('is_rtl', '0') == '0' ? 'checked' : '' }}
                               class="w-4 h-4" style="accent-color:#FF8528;">
                        <span class="text-sm text-gray-700">LTR → (إنجليزية، فرنسية...)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_rtl" value="1" {{ old('is_rtl') == '1' ? 'checked' : '' }}
                               class="w-4 h-4" style="accent-color:#FF8528;">
                        <span class="text-sm text-gray-700">RTL ← (عربية، فارسية...)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">ملف الترجمة (JSON) — اختياري</label>
                <input type="file" name="translations" accept=".json"
                       class="w-full text-sm text-gray-500 file:me-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:text-white file:cursor-pointer"
                       style="--file-bg:#FF8528;">
                <p class="mt-1 text-xs text-gray-400">يمكنك رفع ملف JSON يحتوي مفاتيح الترجمة. يمكن تعديله لاحقاً.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.languages.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
                    {{ __('admin.cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
                    style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                    إضافة اللغة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
