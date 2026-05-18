@extends('layouts.admin')
@section('title', 'قائمة: ' . $menu->name)
@section('page-title', 'بناء القوائم')

@section('content')
<div x-data="menuBuilder({{ $menu->id }}, @json($items), @json($pages), @json($categories))"
     @keydown.escape.window="editingId = null; addTab = 'custom'"
     class="-mt-6 -mx-4 sm:-mx-6">

    {{-- ── TOP BAR ──────────────────────────────────────────────────── --}}
    <div class="sticky top-14 z-30 px-4 sm:px-6 py-3 border-b flex items-center gap-3"
         style="background:#fff; border-color:#e2e8f0; box-shadow:0 1px 0 #f8fafc;">

        <a href="{{ route('admin.menus.index') }}"
           class="w-8 h-8 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:text-gray-700 hover:bg-gray-50 transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-900">{{ $menu->name }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                      style="background:#FFF4EA; color:#FF8528;">
                    {{ ['header'=>'الرأس','footer'=>'التذييل','custom'=>'مخصص'][$menu->location] ?? $menu->location }}
                </span>
            </div>
            <p class="text-xs text-gray-400 mt-0.5" x-text="items.length + ' عنصر'"></p>
        </div>

        {{-- Save indicator --}}
        <div x-show="saving" x-cloak class="flex items-center gap-1.5 text-xs text-gray-400">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            جارٍ الحفظ...
        </div>
        <div x-show="saved && !saving" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="flex items-center gap-1.5 text-xs text-green-600">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            تم الحفظ
        </div>

        {{-- Edit menu name --}}
        <button @click="showMenuSettings = !showMenuSettings"
                class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            الإعدادات
        </button>
    </div>

    {{-- Menu settings inline panel --}}
    <div x-show="showMenuSettings" x-cloak x-collapse
         class="border-b border-gray-100 bg-gray-50/80 px-4 sm:px-6 py-4">
        <form method="POST" action="{{ route('admin.menus.update', $menu) }}" class="flex items-end gap-4 max-w-lg">
            @csrf @method('PUT')
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">اسم القائمة</label>
                <input type="text" name="name" value="{{ $menu->name }}"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div class="w-40">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">الموقع</label>
                <select name="location"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                    <option value="header"  {{ $menu->location === 'header'  ? 'selected' : '' }}>الرأس</option>
                    <option value="footer"  {{ $menu->location === 'footer'  ? 'selected' : '' }}>التذييل</option>
                    <option value="custom"  {{ $menu->location === 'custom'  ? 'selected' : '' }}>مخصص</option>
                </select>
            </div>
            <button type="submit"
                    class="px-4 py-2.5 rounded-xl text-white text-sm font-semibold transition-colors flex-shrink-0"
                    style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                حفظ
            </button>
        </form>
    </div>

    {{-- ── MAIN LAYOUT ──────────────────────────────────────────────── --}}
    <div class="flex gap-5 px-4 sm:px-6 py-5 items-start">

        {{-- ── LEFT PANEL: Add items ───────────────────────────────── --}}
        <div class="w-72 flex-shrink-0 space-y-4">

            {{-- Source tabs --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex border-b border-gray-100">
                    @foreach(['custom' => 'رابط مخصص', 'pages' => 'الصفحات', 'categories' => 'التصنيفات'] as $tab => $label)
                    <button type="button" @click="addTab = '{{ $tab }}'"
                            :class="addTab === '{{ $tab }}'
                                ? 'border-b-2 font-bold text-gray-900'
                                : 'text-gray-400 hover:text-gray-600'"
                            :style="addTab === '{{ $tab }}' ? 'border-color:#FF8528; color:#FF8528;' : ''"
                            class="flex-1 py-3 text-xs transition-colors">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>

                {{-- Custom URL tab --}}
                <div x-show="addTab === 'custom'" class="p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">الرابط (URL)</label>
                        <input x-model="newItem.url" type="text"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-orange-300"
                               placeholder="/ أو https://..."
                               @keydown.enter.prevent="submitNewItem()">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">
                            التسمية (عربي) <span class="text-red-400">*</span>
                        </label>
                        <input x-model="newItem.label_ar" type="text" dir="rtl"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                               placeholder="مثال: الرئيسية"
                               @keydown.enter.prevent="submitNewItem()">
                    </div>
                    <div class="grid grid-cols-3 gap-1.5">
                        @foreach(['en' => 'EN','nl' => 'NL','de' => 'DE'] as $lk => $lv)
                        <div>
                            <label class="block text-[10px] font-semibold text-gray-400 mb-1">{{ $lv }}</label>
                            <input x-model="newItem.label_{{ $lk }}" type="text"
                                   class="w-full px-2.5 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300"
                                   placeholder="{{ ['en'=>'Home','nl'=>'Home','de'=>'Startseite'][$lk] }}">
                        </div>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">يفتح في</label>
                            <select x-model="newItem.target"
                                    class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                <option value="_self">نفس الصفحة</option>
                                <option value="_blank">نافذة جديدة</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">أيقونة (emoji)</label>
                            <input x-model="newItem.icon" type="text"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-center focus:outline-none focus:ring-2 focus:ring-orange-300"
                                   placeholder="🏠">
                        </div>
                    </div>
                    <button type="button" @click="submitNewItem()"
                            :disabled="!newItem.url || !newItem.label_ar || submitting"
                            :class="(!newItem.url || !newItem.label_ar) ? 'opacity-50 cursor-default' : 'hover:opacity-90'"
                            class="w-full py-2.5 rounded-xl text-white text-sm font-semibold transition-all"
                            style="background:#FF8528;">
                        <span x-show="!submitting">إضافة إلى القائمة</span>
                        <span x-show="submitting" x-cloak>جارٍ الإضافة...</span>
                    </button>
                </div>

                {{-- Pages tab --}}
                <div x-show="addTab === 'pages'" class="p-3">
                    <div class="space-y-1 max-h-64 overflow-y-auto">
                        @forelse ($pages as $pg)
                        <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 cursor-pointer group">
                            <input type="checkbox" :value="@json(['label_ar'=>$pg->title_ar,'label_en'=>$pg->title_en ?? '','url'=>'/p/'.$pg->slug])"
                                   x-model="selectedSources"
                                   class="w-4 h-4 rounded accent-orange-500 flex-shrink-0">
                            <div class="min-w-0">
                                <p class="text-sm text-gray-800 truncate">{{ $pg->title_ar }}</p>
                                <p class="text-xs text-gray-400 font-mono">/p/{{ $pg->slug }}</p>
                            </div>
                        </label>
                        @empty
                        <p class="text-center text-xs text-gray-400 py-6">لا توجد صفحات منشورة</p>
                        @endforelse
                    </div>
                    @if ($pages->isNotEmpty())
                    <button type="button" @click="addFromSources()"
                            :disabled="selectedSources.length === 0 || submitting"
                            :class="selectedSources.length === 0 ? 'opacity-50 cursor-default' : ''"
                            class="mt-3 w-full py-2.5 rounded-xl text-white text-sm font-semibold transition-all"
                            style="background:#FF8528;">
                        إضافة المحدد
                        <span x-show="selectedSources.length > 0" x-text="'(' + selectedSources.length + ')'"></span>
                    </button>
                    @endif
                </div>

                {{-- Categories tab --}}
                <div x-show="addTab === 'categories'" class="p-3">
                    <div class="space-y-1 max-h-64 overflow-y-auto">
                        @forelse ($categories as $cat)
                        <label class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" :value="@json(['label_ar'=>$cat['title_ar'],'label_en'=>$cat['title_en'] ?? '','url'=>'/'.$cat['slug']])"
                                   x-model="selectedSources"
                                   class="w-4 h-4 rounded accent-orange-500 flex-shrink-0">
                            <div class="min-w-0">
                                <p class="text-sm text-gray-800 truncate">{{ $cat['title_ar'] }}</p>
                                <p class="text-xs text-gray-400 font-mono">/{{ $cat['slug'] }}</p>
                            </div>
                        </label>
                        @empty
                        <p class="text-center text-xs text-gray-400 py-6">لا توجد تصنيفات</p>
                        @endforelse
                    </div>
                    @if ($categories->isNotEmpty())
                    <button type="button" @click="addFromSources()"
                            :disabled="selectedSources.length === 0 || submitting"
                            :class="selectedSources.length === 0 ? 'opacity-50 cursor-default' : ''"
                            class="mt-3 w-full py-2.5 rounded-xl text-white text-sm font-semibold"
                            style="background:#FF8528;">
                        إضافة المحدد
                        <span x-show="selectedSources.length > 0" x-text="'(' + selectedSources.length + ')'"></span>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Help card --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-xs text-blue-700 space-y-1.5">
                <p class="font-bold text-blue-800">كيفية الاستخدام</p>
                <p class="flex items-start gap-1.5"><span>⠿</span> اسحب العناصر لإعادة الترتيب</p>
                <p class="flex items-start gap-1.5"><span>→</span> اضغط لتعشيش عنصر تحت آخر</p>
                <p class="flex items-start gap-1.5"><span>←</span> اضغط لرفع مستوى العنصر</p>
                <p class="flex items-start gap-1.5"><span>✎</span> اضغط لتعديل بيانات العنصر</p>
            </div>
        </div>

        {{-- ── RIGHT PANEL: Menu Tree ──────────────────────────────── --}}
        <div class="flex-1 min-w-0">

            {{-- Empty state --}}
            <div x-show="items.length === 0"
                 class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background:#FFF4EA;">
                    <svg class="w-7 h-7" style="color:#FF8528;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 mb-1">القائمة فارغة</p>
                <p class="text-xs text-gray-400">أضف عناصر من اللوحة اليسرى</p>
            </div>

            {{-- Items tree --}}
            <div x-show="items.length > 0" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">بنية القائمة</h3>
                    <p class="text-xs text-gray-400">اسحب للترتيب • → للتعشيش</p>
                </div>

                <div id="menu-tree" class="divide-y divide-gray-50">
                    <template x-for="(item, idx) in flatTree" :key="item.id">
                        <div>
                            {{-- Item Row --}}
                            <div :class="[
                                    'group flex items-center gap-0 transition-colors',
                                    editingId === item.id ? 'bg-orange-50/60' : 'hover:bg-gray-50'
                                 ]"
                                 :id="'item-' + item.id">

                                {{-- Indentation + nesting line --}}
                                <template x-for="d in item._depth" :key="d">
                                    <div class="w-8 flex-shrink-0 flex items-center justify-center">
                                        <div class="w-px h-full border-s-2 border-gray-100 mx-auto" style="height:54px;"></div>
                                    </div>
                                </template>

                                {{-- Drag handle --}}
                                <div class="drag-handle w-10 h-14 flex items-center justify-center cursor-grab text-gray-300 hover:text-gray-500 transition-colors flex-shrink-0 select-none">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 6a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM8 14a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM8 22a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4z"/>
                                    </svg>
                                </div>

                                {{-- Icon + Label + URL --}}
                                <div class="flex-1 min-w-0 py-3.5 flex items-center gap-3">
                                    <span x-show="item.icon" x-text="item.icon" class="text-lg flex-shrink-0"></span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="item.label_ar"></p>
                                        <p class="text-xs text-gray-400 font-mono truncate mt-0.5 flex items-center gap-1">
                                            <span x-text="item.url"></span>
                                            <span x-show="item.target === '_blank'" class="text-[9px] text-gray-400 border border-gray-200 px-1 rounded">↗</span>
                                        </p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-1 px-3 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0"
                                     :class="editingId === item.id && '!opacity-100'">

                                    {{-- Outdent (←) --}}
                                    <button type="button" @click="outdent(item.id)"
                                            x-show="item.parent_id !== null"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                            title="رفع المستوى">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>

                                    {{-- Indent (→) --}}
                                    <button type="button" @click="indent(item.id)"
                                            x-show="canIndent(item.id)"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                            title="تعشيش">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>

                                    {{-- Edit --}}
                                    <button type="button" @click="editingId = editingId === item.id ? null : item.id"
                                            :class="editingId === item.id
                                                ? 'bg-orange-100 text-orange-500'
                                                : 'text-gray-400 hover:bg-gray-100 hover:text-gray-700'"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors"
                                            title="تعديل">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    {{-- Delete --}}
                                    <button type="button" @click="deleteItem(item.id)"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                            title="حذف">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Inline edit form --}}
                            <div x-show="editingId === item.id" x-collapse
                                 class="border-t border-orange-100 bg-orange-50/40 px-5 py-4">
                                <div class="max-w-lg space-y-3">
                                    {{-- Lang tabs for labels --}}
                                    <div class="flex items-center gap-1 mb-3">
                                        <span class="text-xs text-gray-400 me-2">التسمية:</span>
                                        <template x-for="l in ['ar','en','nl','de']" :key="l">
                                            <button type="button" @click="editLang = l"
                                                    :class="editLang === l ? 'text-white shadow-sm' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-200'"
                                                    :style="editLang === l ? 'background:#FF8528;' : ''"
                                                    class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all"
                                                    x-text="l.toUpperCase()">
                                            </button>
                                        </template>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">
                                                التسمية (<span x-text="editLang.toUpperCase()"></span>)
                                            </label>
                                            <input :dir="editLang==='ar'?'rtl':'ltr'"
                                                   x-model="item['label_' + editLang]"
                                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                   :placeholder="editLang === 'ar' ? 'عربي' : editLang === 'en' ? 'English' : editLang === 'nl' ? 'Nederlands' : 'Deutsch'">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">الرابط (URL)</label>
                                            <input x-model="item.url" dir="ltr"
                                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                   placeholder="/">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">يفتح في</label>
                                            <select x-model="item.target"
                                                    class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                <option value="_self">نفس الصفحة</option>
                                                <option value="_blank">نافذة جديدة</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">أيقونة (emoji)</label>
                                            <input x-model="item.icon"
                                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 text-center focus:outline-none focus:ring-2 focus:ring-orange-300"
                                                   placeholder="🏠">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" @click="updateItem(item)"
                                                    class="w-full py-2 rounded-xl text-white text-xs font-semibold transition-colors"
                                                    style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                                                حفظ التعديل
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="px-5 py-3 border-t border-gray-50 text-xs text-gray-400 text-center">
                    اسحب العناصر لإعادة الترتيب • يتم الحفظ تلقائياً
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
function menuBuilder(menuId, initialItems, pages, categories) {
    return {
        menuId,
        items: initialItems || [],
        pages: pages || [],
        categories: categories || [],
        addTab: 'custom',
        editingId: null,
        editLang: 'ar',
        saving: false,
        saved: false,
        submitting: false,
        showMenuSettings: false,
        selectedSources: [],
        newItem: { label_ar:'', label_en:'', label_nl:'', label_de:'', url:'/', target:'_self', icon:'' },

        get flatTree() {
            // Build depth-annotated flat list preserving sort_order hierarchy
            const result = [];
            const addItem = (item, depth) => {
                result.push({...item, _depth: depth});
                this.items
                    .filter(c => c.parent_id === item.id)
                    .sort((a, b) => a.sort_order - b.sort_order)
                    .forEach(c => addItem(c, depth + 1));
            };
            this.items
                .filter(i => !i.parent_id)
                .sort((a, b) => a.sort_order - b.sort_order)
                .forEach(i => addItem(i, 0));
            return result;
        },

        init() {
            this.$nextTick(() => this.initSortable());
        },

        initSortable() {
            const el = document.getElementById('menu-tree');
            if (!el || !window.Sortable) return;
            Sortable.create(el, {
                animation: 200,
                handle: '.drag-handle',
                ghostClass: 'opacity-40',
                filter: '.edit-panel',
                onEnd: (evt) => {
                    // Re-index sort_order from flat tree order
                    const newOrder = [...el.querySelectorAll('[id^="item-"]')]
                        .map(el => parseInt(el.id.replace('item-', '')));
                    newOrder.forEach((id, idx) => {
                        const item = this.items.find(i => i.id === id);
                        if (item) item.sort_order = idx;
                    });
                    this.save();
                }
            });
        },

        canIndent(id) {
            const ft = this.flatTree;
            const idx = ft.findIndex(i => i.id === id);
            return idx > 0; // can indent if there's a sibling above
        },

        indent(id) {
            const ft = this.flatTree;
            const idx = ft.findIndex(i => i.id === id);
            if (idx === 0) return;
            const above = ft[idx - 1]; // the item directly above in the tree
            const item = this.items.find(i => i.id === id);
            if (item && above) {
                item.parent_id = above.id;
                this.save();
            }
        },

        outdent(id) {
            const item = this.items.find(i => i.id === id);
            if (!item || !item.parent_id) return;
            const parent = this.items.find(i => i.id === item.parent_id);
            item.parent_id = parent?.parent_id ?? null;
            this.save();
        },

        async save() {
            this.saving = true;
            this.saved = false;
            const payload = this.flatTree.map((item, idx) => ({
                id: item.id,
                sort_order: idx,
                parent_id: item.parent_id ?? null,
            }));

            try {
                await fetch(`/admin/menus/${this.menuId}/reorder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ items: payload }),
                });
                this.saved = true;
                setTimeout(() => this.saved = false, 2500);
            } finally {
                this.saving = false;
                this.$nextTick(() => this.initSortable());
            }
        },

        async submitNewItem() {
            if (!this.newItem.url || !this.newItem.label_ar) return;
            this.submitting = true;
            try {
                const res = await fetch(`/admin/menus/${this.menuId}/items`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({...this.newItem}),
                });
                const data = await res.json();
                this.items.push({...data.item, parent_id: data.item.parent_id ?? null});
                this.newItem = { label_ar:'', label_en:'', label_nl:'', label_de:'', url:'/', target:'_self', icon:'' };
                this.saved = true;
                setTimeout(() => this.saved = false, 2500);
                this.$nextTick(() => this.initSortable());
            } finally {
                this.submitting = false;
            }
        },

        async addFromSources() {
            if (!this.selectedSources.length) return;
            this.submitting = true;
            try {
                for (const src of this.selectedSources) {
                    const data = typeof src === 'string' ? JSON.parse(src) : src;
                    const res = await fetch(`/admin/menus/${this.menuId}/items`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ label_ar: data.label_ar, label_en: data.label_en || '', url: data.url, target: '_self' }),
                    });
                    const json = await res.json();
                    this.items.push({...json.item, parent_id: null});
                }
                this.selectedSources = [];
                this.saved = true;
                setTimeout(() => this.saved = false, 2500);
                this.$nextTick(() => this.initSortable());
            } finally {
                this.submitting = false;
            }
        },

        async updateItem(item) {
            try {
                const res = await fetch(`/admin/menus/${this.menuId}/items/${item.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        label_ar: item.label_ar, label_en: item.label_en,
                        label_nl: item.label_nl, label_de: item.label_de,
                        url: item.url, target: item.target, icon: item.icon,
                    }),
                });
                await res.json();
                this.editingId = null;
                this.saved = true;
                setTimeout(() => this.saved = false, 2500);
            } catch {}
        },

        async deleteItem(id) {
            if (!confirm('حذف هذا العنصر؟ سيتم تحويل عناصره الفرعية إلى المستوى الرئيسي.')) return;
            try {
                await fetch(`/admin/menus/${this.menuId}/items/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                });
                // Move children to root
                this.items.filter(i => i.parent_id === id).forEach(c => c.parent_id = null);
                this.items = this.items.filter(i => i.id !== id);
                if (this.editingId === id) this.editingId = null;
                this.$nextTick(() => this.initSortable());
            } catch {}
        },
    };
}
</script>
@endpush
@endsection
