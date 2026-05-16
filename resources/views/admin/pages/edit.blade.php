@extends('layouts.admin')

@section('title', 'Edit Page')
@section('page-title', 'Edit Page')

@section('content')
<div x-data="pageForm()" class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pages.index') }}"
               class="w-8 h-8 flex items-center justify-center rounded-xl border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-lg font-semibold text-gray-900">Edit Page</h2>
        </div>
        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
              x-data
              @submit.prevent="if(confirm('Delete this page permanently?')) $el.submit()">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-red-600 text-xs font-medium border border-red-200 hover:bg-red-50 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.pages.update', $page) }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- Main content --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Language tabs --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Tab bar --}}
                    <div class="flex border-b border-gray-100 px-5 pt-4 gap-1">
                        @foreach(['ar' => 'AR — العربية', 'en' => 'EN — English', 'nl' => 'NL — Nederlands', 'de' => 'DE — Deutsch'] as $lang => $label)
                            <button type="button"
                                    @click="activeTab = '{{ $lang }}'"
                                    :class="activeTab === '{{ $lang }}'
                                        ? 'border-b-2 text-gray-900 font-semibold'
                                        : 'text-gray-400 hover:text-gray-600'"
                                    class="px-3 pb-3 text-xs transition-colors whitespace-nowrap">
                                <span :style="activeTab === '{{ $lang }}' ? 'color:#FF8528; border-color:#FF8528' : ''">{{ $label }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="p-5 space-y-4">
                        {{-- AR --}}
                        <div x-show="activeTab === 'ar'" x-cloak>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title (Arabic) <span class="text-red-500">*</span></label>
                            <input type="text" name="title_ar" value="{{ old('title_ar', $page->title_ar) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="direction:rtl; --tw-ring-color:#FF8528"
                                   placeholder="عنوان الصفحة">
                            @error('title_ar')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 mt-4">Content (Arabic)</label>
                            <textarea name="body_ar" rows="14"
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent font-mono"
                                      style="direction:rtl; --tw-ring-color:#FF8528"
                                      placeholder="محتوى الصفحة...">{{ old('body_ar', $page->body_ar) }}</textarea>
                        </div>

                        {{-- EN --}}
                        <div x-show="activeTab === 'en'" x-cloak>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title (English)</label>
                            <input type="text" name="title_en" value="{{ old('title_en', $page->title_en) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="--tw-ring-color:#FF8528"
                                   placeholder="Page title">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5 mt-4">Content (English)</label>
                            <textarea name="body_en" rows="14"
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent font-mono"
                                      style="--tw-ring-color:#FF8528"
                                      placeholder="Page content...">{{ old('body_en', $page->body_en) }}</textarea>
                        </div>

                        {{-- NL --}}
                        <div x-show="activeTab === 'nl'" x-cloak>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title (Dutch)</label>
                            <input type="text" name="title_nl" value="{{ old('title_nl', $page->title_nl) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="--tw-ring-color:#FF8528"
                                   placeholder="Paginatitel">
                        </div>

                        {{-- DE --}}
                        <div x-show="activeTab === 'de'" x-cloak>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title (German)</label>
                            <input type="text" name="title_de" value="{{ old('title_de', $page->title_de) }}"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                   style="--tw-ring-color:#FF8528"
                                   placeholder="Seitentitel">
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700">SEO</h3>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#FF8528"
                               placeholder="Override page title for search engines">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Meta Description</label>
                        <textarea name="meta_desc" rows="3"
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                  style="--tw-ring-color:#FF8528"
                                  placeholder="Brief description for search engines (150–160 chars)">{{ old('meta_desc', $page->meta_desc) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Publish --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700">Publish</h3>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                        <select name="status"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#FF8528">
                            <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#FF8528">
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="hidden" name="show_in_nav" value="0">
                        <input type="checkbox" name="show_in_nav" value="1"
                               {{ old('show_in_nav', $page->show_in_nav) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 accent-orange-500">
                        <span class="text-sm text-gray-700">Show in navigation</span>
                    </label>

                    <div class="pt-1 flex gap-3">
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-colors"
                                style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                            Save Changes
                        </button>
                        <a href="{{ route('admin.pages.index') }}"
                           class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                {{-- URL slug --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-700">URL Slug</h3>
                    <div class="flex items-center gap-0">
                        <span class="px-3 py-2.5 bg-gray-50 border border-r-0 border-gray-300 rounded-l-xl text-xs text-gray-400">/</span>
                        <input type="text" name="slug" value="{{ old('slug', $page->slug) }}"
                               class="flex-1 px-3.5 py-2.5 rounded-r-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                               style="--tw-ring-color:#FF8528">
                    </div>
                    @error('slug')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Template --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-700">Template</h3>
                    <select name="template"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528">
                        <option value="default" {{ old('template', $page->template) === 'default' ? 'selected' : '' }}>Default</option>
                        <option value="full-width" {{ old('template', $page->template) === 'full-width' ? 'selected' : '' }}>Full Width</option>
                        <option value="landing" {{ old('template', $page->template) === 'landing' ? 'selected' : '' }}>Landing</option>
                    </select>
                </div>

                {{-- Meta info --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-2 text-xs text-gray-500">
                    <div class="flex justify-between">
                        <span>Author</span>
                        <span class="font-medium text-gray-700">{{ $page->author?->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Created</span>
                        <span class="font-medium text-gray-700">{{ $page->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Updated</span>
                        <span class="font-medium text-gray-700">{{ $page->updated_at->format('d M Y, H:i') }}</span>
                    </div>
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
