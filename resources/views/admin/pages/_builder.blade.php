@php
    $isCreate = !isset($page);
    $formAction = $isCreate ? route('admin.pages.store') : route('admin.pages.update', $page);
    $cfg = [
        'titles'      => [
            'ar' => old('title_ar', $page->title_ar ?? ''),
            'en' => old('title_en', $page->title_en ?? ''),
            'nl' => old('title_nl', $page->title_nl ?? ''),
            'de' => old('title_de', $page->title_de ?? ''),
        ],
        'slug'        => old('slug', $page->slug ?? ''),
        'status'      => old('status', $page->status ?? 'draft'),
        'template'    => old('template', $page->template ?? 'default'),
        'show_in_nav' => (bool)($page->show_in_nav ?? false),
        'sort_order'  => (int)($page->sort_order ?? 0),
        'meta_title'  => old('meta_title', $page->meta_title ?? ''),
        'meta_desc'   => old('meta_desc', $page->meta_desc ?? ''),
    ];
    $initialBlocks = $page->blocks ?? [];
@endphp

<div x-data="pageBuilder(@json($initialBlocks), @json($cfg))"
     @keydown.escape.window="showPicker = false"
     class="-mt-6 -mx-4 sm:-mx-6">

    {{-- ── HIDDEN FORM ─────────────────────────────────────────────── --}}
    <form id="builder-form" method="POST" action="{{ $formAction }}">
        @csrf
        @if(!$isCreate) @method('PUT') @endif
        <input type="hidden" name="blocks"      :value="JSON.stringify(blocks)">
        <input type="hidden" name="title_ar"    :value="cfg.titles.ar">
        <input type="hidden" name="title_en"    :value="cfg.titles.en">
        <input type="hidden" name="title_nl"    :value="cfg.titles.nl">
        <input type="hidden" name="title_de"    :value="cfg.titles.de">
        <input type="hidden" name="slug"        :value="cfg.slug">
        <input type="hidden" name="status"      :value="cfg.status">
        <input type="hidden" name="show_in_nav" :value="cfg.show_in_nav ? '1' : '0'">
        <input type="hidden" name="sort_order"  :value="cfg.sort_order">
        <input type="hidden" name="meta_title"  :value="cfg.meta_title">
        <input type="hidden" name="meta_desc"   :value="cfg.meta_desc">
        <input type="hidden" name="template"    :value="cfg.template">
    </form>

    {{-- ── TOP TOOLBAR ─────────────────────────────────────────────── --}}
    <div class="sticky top-14 z-30 px-4 sm:px-6 py-3 border-b flex items-center gap-3"
         style="background:#fff; border-color:#e2e8f0; box-shadow:0 1px 0 #f8fafc;">

        <a href="{{ route('admin.pages.index') }}"
           class="w-8 h-8 flex items-center justify-center rounded-xl border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        {{-- Page title live preview --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800 truncate"
               x-text="cfg.titles.ar || '{{ $isCreate ? 'صفحة جديدة' : ($page->title_ar ?? 'بدون عنوان') }}'"></p>
            <p class="text-xs text-gray-400" x-show="cfg.slug" x-text="'/' + cfg.slug"></p>
        </div>

        {{-- Lang tabs --}}
        <div class="hidden sm:flex items-center gap-1 bg-gray-100 rounded-xl p-1">
            @foreach(['ar' => 'AR','en' => 'EN','nl' => 'NL','de' => 'DE'] as $lk => $lv)
            <button type="button" @click="editLang = '{{ $lk }}'"
                    :class="editLang === '{{ $lk }}' ? 'bg-white shadow text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-3 py-1 rounded-lg text-xs font-semibold transition-all">
                {{ $lv }}
            </button>
            @endforeach
        </div>

        {{-- Status badge --}}
        <button type="button" @click="cfg.status = cfg.status === 'published' ? 'draft' : 'published'"
                :class="cfg.status === 'published'
                    ? 'bg-green-100 text-green-700 border-green-200'
                    : 'bg-gray-100 text-gray-500 border-gray-200'"
                class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-semibold transition-all">
            <span class="w-1.5 h-1.5 rounded-full"
                  :class="cfg.status === 'published' ? 'bg-green-500' : 'bg-gray-400'"></span>
            <span x-text="cfg.status === 'published' ? 'منشور' : 'مسودة'"></span>
        </button>

        @if(!$isCreate && ($page->status ?? '') === 'published')
        <a href="{{ route('page.show', $page->slug) }}" target="_blank"
           class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors flex-shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            معاينة
        </a>
        @endif

        <button type="button" @click="$refs.builderForm.submit()"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold transition-colors flex-shrink-0"
                style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            حفظ
        </button>
    </div>

    {{-- Bind form ref --}}
    <span x-ref="builderForm" style="display:none"
          x-init="$refs.builderForm = document.getElementById('builder-form')"></span>

    {{-- ── MAIN LAYOUT ──────────────────────────────────────────────── --}}
    <div class="flex gap-5 px-4 sm:px-6 py-5 items-start">

        {{-- ───────────────────── CANVAS ───────────────────────────── --}}
        <div class="flex-1 min-w-0">

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 rounded-2xl px-4 py-3 text-sm text-red-700">
                <ul class="space-y-1">
                    @foreach($errors->all() as $err)
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-red-500 flex-shrink-0"></span>{{ $err }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Empty state --}}
            <div x-show="blocks.length === 0"
                 class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background:#FFF4EA;">
                    <svg class="w-7 h-7" style="color:#FF8528;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zm12-1a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 mb-1">ابنِ صفحتك بالبلوكات</p>
                <p class="text-xs text-gray-400 mb-5">اختر بلوكاً من الأدناه للبدء</p>
                <button type="button" @click="showPicker = true"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold"
                        style="background:#FF8528;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    أضف أول بلوك
                </button>
            </div>

            {{-- Blocks list --}}
            <div id="blocks-canvas" class="space-y-2">
                <template x-for="(block, bIdx) in blocks" :key="block.id">
                    <div :id="'block-' + block.id"
                         :class="selectedId === block.id
                            ? 'border-orange-300 shadow-md ring-1 ring-orange-200'
                            : 'border-gray-100 hover:border-gray-200 hover:shadow-sm'"
                         class="group bg-white rounded-2xl border transition-all duration-200">

                        {{-- Block header --}}
                        <div class="flex items-center gap-3 px-4 py-3">
                            {{-- Drag handle --}}
                            <span class="drag-handle cursor-grab text-gray-300 hover:text-gray-500 transition-colors select-none flex-shrink-0"
                                  title="اسحب للترتيب">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 6a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM8 14a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM8 22a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                            </span>

                            {{-- Type badge --}}
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md flex-shrink-0"
                                  :style="getBlockColor(block.type)"
                                  x-text="getBlockLabel(block.type)"></span>

                            {{-- Preview --}}
                            <span class="flex-1 text-sm text-gray-500 truncate min-w-0"
                                  x-text="getBlockPreview(block, editLang)"></span>

                            {{-- Actions (appear on hover) --}}
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                <button type="button" @click="moveBlock(block.id, 'up')" :disabled="bIdx === 0"
                                        :class="bIdx === 0 ? 'opacity-30 cursor-default' : 'hover:bg-gray-100'"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 transition-colors" title="أعلى">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button type="button" @click="moveBlock(block.id, 'down')" :disabled="bIdx === blocks.length - 1"
                                        :class="bIdx === blocks.length - 1 ? 'opacity-30 cursor-default' : 'hover:bg-gray-100'"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 transition-colors" title="أسفل">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <button type="button" @click="duplicateBlock(block.id)"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors" title="نسخ">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                <button type="button" @click="toggleEdit(block.id)"
                                        :class="selectedId === block.id ? 'bg-orange-100 text-orange-500' : 'text-gray-400 hover:bg-gray-100 hover:text-gray-700'"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors" title="تعديل">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button type="button" @click="removeBlock(block.id)"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="حذف">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- ── Inline Editor ── --}}
                        <div x-show="selectedId === block.id" x-collapse class="border-t border-gray-100">
                            <div class="p-4 sm:p-5">

                                {{-- Lang tabs inside editor --}}
                                <div class="flex items-center gap-1 mb-5">
                                    <span class="text-xs text-gray-400 me-2">اللغة:</span>
                                    <template x-for="l in ['ar','en','nl','de']" :key="l">
                                        <button type="button" @click="editLang = l"
                                                :class="editLang === l
                                                    ? 'text-white shadow-sm'
                                                    : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                                :style="editLang === l ? 'background:#FF8528;' : ''"
                                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all"
                                                x-text="l.toUpperCase()"></button>
                                    </template>
                                </div>

                                {{-- ── HERO EDITOR ── --}}
                                <div x-show="block.type === 'hero'" class="space-y-4">
                                    <div class="grid gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">العنوان الرئيسي</label>
                                            <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                   x-model="block.content.title[editLang]"
                                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow"
                                                   placeholder="اكتب عنواناً جذاباً...">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">العنوان الفرعي</label>
                                            <textarea :dir="editLang==='ar'?'rtl':'ltr'"
                                                      x-model="block.content.subtitle[editLang]"
                                                      rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none transition-shadow"
                                                      placeholder="وصف مختصر يشرح الفكرة..."></textarea>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">نص الزر</label>
                                                <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                       x-model="block.content.btn_text[editLang]"
                                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow"
                                                       placeholder="ابدأ الآن">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">رابط الزر</label>
                                                <input x-model="block.content.btn_url"
                                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow font-mono"
                                                       placeholder="/contact">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-100 pt-4">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">الإعدادات</p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1.5">لون الخلفية</label>
                                                <div class="flex gap-2 items-center">
                                                    <input type="color" x-model="block.settings.bg_color"
                                                           class="w-9 h-9 rounded-xl border border-gray-200 cursor-pointer p-0.5">
                                                    <input x-model="block.settings.bg_color"
                                                           class="flex-1 px-2.5 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1.5">لون النص</label>
                                                <div class="flex gap-2 items-center">
                                                    <input type="color" x-model="block.settings.text_color"
                                                           class="w-9 h-9 rounded-xl border border-gray-200 cursor-pointer p-0.5">
                                                    <input x-model="block.settings.text_color"
                                                           class="flex-1 px-2.5 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="block text-xs text-gray-500 mb-1.5">صورة الخلفية (URL)</label>
                                            <input x-model="block.settings.bg_image"
                                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                   placeholder="https://... (اختياري)">
                                        </div>
                                        <div class="mt-3 grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1.5">الارتفاع</label>
                                                <div class="flex gap-1.5">
                                                    <template x-for="h in [{v:'sm',l:'صغير'},{v:'md',l:'متوسط'},{v:'lg',l:'كبير'},{v:'full',l:'كامل'}]" :key="h.v">
                                                        <button type="button" @click="block.settings.height = h.v"
                                                                :class="block.settings.height===h.v ? 'text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                                                :style="block.settings.height===h.v ? 'background:#FF8528;' : ''"
                                                                class="flex-1 py-1.5 rounded-lg text-[10px] font-semibold transition-all"
                                                                x-text="h.l"></button>
                                                    </template>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-500 mb-1.5">المحاذاة</label>
                                                <div class="flex gap-1.5">
                                                    <template x-for="a in [{v:'right',l:'⬅'},{v:'center',l:'↔'},{v:'left',l:'➡'}]" :key="a.v">
                                                        <button type="button" @click="block.settings.align = a.v"
                                                                :class="block.settings.align===a.v ? 'text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                                                :style="block.settings.align===a.v ? 'background:#FF8528;' : ''"
                                                                class="flex-1 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                                                x-text="a.l"></button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ── HEADING EDITOR ── --}}
                                <div x-show="block.type === 'heading'" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">نص العنوان</label>
                                        <input :dir="editLang==='ar'?'rtl':'ltr'"
                                               x-model="block.content.text[editLang]"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow"
                                               placeholder="عنوان القسم...">
                                    </div>
                                    <div class="border-t border-gray-100 pt-4 grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">المستوى</label>
                                            <select x-model="block.settings.level"
                                                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                <option value="h1">H1 — رئيسي</option>
                                                <option value="h2">H2 — ثانوي</option>
                                                <option value="h3">H3 — ثالثي</option>
                                                <option value="h4">H4 — رابع</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">المحاذاة</label>
                                            <select x-model="block.settings.align"
                                                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                <option value="right">يمين</option>
                                                <option value="center">وسط</option>
                                                <option value="left">يسار</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">اللون</label>
                                            <input type="color" x-model="block.settings.color"
                                                   class="w-full h-10 rounded-xl border border-gray-200 cursor-pointer p-1">
                                        </div>
                                    </div>
                                </div>

                                {{-- ── PARAGRAPH EDITOR ── --}}
                                <div x-show="block.type === 'paragraph'" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">النص</label>
                                        <textarea :dir="editLang==='ar'?'rtl':'ltr'"
                                                  x-model="block.content.text[editLang]"
                                                  rows="5" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-y transition-shadow leading-relaxed"
                                                  placeholder="اكتب المحتوى النصي هنا..."></textarea>
                                    </div>
                                    <div class="border-t border-gray-100 pt-3 flex items-center gap-4">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">المحاذاة</label>
                                            <select x-model="block.settings.align"
                                                    class="px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                <option value="right">يمين</option>
                                                <option value="center">وسط</option>
                                                <option value="left">يسار</option>
                                                <option value="justify">ضبط</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">الحجم</label>
                                            <select x-model="block.settings.size"
                                                    class="px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                <option value="sm">صغير</option>
                                                <option value="base">متوسط</option>
                                                <option value="lg">كبير</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- ── IMAGE EDITOR ── --}}
                                <div x-show="block.type === 'image'" class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">رابط الصورة</label>
                                        <div class="flex gap-2">
                                            <input x-model="block.content.url"
                                                   class="flex-1 px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 font-mono transition-shadow"
                                                   placeholder="https://...">
                                        </div>
                                        <div x-show="block.content.url" class="mt-2">
                                            <img :src="block.content.url" class="h-24 rounded-xl object-cover border border-gray-100" loading="lazy">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">النص البديل</label>
                                            <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                   x-model="block.content.alt[editLang]"
                                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                   placeholder="وصف الصورة">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">التعليق</label>
                                            <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                   x-model="block.content.caption[editLang]"
                                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                   placeholder="تعليق الصورة (اختياري)">
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-100 pt-3 grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">الحجم</label>
                                            <select x-model="block.settings.size"
                                                    class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                <option value="full">كامل العرض</option>
                                                <option value="lg">كبير</option>
                                                <option value="md">متوسط</option>
                                                <option value="sm">صغير</option>
                                            </select>
                                        </div>
                                        <div class="flex items-end">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" x-model="block.settings.rounded"
                                                       class="w-4 h-4 rounded accent-orange-500">
                                                <span class="text-xs text-gray-600">حواف دائرية</span>
                                            </label>
                                        </div>
                                        <div class="flex items-end">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" x-model="block.settings.shadow"
                                                       class="w-4 h-4 rounded accent-orange-500">
                                                <span class="text-xs text-gray-600">ظل</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- ── TWO COLUMNS EDITOR ── --}}
                                <div x-show="block.type === 'two_columns'" class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        {{-- Left column --}}
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-bold text-gray-600">العمود الأيمن</span>
                                                <select x-model="block.content.left_type"
                                                        class="px-2 py-1 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                    <option value="text">نص</option>
                                                    <option value="image">صورة</option>
                                                </select>
                                            </div>
                                            <div x-show="block.content.left_type === 'text'">
                                                <textarea :dir="editLang==='ar'?'rtl':'ltr'"
                                                          x-model="block.content.left_text[editLang]"
                                                          rows="4" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"
                                                          placeholder="محتوى العمود..."></textarea>
                                            </div>
                                            <div x-show="block.content.left_type === 'image'">
                                                <input x-model="block.content.left_image"
                                                       class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                       placeholder="https://...">
                                                <img x-show="block.content.left_image" :src="block.content.left_image"
                                                     class="mt-2 h-20 w-full object-cover rounded-xl border border-gray-100">
                                            </div>
                                        </div>
                                        {{-- Right column --}}
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-bold text-gray-600">العمود الأيسر</span>
                                                <select x-model="block.content.right_type"
                                                        class="px-2 py-1 rounded-lg border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                    <option value="text">نص</option>
                                                    <option value="image">صورة</option>
                                                </select>
                                            </div>
                                            <div x-show="block.content.right_type === 'text'">
                                                <textarea :dir="editLang==='ar'?'rtl':'ltr'"
                                                          x-model="block.content.right_text[editLang]"
                                                          rows="4" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"
                                                          placeholder="محتوى العمود..."></textarea>
                                            </div>
                                            <div x-show="block.content.right_type === 'image'">
                                                <input x-model="block.content.right_image"
                                                       class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                       placeholder="https://...">
                                                <img x-show="block.content.right_image" :src="block.content.right_image"
                                                     class="mt-2 h-20 w-full object-cover rounded-xl border border-gray-100">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-100 pt-3">
                                        <label class="block text-xs text-gray-500 mb-1.5">نسبة العرض</label>
                                        <div class="flex gap-2">
                                            <template x-for="r in ['1/1','2/1','1/2','3/2','2/3']" :key="r">
                                                <button type="button" @click="block.settings.ratio = r"
                                                        :class="block.settings.ratio===r ? 'text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                                                        :style="block.settings.ratio===r ? 'background:#FF8528;' : ''"
                                                        class="flex-1 py-1.5 rounded-lg text-[10px] font-bold transition-all"
                                                        x-text="r"></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- ── CTA EDITOR ── --}}
                                <div x-show="block.type === 'cta'" class="space-y-4">
                                    <div class="grid gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">العنوان</label>
                                            <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                   x-model="block.content.title[editLang]"
                                                   class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow"
                                                   placeholder="هل أنت مستعد للانطلاق؟">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">النص</label>
                                            <textarea :dir="editLang==='ar'?'rtl':'ltr'"
                                                      x-model="block.content.text[editLang]"
                                                      rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none transition-shadow"
                                                      placeholder="وصف مختصر يشجع على الفعل..."></textarea>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">نص الزر</label>
                                                <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                       x-model="block.content.btn_text[editLang]"
                                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow"
                                                       placeholder="تواصل معنا">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">رابط الزر</label>
                                                <input x-model="block.content.btn_url"
                                                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow"
                                                       placeholder="/contact">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-100 pt-3 grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">لون الخلفية</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="color" x-model="block.settings.bg_color"
                                                       class="w-9 h-9 rounded-xl border border-gray-200 cursor-pointer p-0.5">
                                                <input x-model="block.settings.bg_color"
                                                       class="flex-1 px-2.5 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1.5">لون النص</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="color" x-model="block.settings.text_color"
                                                       class="w-9 h-9 rounded-xl border border-gray-200 cursor-pointer p-0.5">
                                                <input x-model="block.settings.text_color"
                                                       class="flex-1 px-2.5 py-2 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ── FEATURES EDITOR ── --}}
                                <div x-show="block.type === 'features'" class="space-y-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-500">العناصر</span>
                                        <div class="flex items-center gap-3">
                                            <label class="text-xs text-gray-500">الأعمدة:</label>
                                            <div class="flex gap-1">
                                                <template x-for="c in [2,3,4]" :key="c">
                                                    <button type="button" @click="block.settings.columns = c"
                                                            :class="block.settings.columns===c ? 'text-white' : 'bg-gray-100 text-gray-500'"
                                                            :style="block.settings.columns===c ? 'background:#FF8528;' : ''"
                                                            class="w-7 h-7 rounded-lg text-xs font-bold transition-all"
                                                            x-text="c"></button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <template x-for="(item, itemIdx) in block.content.items" :key="itemIdx">
                                        <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <input x-model="item.icon"
                                                           class="w-10 h-9 text-center rounded-lg border border-gray-200 text-lg focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                           placeholder="⭐">
                                                    <span class="text-xs text-gray-400" x-text="'عنصر ' + (itemIdx + 1)"></span>
                                                </div>
                                                <button type="button" @click="removeFeatureItem(block.id, itemIdx)"
                                                        class="w-6 h-6 flex items-center justify-center rounded-lg text-red-400 hover:bg-red-50 transition-colors">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                   x-model="item.title[editLang]"
                                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                   placeholder="عنوان الميزة">
                                            <textarea :dir="editLang==='ar'?'rtl':'ltr'"
                                                      x-model="item.text[editLang]"
                                                      rows="2" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs resize-none focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                      placeholder="وصف الميزة..."></textarea>
                                        </div>
                                    </template>
                                    <button type="button" @click="addFeatureItem(block.id)"
                                            class="w-full py-2.5 rounded-xl border-2 border-dashed border-gray-200 text-xs text-gray-400 hover:border-orange-300 hover:text-orange-400 transition-colors font-medium">
                                        + أضف ميزة
                                    </button>
                                </div>

                                {{-- ── FAQ EDITOR ── --}}
                                <div x-show="block.type === 'faq'" class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">عنوان القسم (اختياري)</label>
                                        <input :dir="editLang==='ar'?'rtl':'ltr'"
                                               x-model="block.content.title[editLang]"
                                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                                               placeholder="الأسئلة الشائعة">
                                    </div>
                                    <div class="mt-3 space-y-2">
                                        <template x-for="(item, itemIdx) in block.content.items" :key="itemIdx">
                                            <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-400 font-medium" x-text="'س ' + (itemIdx+1)"></span>
                                                    <button type="button" @click="removeFaqItem(block.id, itemIdx)"
                                                            class="w-6 h-6 flex items-center justify-center rounded-lg text-red-400 hover:bg-red-50 transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                       x-model="item.question[editLang]"
                                                       class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                       placeholder="السؤال...">
                                                <textarea :dir="editLang==='ar'?'rtl':'ltr'"
                                                          x-model="item.answer[editLang]"
                                                          rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs resize-none focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                          placeholder="الإجابة..."></textarea>
                                            </div>
                                        </template>
                                        <button type="button" @click="addFaqItem(block.id)"
                                                class="w-full py-2.5 rounded-xl border-2 border-dashed border-gray-200 text-xs text-gray-400 hover:border-orange-300 hover:text-orange-400 transition-colors font-medium">
                                            + أضف سؤال
                                        </button>
                                    </div>
                                </div>

                                {{-- ── DIVIDER EDITOR ── --}}
                                <div x-show="block.type === 'divider'" class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1.5">المسافة</label>
                                        <div class="flex gap-1">
                                            <template x-for="h in [{v:'sm',l:'ص'},{v:'md',l:'م'},{v:'lg',l:'ك'},{v:'xl',l:'ع'}]" :key="h.v">
                                                <button type="button" @click="block.settings.height = h.v"
                                                        :class="block.settings.height===h.v ? 'text-white' : 'bg-gray-100 text-gray-500'"
                                                        :style="block.settings.height===h.v ? 'background:#FF8528;' : ''"
                                                        class="flex-1 py-2 rounded-lg text-xs font-bold transition-all"
                                                        x-text="h.l"></button>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1.5">النوع</label>
                                        <select x-model="block.settings.style"
                                                class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                            <option value="none">بدون خط</option>
                                            <option value="line">خط</option>
                                            <option value="dots">نقاط</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1.5">اللون</label>
                                        <input type="color" x-model="block.settings.color"
                                               class="w-full h-10 rounded-xl border border-gray-200 cursor-pointer p-1">
                                    </div>
                                </div>

                                {{-- ── HTML EDITOR ── --}}
                                <div x-show="block.type === 'html'">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">كود HTML</label>
                                    <textarea x-model="block.content.code"
                                              rows="8" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-orange-300 resize-y"
                                              dir="ltr" placeholder="<div>...</div>"></textarea>
                                    <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        يُعرض كما هو — تأكد من سلامة الكود
                                    </p>
                                </div>

                            </div>

                            {{-- Add block below this one --}}
                            <div class="px-4 pb-4">
                                <button type="button" @click="showPicker = true; pickerInsertAfter = block.id"
                                        class="w-full py-2 rounded-xl border border-dashed border-gray-200 text-xs text-gray-400 hover:border-orange-300 hover:text-orange-400 transition-colors font-medium flex items-center justify-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    أضف بلوك بعده
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Add block button --}}
            <div x-show="blocks.length > 0" class="mt-3">
                <button type="button" @click="showPicker = true; pickerInsertAfter = null"
                        class="w-full py-3 rounded-2xl border-2 border-dashed border-gray-200 text-sm text-gray-400 hover:border-orange-300 hover:text-orange-500 hover:bg-orange-50/50 transition-all font-medium flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    أضف بلوكاً جديداً
                </button>
            </div>
        </div>

        {{-- ───────────────── PAGE SETTINGS PANEL ──────────────────── --}}
        <div class="w-72 flex-shrink-0 space-y-4">

            {{-- Titles --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex border-b border-gray-100">
                    @foreach(['ar' => 'AR','en' => 'EN','nl' => 'NL','de' => 'DE'] as $lk => $lv)
                    <button type="button" @click="titleTab = '{{ $lk }}'"
                            :class="titleTab === '{{ $lk }}'
                                ? 'border-b-2 font-semibold text-gray-900'
                                : 'text-gray-400 hover:text-gray-600'"
                            :style="titleTab === '{{ $lk }}' ? 'border-color:#FF8528;color:#FF8528;' : ''"
                            class="flex-1 py-2.5 text-xs transition-colors">
                        {{ $lv }}
                    </button>
                    @endforeach
                </div>
                <div class="p-4">
                    @foreach(['ar' => ['العربية','rtl'], 'en' => ['الإنجليزية','ltr'], 'nl' => ['الهولندية','ltr'], 'de' => ['الألمانية','ltr']] as $lk => [$llabel, $ldir])
                    <div x-show="titleTab === '{{ $lk }}'">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                            العنوان ({{ $llabel }})
                            @if($lk === 'ar') <span class="text-red-500">*</span> @endif
                        </label>
                        <input x-model="cfg.titles.{{ $lk }}"
                               dir="{{ $ldir }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 transition-shadow"
                               placeholder="عنوان الصفحة">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Publish --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-3">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">النشر</h4>
                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">الحالة</label>
                    <select x-model="cfg.status"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <option value="draft">مسودة</option>
                        <option value="published">منشور</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">ترتيب العرض</label>
                    <input type="number" x-model="cfg.sort_order"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" x-model="cfg.show_in_nav"
                           class="w-4 h-4 rounded border-gray-300 accent-orange-500">
                    <span class="text-sm text-gray-700">إظهار في القائمة</span>
                </label>
            </div>

            {{-- URL --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-2">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">الرابط</h4>
                <div class="flex items-center rounded-xl border border-gray-200 overflow-hidden focus-within:ring-2 focus-within:ring-orange-300">
                    <span class="px-3 py-2.5 bg-gray-50 text-gray-400 text-xs border-e border-gray-200">/p/</span>
                    <input x-model="cfg.slug"
                           class="flex-1 px-3 py-2.5 text-sm focus:outline-none font-mono bg-white"
                           placeholder="page-slug">
                </div>
                <p class="text-xs text-gray-400">اتركه فارغاً للإنشاء تلقائياً</p>
            </div>

            {{-- Template --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-2">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">القالب</h4>
                <select x-model="cfg.template"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="default">افتراضي</option>
                    <option value="full-width">عرض كامل</option>
                    <option value="landing">صفحة هبوط</option>
                </select>
            </div>

            {{-- SEO --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 space-y-3">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">SEO</h4>
                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">عنوان ميتا</label>
                    <input x-model="cfg.meta_title"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                           placeholder="يستبدل العنوان في محركات البحث">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1.5">وصف ميتا</label>
                    <textarea x-model="cfg.meta_desc" rows="3"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 resize-none"
                              placeholder="150–160 حرف..."></textarea>
                    <p class="text-xs text-gray-400 mt-1" x-text="(cfg.meta_desc || '').length + ' / 160'"></p>
                </div>
            </div>

            {{-- Save --}}
            <button type="button" @click="$refs.builderForm.submit()"
                    class="w-full py-3 rounded-2xl text-white font-semibold text-sm transition-colors"
                    style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                {{ $isCreate ? 'إنشاء الصفحة' : 'حفظ التغييرات' }}
            </button>
        </div>
    </div>

    {{-- ── BLOCK PICKER MODAL ───────────────────────────────────────── --}}
    <div x-show="showPicker" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.5);"
         @click.self="showPicker = false">

        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">أضف بلوك</h3>
                    <p class="text-xs text-gray-400 mt-0.5">اختر نوع المحتوى الذي تريد إضافته</p>
                </div>
                <button type="button" @click="showPicker = false"
                        class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-5 overflow-y-auto max-h-[60vh]">

                {{-- Category: Basics --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">الأساسيات</p>
                <div class="grid grid-cols-3 gap-2.5 mb-5">
                    @foreach([
                        ['hero',       'Hero',    '#1a1a2e', 'M3 4h18v16H3z M3 8h18', 'قسم رئيسي كبير مع عنوان وزر'],
                        ['heading',    'عنوان',   '#3b82f6', 'M4 6h16M4 10h12M4 14h8', 'H1 إلى H4 مع تنسيق'],
                        ['paragraph',  'نص',      '#6b7280', 'M4 6h16M4 10h16M4 14h12M4 18h8', 'فقرة نصية كاملة'],
                    ] as [$type, $label, $color, $iconPath, $desc])
                    <button type="button"
                            @click="addBlock('{{ $type }}', pickerInsertAfter)"
                            class="group flex flex-col items-center gap-2 p-3.5 rounded-2xl border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all text-center bg-gray-50 hover:bg-orange-50/40">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:{{ $color }}18;">
                            <svg class="w-5 h-5" style="color:{{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $iconPath }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700 group-hover:text-orange-600 transition-colors">{{ $label }}</p>
                            <p class="text-[9px] text-gray-400 mt-0.5 leading-tight">{{ $desc }}</p>
                        </div>
                    </button>
                    @endforeach
                </div>

                {{-- Category: Media & Layout --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">الوسائط والتخطيط</p>
                <div class="grid grid-cols-3 gap-2.5 mb-5">
                    @foreach([
                        ['image',       'صورة',    '#10b981', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'صورة مع تعليق'],
                        ['two_columns', 'عمودان',  '#8b5cf6', 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7', 'تخطيط عمودين'],
                        ['divider',    'فاصل',    '#94a3b8', 'M5 12h14', 'مسافة أو خط فاصل'],
                    ] as [$type, $label, $color, $iconPath, $desc])
                    <button type="button"
                            @click="addBlock('{{ $type }}', pickerInsertAfter)"
                            class="group flex flex-col items-center gap-2 p-3.5 rounded-2xl border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all text-center bg-gray-50 hover:bg-orange-50/40">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:{{ $color }}18;">
                            <svg class="w-5 h-5" style="color:{{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $iconPath }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700 group-hover:text-orange-600 transition-colors">{{ $label }}</p>
                            <p class="text-[9px] text-gray-400 mt-0.5 leading-tight">{{ $desc }}</p>
                        </div>
                    </button>
                    @endforeach
                </div>

                {{-- Category: Marketing --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">التسويق</p>
                <div class="grid grid-cols-3 gap-2.5 mb-5">
                    @foreach([
                        ['cta',      'CTA',       '#f97316', 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'قسم دعوة للتواصل'],
                        ['features', 'مميزات',    '#f59e0b', 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'شبكة ميزات بأيقونات'],
                        ['faq',      'أسئلة',     '#0ea5e9', 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'أسئلة شائعة accordion'],
                    ] as [$type, $label, $color, $iconPath, $desc])
                    <button type="button"
                            @click="addBlock('{{ $type }}', pickerInsertAfter)"
                            class="group flex flex-col items-center gap-2 p-3.5 rounded-2xl border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all text-center bg-gray-50 hover:bg-orange-50/40">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:{{ $color }}18;">
                            <svg class="w-5 h-5" style="color:{{ $color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $iconPath }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700 group-hover:text-orange-600 transition-colors">{{ $label }}</p>
                            <p class="text-[9px] text-gray-400 mt-0.5 leading-tight">{{ $desc }}</p>
                        </div>
                    </button>
                    @endforeach
                </div>

                {{-- Category: Advanced --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">متقدم</p>
                <div class="grid grid-cols-3 gap-2.5">
                    <button type="button"
                            @click="addBlock('html', pickerInsertAfter)"
                            class="group flex flex-col items-center gap-2 p-3.5 rounded-2xl border border-gray-100 hover:border-orange-200 hover:shadow-md transition-all text-center bg-gray-50 hover:bg-orange-50/40">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#ef444418;">
                            <svg class="w-5 h-5" style="color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700 group-hover:text-orange-600 transition-colors">HTML</p>
                            <p class="text-[9px] text-gray-400 mt-0.5 leading-tight">كود HTML مخصص</p>
                        </div>
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
function pageBuilder(initialBlocks, initialCfg) {
    return {
        blocks: [],
        selectedId: null,
        editLang: 'ar',
        titleTab: 'ar',
        showPicker: false,
        pickerInsertAfter: null,
        cfg: initialCfg || {
            titles: {ar:'',en:'',nl:'',de:''},
            slug: '', status: 'draft', template: 'default',
            show_in_nav: false, sort_order: 0, meta_title: '', meta_desc: ''
        },

        init() {
            this.blocks = (initialBlocks || []).map(b => this.mergeDefaults(b));
            this.$nextTick(() => this.initSortable());
        },

        initSortable() {
            const canvas = document.getElementById('blocks-canvas');
            if (!canvas || !window.Sortable) return;
            Sortable.create(canvas, {
                animation: 180,
                handle: '.drag-handle',
                ghostClass: 'opacity-40',
                dragClass: 'shadow-2xl',
                onEnd: (evt) => {
                    const [moved] = this.blocks.splice(evt.oldIndex, 1);
                    this.blocks.splice(evt.newIndex, 0, moved);
                    this.$nextTick(() => this.initSortable());
                }
            });
        },

        getDefaults(type) {
            const ml = () => ({ar:'',en:'',nl:'',de:''});
            const defs = {
                hero: {
                    content: { title:ml(), subtitle:ml(), btn_text:ml(), btn_url:'' },
                    settings: { bg_color:'#0f172a', bg_image:'', text_color:'#ffffff', align:'center', height:'lg' }
                },
                heading: {
                    content: { text:ml() },
                    settings: { level:'h2', align:'center', color:'#111827' }
                },
                paragraph: {
                    content: { text:ml() },
                    settings: { align:'right', size:'base' }
                },
                image: {
                    content: { url:'', alt:ml(), caption:ml() },
                    settings: { size:'full', rounded:true, shadow:false }
                },
                two_columns: {
                    content: { left_type:'text', right_type:'image', left_text:ml(), right_text:ml(), left_image:'', right_image:'' },
                    settings: { ratio:'1/1' }
                },
                cta: {
                    content: { title:ml(), text:ml(), btn_text:ml(), btn_url:'' },
                    settings: { bg_color:'#FF8528', text_color:'#ffffff', align:'center' }
                },
                features: {
                    content: { items:[{icon:'⚡', title:ml(), text:ml()}] },
                    settings: { columns:3 }
                },
                faq: {
                    content: { title:ml(), items:[{question:ml(), answer:ml()}] },
                    settings: { title_align:'center' }
                },
                divider: {
                    content: {},
                    settings: { height:'md', style:'line', color:'#e5e7eb' }
                },
                html: {
                    content: { code:'' },
                    settings: {}
                }
            };
            const d = defs[type] || {content:{}, settings:{}};
            return JSON.parse(JSON.stringify({type, ...d}));
        },

        mergeDefaults(block) {
            const def = this.getDefaults(block.type);
            return {
                ...def,
                ...block,
                content: this.deepMerge(def.content || {}, block.content || {}),
                settings: this.deepMerge(def.settings || {}, block.settings || {})
            };
        },

        deepMerge(target, source) {
            const result = Object.assign({}, target);
            for (const k of Object.keys(source)) {
                if (source[k] !== null && typeof source[k] === 'object' && !Array.isArray(source[k])) {
                    result[k] = this.deepMerge(target[k] || {}, source[k]);
                } else {
                    result[k] = source[k];
                }
            }
            return result;
        },

        addBlock(type, afterId = null) {
            const block = {
                id: 'b_' + Date.now() + '_' + Math.random().toString(36).slice(2,6),
                ...this.getDefaults(type)
            };
            if (afterId !== null) {
                const idx = this.blocks.findIndex(b => b.id === afterId);
                this.blocks.splice(idx + 1, 0, block);
            } else {
                this.blocks.push(block);
            }
            this.selectedId = block.id;
            this.showPicker = false;
            this.pickerInsertAfter = null;
            this.$nextTick(() => {
                const el = document.getElementById('block-' + block.id);
                if (el) el.scrollIntoView({behavior:'smooth', block:'center'});
                this.initSortable();
            });
        },

        removeBlock(id) {
            if (!confirm('حذف هذا البلوك؟')) return;
            this.blocks = this.blocks.filter(b => b.id !== id);
            if (this.selectedId === id) this.selectedId = null;
            this.$nextTick(() => this.initSortable());
        },

        duplicateBlock(id) {
            const orig = this.blocks.find(b => b.id === id);
            if (!orig) return;
            const clone = JSON.parse(JSON.stringify(orig));
            clone.id = 'b_' + Date.now() + '_' + Math.random().toString(36).slice(2,6);
            const idx = this.blocks.findIndex(b => b.id === id);
            this.blocks.splice(idx + 1, 0, clone);
            this.selectedId = clone.id;
            this.$nextTick(() => this.initSortable());
        },

        moveBlock(id, dir) {
            const idx = this.blocks.findIndex(b => b.id === id);
            if (dir === 'up' && idx > 0) {
                const [m] = this.blocks.splice(idx, 1);
                this.blocks.splice(idx - 1, 0, m);
            } else if (dir === 'down' && idx < this.blocks.length - 1) {
                const [m] = this.blocks.splice(idx, 1);
                this.blocks.splice(idx + 1, 0, m);
            }
            this.$nextTick(() => this.initSortable());
        },

        toggleEdit(id) {
            this.selectedId = this.selectedId === id ? null : id;
        },

        addFeatureItem(blockId) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            block.content.items.push({icon:'⭐', title:{ar:'',en:'',nl:'',de:''}, text:{ar:'',en:'',nl:'',de:''}});
        },

        removeFeatureItem(blockId, idx) {
            const block = this.blocks.find(b => b.id === blockId);
            if (block && block.content.items.length > 1) block.content.items.splice(idx, 1);
        },

        addFaqItem(blockId) {
            const block = this.blocks.find(b => b.id === blockId);
            if (!block) return;
            block.content.items.push({question:{ar:'',en:'',nl:'',de:''}, answer:{ar:'',en:'',nl:'',de:''}});
        },

        removeFaqItem(blockId, idx) {
            const block = this.blocks.find(b => b.id === blockId);
            if (block && block.content.items.length > 1) block.content.items.splice(idx, 1);
        },

        getBlockLabel(type) {
            return {hero:'Hero', heading:'عنوان', paragraph:'نص', image:'صورة',
                    two_columns:'عمودان', cta:'CTA', features:'مميزات',
                    faq:'أسئلة', divider:'فاصل', html:'HTML'}[type] || type;
        },

        getBlockColor(type) {
            return {
                hero:        'background:#0f172a18;color:#0f172a',
                heading:     'background:#3b82f618;color:#1d4ed8',
                paragraph:   'background:#6b728018;color:#374151',
                image:       'background:#10b98118;color:#047857',
                two_columns: 'background:#8b5cf618;color:#6d28d9',
                cta:         'background:#f9731618;color:#c2410c',
                features:    'background:#f59e0b18;color:#b45309',
                faq:         'background:#0ea5e918;color:#0369a1',
                divider:     'background:#e5e7eb;color:#6b7280',
                html:        'background:#ef444418;color:#b91c1c'
            }[type] || 'background:#f3f4f6;color:#374151';
        },

        getBlockPreview(block, lang) {
            const c = block.content || {};
            const t = (field) => (c[field]?.[lang] || c[field]?.ar || '');
            switch (block.type) {
                case 'hero':        return t('title') || 'Hero Section';
                case 'heading':     return t('text') || 'عنوان';
                case 'paragraph':   return (t('text') || 'فقرة نصية').slice(0,60);
                case 'image':       return c.url ? '🖼 ' + c.url.split('/').pop() : 'صورة';
                case 'two_columns': return 'تخطيط عمودين';
                case 'cta':         return t('title') || 'Call to Action';
                case 'features':    return (c.items?.length || 0) + ' مميزة';
                case 'faq':         return (c.items?.length || 0) + ' سؤال';
                case 'divider':     return '── فاصل ──';
                case 'html':        return 'HTML مخصص';
                default:            return block.type;
            }
        }
    };
}
</script>
@endpush
