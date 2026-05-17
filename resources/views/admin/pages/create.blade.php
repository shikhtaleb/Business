@extends('layouts.admin')

@section('title', 'إنشاء صفحة')
@section('page-title', 'إنشاء صفحة جديدة')

@section('content')
<div x-data="pageForm()" class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pages.index') }}"
           class="w-8 h-8 flex items-center justify-center rounded-xl border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-lg font-semibold text-gray-900">إنشاء صفحة جديدة</h2>
    </div>

    <form method="POST" action="{{ route('admin.pages.store') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Main content --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Language tabs --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Tab bar --}}
                    <div class="flex border-b border-gray-100 px-5 pt-4 gap-1">
                        @foreach(['ar' => 'AR — العربية', 'en' => 'EN — الإنجليزية', 'nl' => 'NL — الهولندية', 'de' => 'DE — الألمانية'] as $lang => $label)
                            <button type="button"
                                    @click="activeTab = '{{ $lang }}'"
                                    :class="activeTab === '{{ $lang }}'
                                        ? 'border-b-2 text-gray-900 font-semibold'
                                        : 'text-gray-400 hover:text-gray-600'"
                                    class="px-3 pb-3 text-xs transition-colors whitespace-nowrap"
                                    style="border-color: activeTab === '{{ $lang }}' ? '#FF8528' : 'transparent'">
                                <span :style="activeTab === '{{ $lang }}' ? 'color:#FF8528' : ''">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="p-5 space-y-4">
                        {{-- AR --}}
                        <div x-show="activeTab === 'ar'" x-cloak>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">العنوان (عربي) <span class="text-red-500">*</span></label>
                            <input type="text" name="title_ar" value="{{ old('title_ar') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="direction:rtl; --tw-ring-color:#FF8528"
                                   placeholder="عنوان الصفحة">
                            @error('title_ar')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 mt-4">المحتوى (عربي)</label>
                            <textarea name="body_ar" rows="12"
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent font-mono"
                                      style="direction:rtl; --tw-ring-color:#FF8528"
                                      placeholder="محتوى الصفحة...">{{ old('body_ar') }}</textarea>
                        </div>

                        {{-- EN --}}
                        <div x-show="activeTab === 'en'" x-cloak>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">العنوان (إنجليزي)</label>
                            <input type="text" name="title_en" value="{{ old('title_en') }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="--tw-ring-color:#FF8528"
                                   placeholder="عنوان الصفحة">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 mt-4">المحتوى (إنجليزي)</label>
                            <textarea name="body_en" rows="12"
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent font-mono"
                                      style="--tw-ring-color:#FF8528"
                                      placeholder="محتوى الصفحة...">{{ old('body_en') }}</textarea>
                        </div>

                        {{-- NL --}}
                        <div x-show="activeTab === 'nl'" x-cloak class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">العنوان (هولندي)</label>
                                <input type="text" name="title_nl" value="{{ old('title_nl') }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                       style="--tw-ring-color:#FF8528; direction:ltr"
                                       placeholder="عنوان الصفحة">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">المحتوى (هولندي)</label>
                                <textarea name="body_nl" rows="12"
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent font-mono"
                                          style="--tw-ring-color:#FF8528; direction:ltr"
                                          placeholder="محتوى الصفحة...">{{ old('body_nl') }}</textarea>
                            </div>
                        </div>

                        {{-- DE --}}
                        <div x-show="activeTab === 'de'" x-cloak class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">العنوان (ألماني)</label>
                                <input type="text" name="title_de" value="{{ old('title_de') }}"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                       style="--tw-ring-color:#FF8528; direction:ltr"
                                       placeholder="عنوان الصفحة">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">المحتوى (ألماني)</label>
                                <textarea name="body_de" rows="12"
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent font-mono"
                                          style="--tw-ring-color:#FF8528; direction:ltr"
                                          placeholder="محتوى الصفحة...">{{ old('body_de') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700">SEO</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">عنوان ميتا</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#FF8528"
                               placeholder="يستبدل عنوان الصفحة في محركات البحث">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">وصف ميتا</label>
                        <textarea name="meta_desc" rows="3"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                  style="--tw-ring-color:#FF8528"
                                  placeholder="وصف مختصر لمحركات البحث (١٥٠–١٦٠ حرفاً)">{{ old('meta_desc') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Publish --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700">النشر</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">الحالة</label>
                        <select name="status"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#FF8528">
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>منشور</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">ترتيب العرض</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#FF8528">
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="hidden" name="show_in_nav" value="0">
                        <input type="checkbox" name="show_in_nav" value="1"
                               {{ old('show_in_nav') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 accent-orange-500">
                        <span class="text-sm text-gray-700">إظهار في القائمة</span>
                    </label>

                    <div class="pt-1 flex gap-3">
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-colors"
                                style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                            إنشاء الصفحة
                        </button>
                        <a href="{{ route('admin.pages.index') }}"
                           class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                            إلغاء
                        </a>
                    </div>
                </div>

                {{-- URL slug --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-700">الرابط المختصر</h3>
                    <p class="text-xs text-gray-400">اتركه فارغاً للإنشاء التلقائي من العنوان العربي.</p>
                    <div class="flex items-center gap-0">
                        <span class="px-3 py-2.5 bg-gray-50 border border-r-0 border-gray-300 rounded-l-xl text-xs text-gray-400">/</span>
                        <input type="text" name="slug" value="{{ old('slug') }}"
                               class="flex-1 px-3.5 py-2.5 rounded-r-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#FF8528"
                               placeholder="page-slug">
                    </div>
                    @error('slug')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Template --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-700">القالب</h3>
                    <select name="template"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528">
                        <option value="default" {{ old('template', 'default') === 'default' ? 'selected' : '' }}>افتراضي</option>
                        <option value="full-width" {{ old('template') === 'full-width' ? 'selected' : '' }}>عرض كامل</option>
                        <option value="landing" {{ old('template') === 'landing' ? 'selected' : '' }}>صفحة هبوط</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function pageForm() {
    return {
        activeTab: 'ar',
    };
}
</script>
@endpush
@endsection
