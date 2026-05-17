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

@section('title', 'تعديل المقال')
@section('page-title', 'تعديل المقال')

@section('content')
<div x-data="postForm()" class="space-y-5">

    {{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.posts.index') }}" class="hover:text-gray-700 transition-colors">المقالات</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span class="text-gray-900 font-medium line-clamp-1">{{ $post->title_ar }}</span>
    </div>

    {{-- ── View on Frontend ──────────────────────────────────────────────── --}}
    @if($post->status === 'published')
    <div class="flex justify-end">
        <a href="{{ url('/blog/' . $post->slug) }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-medium transition-colors hover:opacity-90"
           style="background-color:#22c55e;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            عرض المقال على الموقع
        </a>
    </div>
    @endif

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

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="space-y-5">
        @csrf
        @method('PUT')

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
                                           value="{{ old('title_' . $code, $post->{'title_' . $code}) }}"
                                           {{ $code === 'ar' ? 'required' : '' }}
                                           dir="{{ $code === 'ar' ? 'rtl' : 'ltr' }}"
                                           placeholder="{{ $code === 'ar' ? 'أدخل عنوان المقال…' : 'Enter post title…' }}"
                                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                                    @error('title_' . $code)
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Excerpt --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        المقتطف ({{ $label }})
                                    </label>
                                    <textarea name="excerpt_{{ $code }}"
                                              rows="3"
                                              dir="{{ $code === 'ar' ? 'rtl' : 'ltr' }}"
                                              placeholder="{{ $code === 'ar' ? 'ملخص قصير للمقال…' : 'Short post summary…' }}"
                                              class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none">{{ old('excerpt_' . $code, $post->{'excerpt_' . $code}) }}</textarea>
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
                                                  class="hidden">{{ old('body_' . $code, $post->{"body_{$code}"} ?? '') }}</textarea>
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
                                                  placeholder="محتوى المقال...">{{ old('body_' . $code, $post->{"body_{$code}"} ?? '') }}</textarea>
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
                     x-data="{ seoOpen: {{ ($post->seo_title || $post->seo_desc) ? 'true' : 'false' }} }">
                    <button type="button"
                            @click="seoOpen = !seoOpen"
                            class="w-full flex items-center justify-between px-5 py-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            إعدادات SEO
                            @if($post->seo_title || $post->seo_desc)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">مضبوط</span>
                            @endif
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
                                   value="{{ old('seo_title', $post->seo_title) }}"
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
                                      class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none">{{ old('seo_desc', $post->seo_desc) }}</textarea>
                            @error('seo_desc')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Post meta --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
                    <div class="flex flex-wrap gap-x-6 gap-y-1.5 text-xs text-gray-400">
                        <span>المشاهدات: <span class="font-semibold text-gray-600">{{ number_format($post->views_count) }}</span></span>
                        <span>أُنشئ: <span class="font-semibold text-gray-600">{{ $post->created_at->format('Y-m-d') }}</span></span>
                        <span>آخر تعديل: <span class="font-semibold text-gray-600">{{ $post->updated_at->format('Y-m-d H:i') }}</span></span>
                        <span>الكاتب: <span class="font-semibold text-gray-600">{{ $post->author?->name }}</span></span>
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
                            <option value="draft"     {{ old('status', $post->status) === 'draft'     ? 'selected' : '' }}>مسودة</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>منشور</option>
                            <option value="scheduled" {{ old('status', $post->status) === 'scheduled' ? 'selected' : '' }}>مجدول</option>
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
                               value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
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
                               {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 accent-orange-500">
                        <span class="text-sm font-medium text-gray-700">مقال مميز</span>
                    </label>

                    {{-- Lang Locked --}}
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="lang_locked" value="0">
                        <input type="checkbox"
                               name="lang_locked"
                               value="1"
                               {{ old('lang_locked', $post->lang_locked) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 accent-orange-500">
                        <span class="text-sm font-medium text-gray-700">قفل اللغة</span>
                    </label>

                    {{-- Actions --}}
                    <div class="flex gap-2 pt-2 border-t border-gray-100">
                        <button type="submit"
                                class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition-colors"
                                style="background:#FF8528;">
                            حفظ التغييرات
                        </button>
                        <a href="{{ route('admin.posts.index') }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                            إلغاء
                        </a>
                    </div>

                    {{-- Quick toggle publish --}}
                    <form method="POST" action="{{ route('admin.posts.publish', $post) }}" class="pt-1">
                        @csrf
                        <button type="submit"
                                class="w-full py-2 rounded-xl text-sm font-medium border transition-colors
                                       {{ $post->status === 'published'
                                           ? 'border-red-200 text-red-600 hover:bg-red-50'
                                           : 'border-green-200 text-green-700 hover:bg-green-50' }}">
                            {{ $post->status === 'published' ? 'إلغاء النشر' : 'نشر الآن' }}
                        </button>
                    </form>
                </div>

                {{-- Slug --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">الرابط (Slug)</label>
                    <input type="text"
                           name="slug"
                           value="{{ old('slug', $post->slug) }}"
                           dir="ltr"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 font-mono focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
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
                            <option value="{{ $cat->id }}"
                                    {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>
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
                           value="{{ old('tags_input', $tagsList) }}"
                           placeholder="وسم1، وسم2، وسم3"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">افصل بين الوسوم بفاصلة. سيتم إنشاء الوسوم الجديدة تلقائياً.</p>
                    @error('tags_input')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <label class="block text-xs font-semibold text-gray-500">رابط الصورة المميزة</label>
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}"
                             alt="صورة مميزة"
                             class="w-full h-32 object-cover rounded-xl border border-gray-100">
                    @endif
                    <input type="text"
                           name="featured_image"
                           value="{{ old('featured_image', $post->featured_image) }}"
                           dir="ltr"
                           placeholder="https://…"
                           class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-700 font-mono focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    @error('featured_image')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
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
        status: '{{ old('status', $post->status) }}',
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
