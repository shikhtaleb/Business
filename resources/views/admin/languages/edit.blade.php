@extends('layouts.admin')
@section('title', 'تعديل اللغة')
@section('page-title', __('admin.languages'))

@section('content')
<div class="max-w-xl space-y-5">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">تعديل اللغة: <span style="color:#FF8528;">{{ $language->native_name }}</span></h2>
        </div>
        <form method="POST" action="{{ route('admin.languages.update', $language) }}" enctype="multipart/form-data" class="px-6 py-6 space-y-5">
            @csrf @method('PUT')

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم بالإنجليزية</label>
                    <input type="text" name="name" value="{{ old('name', $language->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم الأصلي</label>
                    <input type="text" name="native_name" value="{{ old('native_name', $language->native_name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">رمز العلم</label>
                <input type="text" name="flag_code" value="{{ old('flag_code', $language->flag_code) }}" maxlength="10"
                       class="w-32 px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm font-mono focus:outline-none focus:ring-2"
                       placeholder="SA">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">اتجاه اللغة</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_rtl" value="0" {{ !$language->is_rtl ? 'checked' : '' }} class="w-4 h-4" style="accent-color:#FF8528;">
                        <span class="text-sm text-gray-700">LTR →</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="is_rtl" value="1" {{ $language->is_rtl ? 'checked' : '' }} class="w-4 h-4" style="accent-color:#FF8528;">
                        <span class="text-sm text-gray-700">RTL ←</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $language->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300" style="accent-color:#FF8528;">
                    <span class="text-sm text-gray-700">{{ __('admin.active') }}</span>
                </label>
                @if(!$language->is_default)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="set_default" value="1" class="w-4 h-4 rounded border-gray-300" style="accent-color:#FF8528;">
                        <span class="text-sm text-gray-700">تعيين كافتراضي</span>
                    </label>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">تحديث ملف الترجمة (JSON)</label>
                <input type="file" name="translations" accept=".json"
                       class="w-full text-sm text-gray-500">
                @if($language->translations)
                    <p class="mt-1 text-xs text-green-600">✓ يوجد ملف ترجمة ({{ count($language->translations) }} مفتاح)</p>
                @endif
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <a href="{{ route('admin.languages.export', $language) }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    تصدير الترجمات
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.languages.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
                        style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                        {{ __('admin.save_changes') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
