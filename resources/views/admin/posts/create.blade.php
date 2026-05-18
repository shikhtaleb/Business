@extends('layouts.admin')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.ql-container { border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; font-size: 0.9rem; min-height: 280px; }
.ql-toolbar { border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; background: #f8fafc; border-color: #e5e7eb !important; }
.ql-container { border-color: #e5e7eb !important; }
.ql-toolbar button:hover .ql-stroke, .ql-toolbar button.ql-active .ql-stroke { stroke: #FF8528 !important; }
.ql-toolbar button:hover .ql-fill, .ql-toolbar button.ql-active .ql-fill { fill: #FF8528 !important; }
.ql-editor { min-height: 280px; line-height: 1.8; }
.ql-editor.ql-blank::before { color: #9ca3af; font-style: normal; }
</style>
@endpush

@section('title', 'مقال جديد')
@section('page-title', 'إنشاء مقال جديد')

@section('content')
<div x-data="postForm()" class="space-y-5">

    {{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.posts.index') }}" class="hover:text-gray-700 transition-colors">المقالات</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="text-gray-900 font-medium">مقال جديد</span>
    </div>

    {{-- ── Validation Errors ───────────────────────────────────────────────── --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1">يوجد أخطاء في البيانات المدخلة:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li class="text-sm text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.posts.store') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            {{-- ── Left column: content ────────────────────────────────────── --}}
            <div class="xl:col-span-2 space-y-5">

                {{-- Language Tabs --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    {{-- Tab nav --}}
                    <div class="border-b border-gray-100 px-5 pt-5">
                        <div class="flex gap-1">
                            @foreach(['ar' => 'العربية', 'en' => 'الإنجليزية', 'nl' => 'الهولندية', 'de' => 'الألمانية'] as $code => $label)
                                <button type="button"
                                        @click="tab = '{{ $code }}'"
                                        :class="tab === '{{ $code }}'
                                            ? 'border-b-2 text-gray-900 font-semibold'
                                            : 'text-gray-500 hover:text-gray-700'"
                                        class="px-4 py-2.5 text-sm transition-colors -mb-px"
                                        :style="tab === '{{ $code }}' ? 'border-color:#FF8528;color:#FF8528;' : ''">
                                    {{ $label }}
                                    @if($code === 'ar')
                                        <span class="text-red-500 ms-0.5">*</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab panels --}}
                    <div class="p-5 space-y-4">

                        @foreach(['ar' => 'العربية', 'en' => 'الإنجليزية', 'nl' => 'الهولندية', 'de' => 'الألمانية'] as $code => $label)
                            <div x-show="tab === '{{ $code }}'" x-cloak>
                                {{-- Title --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        العنوان ({{ $label }})
                                        @if($code === 'ar') <span class="text-red-500">*</span> @endif
                                    </label>
                                    <input type="text"
                                           name="title_{{ $code }}"
                                           value="{{ old('title_' . $code) }}"
                                           {{ $code === 'ar' ? 'required' : '' }}
                                           dir="{{ $code === 'ar' ? 'rtl' : 'ltr' }}"
                                           placeholder="{{ $code === 'ar' ? 'أدخل عنوان المقال…' : 'Enter post title…' }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                                    @error('title_' . $code)
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Excerpt --}}
                                <div class="mb-4" @if($code !== 'ar' && $code !== 'en') x-show="tab === '{{ $code }}'" @endif>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        المقتطف ({{ $label }})
                                    </label>
                                    <textarea name="excerpt_{{ $code }}"
                                              rows="3"
                                              dir="{{ $code === 'ar' ? 'rtl' : 'ltr' }}"
                                              placeholder="{{ $code === 'ar' ? 'ملخص قصير للمقال…' : 'Short post summary…' }}"
                                              class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none">{{ old('excerpt_' . $code) }}</textarea>
                                    @error('excerpt_' . $code)
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Body --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        محتوى المقال ({{ $label }})
                                        @if($code === 'ar') <span class="text-red-500">*</span> @endif
                                    </label>
                                    @if(in_array($code, ['ar', 'en']))
                                        {{-- Quill rich-text editor for AR and EN --}}
                                        <textarea name="body_{{ $code }}"
                                                  id="body_{{ $code }}_input"
                                                  class="hidden">{{ old('body_' . $code, '') }}</textarea>
                                        <div id="quill_{{ $code }}"
                                             dir="{{ $code === 'ar' ? 'rtl' : 'ltr' }}"
                                             class="rounded-xl border border-gray-200 overflow-hidden quill-editor"
                                             style="min-height:280px;"></div>
                                    @else
                                        {{-- Plain textarea for NL and DE --}}
                                        <textarea name="body_{{ $code }}"
                                                  rows="12"
                                                  dir="ltr"
                                                  class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent font-mono resize-y"
                                                  placeholder="محتوى المقال...">{{ old('body_' . $code, '') }}</textarea>
                                    @endif
                                    @error('body_' . $code)
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── SEO (collapsible) ──────────────────────────────────── --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
                     x-data="{ seoOpen: false }">
                    <button type="button"
                            @click="seoOpen = !seoOpen"
                            class="w-full flex items-center justify-between px-5 py-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            إعدادات SEO
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
                             :class="seoOpen ? 'rotate-180' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="seoOpen" x-collapse x-cloak class="px-5 pb-5 space-y-4 border-t border-gray-100">
                        <div class="pt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">عنوان SEO</label>
                            <input type="text"
                                   name="seo_title"
                                   value="{{ old('seo_title') }}"
                                   maxlength="255"
                                   placeholder="عنوان الصفحة في محركات البحث…"
                                   class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                            @error('seo_title')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">وصف SEO</label>
                            <textarea name="seo_desc"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="وصف الصفحة في محركات البحث… (الحد الأقصى 500 حرف)"
                                      class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none">{{ old('seo_desc') }}</textarea>
                            @error('seo_desc')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right column: settings ──────────────────────────────────── --}}
            <div class="space-y-5">

                {{-- Publish Box --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h3 class="text-sm font-bold text-gray-900">النشر</h3>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">الحالة <span class="text-red-500">*</span></label>
                        <select name="status"
                                x-model="status"
                                class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                            <option value="draft">مسودة</option>
                            <option value="published">منشور</option>
                            <option value="scheduled">مجدول</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Published At --}}
                    <div x-show="status === 'scheduled' || status === 'published'" x-cloak>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">تاريخ النشر</label>
                        <input type="datetime-local"
                               name="published_at"
                               value="{{ old('published_at') }}"
                               class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                        @error('published_at')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Is Featured --}}
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox"
                               name="is_featured"
                               value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 accent-orange-500">
                        <span class="text-sm font-medium text-gray-700">مقال مميز</span>
                    </label>

                    {{-- Lang Locked --}}
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="lang_locked" value="0">
                        <input type="checkbox"
                               name="lang_locked"
                               value="1"
                               {{ old('lang_locked') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 accent-orange-500">
                        <span class="text-sm font-medium text-gray-700">قفل اللغة</span>
                    </label>

                    {{-- Actions --}}
                    <div class="flex gap-2 pt-2 border-t border-gray-100">
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition-colors"
                                style="background:#FF8528;">
                            حفظ المقال
                        </button>
                        <a href="{{ route('admin.posts.index') }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                            إلغاء
                        </a>
                    </div>
                </div>

                {{-- Slug --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">الرابط (Slug)</label>
                    <input type="text"
                           name="slug"
                           value="{{ old('slug') }}"
                           dir="ltr"
                           placeholder="يُولَّد تلقائياً من العنوان العربي"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 font-mono focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">اتركه فارغاً للتوليد التلقائي</p>
                    @error('slug')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">التصنيف</label>
                    <select name="category_id"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                        <option value="">— بدون تصنيف —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->parent_id ? '— ' : '' }}{{ $cat->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">الوسوم (Tags)</label>
                    <input type="text"
                           name="tags_input"
                           value="{{ old('tags_input') }}"
                           placeholder="وسم1، وسم2، وسم3"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">افصل بين الوسوم بفاصلة. سيتم إنشاء الوسوم الجديدة تلقائياً.</p>
                    @error('tags_input')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5"
                     x-data="{
                         imageUrl: '{{ old('featured_image') }}',
                         picker: { open: false, loading: false, images: [] },
                         async openPicker() {
                             this.picker.open = true;
                             if (!this.picker.images.length) {
                                 this.picker.loading = true;
                                 try {
                                     const r = await fetch('{{ route('admin.media.index') }}?json=1', { headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'} });
                                     this.picker.images = await r.json();
                                 } catch {}
                                 this.picker.loading = false;
                             }
                         }
                     }">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">الصورة المميزة</label>
                    <div class="flex gap-2">
                        <input type="text"
                               name="featured_image"
                               x-model="imageUrl"
                               dir="ltr"
                               placeholder="https://…"
                               class="flex-1 rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 font-mono focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                        <button type="button" @click="openPicker()"
                                class="flex-shrink-0 px-3 py-2 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors whitespace-nowrap">
                            🖼 استعراض
                        </button>
                    </div>
                    <div x-show="imageUrl" class="mt-2">
                        <img :src="imageUrl" class="h-28 w-full object-cover rounded-xl border border-gray-100" loading="lazy">
                    </div>
                    @error('featured_image')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Media Picker Modal --}}
                    <div x-show="picker.open" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                         style="background:rgba(15,23,42,0.6);"
                         @click.self="picker.open = false"
                         @keydown.escape.window="picker.open = false">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[75vh] flex flex-col overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-900 text-sm">اختر صورة</h3>
                                <button @click="picker.open = false" class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto p-4">
                                <template x-if="picker.loading">
                                    <p class="text-center text-sm text-gray-400 py-12">جارٍ التحميل...</p>
                                </template>
                                <template x-if="!picker.loading && picker.images.length === 0">
                                    <p class="text-center text-sm text-gray-400 py-12">لا توجد صور في المكتبة</p>
                                </template>
                                <div x-show="!picker.loading" class="grid grid-cols-3 sm:grid-cols-4 gap-2.5">
                                    <template x-for="img in picker.images" :key="img.id">
                                        <button type="button"
                                                @click="imageUrl = img.url; picker.open = false"
                                                class="aspect-square rounded-xl overflow-hidden border-2 border-transparent hover:border-orange-400 transition-all">
                                            <img :src="img.url" :alt="img.original_name" class="w-full h-full object-cover" loading="lazy">
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                                <a href="{{ route('admin.media.index') }}" target="_blank" class="text-xs font-medium hover:underline" style="color:#FF8528;">+ رفع صورة جديدة</a>
                                <button @click="picker.open = false" class="px-4 py-2 rounded-xl border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">إلغاء</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
function postForm() {
    return {
        tab: 'ar',
        status: '{{ old('status', 'draft') }}',
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const editors = {};

    ['ar', 'en'].forEach(function(lang) {
        const container = document.getElementById('quill_' + lang);
        const textarea = document.getElementById('body_' + lang + '_input');
        if (!container || !textarea) return;

        const toolbarOptions = [
            [{ 'header': [2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            [{ 'align': [] }],
            ['clean']
        ];

        const quill = new Quill(container, {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
            placeholder: lang === 'ar' ? 'اكتب محتوى المقال هنا…' : 'Write post content here…',
            direction: lang === 'ar' ? 'rtl' : 'ltr',
        });

        // Set initial content
        const initialContent = textarea.value;
        if (initialContent) {
            quill.root.innerHTML = initialContent;
        }

        editors[lang] = quill;
    });

    // Sync Quill content to hidden textarea before form submit
    const form = document.querySelector('form[action*="posts"]');
    if (form) {
        form.addEventListener('submit', function() {
            ['ar', 'en'].forEach(function(lang) {
                const textarea = document.getElementById('body_' + lang + '_input');
                const quill = editors[lang];
                if (textarea && quill) {
                    textarea.value = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
                }
            });
        });
    }
});
</script>
@endpush
