@extends('layouts.admin')

@section('title', 'المقالات — Retont Business')
@section('page-title', 'إدارة المقالات')

@section('content')
<div class="space-y-5">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">المقالات</h2>
            <p class="text-sm text-gray-500 mt-0.5">إجمالي {{ $posts->total() }} مقال</p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                التصنيفات
            </a>
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold text-white shadow-sm transition-colors"
               style="background:#FF8528;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                مقال جديد
            </a>
        </div>
    </div>

    {{-- ── Filter Bar ───────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
        <form method="GET" action="{{ route('admin.posts.index') }}"
              class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">الحالة</label>
                <select name="status"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    <option value="">كل الحالات</option>
                    <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>مسودة</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منشور</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">التصنيف</label>
                <select name="category_id"
                        class="w-full rounded-xl border border-gray-200 px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name_ar }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-sm font-semibold text-white shadow-sm"
                        style="background:#FF8528;">
                    تصفية
                </button>
                @if(request()->hasAny(['status','category_id']))
                    <a href="{{ route('admin.posts.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
                        إعادة ضبط
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Table ────────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($posts->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium">لا توجد مقالات حتى الآن</p>
                <a href="{{ route('admin.posts.create') }}"
                   class="mt-3 text-sm font-semibold hover:underline"
                   style="color:#FF8528;">أنشئ أول مقال</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-right text-xs font-semibold text-gray-500 px-5 py-3 w-16">صورة</th>
                            <th class="text-right text-xs font-semibold text-gray-500 px-4 py-3">العنوان</th>
                            <th class="text-right text-xs font-semibold text-gray-500 px-4 py-3 hidden md:table-cell">التصنيف</th>
                            <th class="text-right text-xs font-semibold text-gray-500 px-4 py-3 hidden lg:table-cell">الكاتب</th>
                            <th class="text-right text-xs font-semibold text-gray-500 px-4 py-3">الحالة</th>
                            <th class="text-right text-xs font-semibold text-gray-500 px-4 py-3 hidden lg:table-cell">تاريخ النشر</th>
                            <th class="text-right text-xs font-semibold text-gray-500 px-4 py-3 hidden xl:table-cell">المشاهدات</th>
                            <th class="text-right text-xs font-semibold text-gray-500 px-4 py-3">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Thumbnail --}}
                                <td class="px-5 py-3">
                                    @if($post->featured_image)
                                        <img src="{{ $post->featured_image }}"
                                             alt="{{ $post->title_ar }}"
                                             class="w-12 h-10 object-cover rounded-lg border border-gray-100">
                                    @else
                                        <div class="w-12 h-10 rounded-lg border border-gray-100 bg-gray-50 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>

                                {{-- Title --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if($post->is_featured)
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="#FF8528" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900 line-clamp-1">{{ $post->title_ar }}</p>
                                            <p class="text-xs text-gray-400 font-mono mt-0.5">/{{ $post->slug }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Category --}}
                                <td class="px-4 py-3 hidden md:table-cell">
                                    @if($post->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ $post->category->name_ar }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Author --}}
                                <td class="px-4 py-3 hidden lg:table-cell">
                                    <span class="text-gray-600 text-xs">{{ $post->author?->name ?? '—' }}</span>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    @php
                                        $badgeClass = match($post->status) {
                                            'published' => 'bg-green-100 text-green-700',
                                            'scheduled' => 'bg-blue-100 text-blue-700',
                                            default     => 'bg-gray-100 text-gray-600',
                                        };
                                        $badgeLabel = match($post->status) {
                                            'published' => 'منشور',
                                            'scheduled' => 'مجدول',
                                            default     => 'مسودة',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $badgeLabel }}
                                    </span>
                                </td>

                                {{-- Published At --}}
                                <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-500">
                                    {{ $post->published_at?->format('Y-m-d H:i') ?? '—' }}
                                </td>

                                {{-- Views --}}
                                <td class="px-4 py-3 hidden xl:table-cell text-xs text-gray-500">
                                    {{ number_format($post->views_count) }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        {{-- Toggle publish --}}
                                        <form method="POST" action="{{ route('admin.posts.publish', $post) }}">
                                            @csrf
                                            <button type="submit"
                                                    title="{{ $post->status === 'published' ? 'إلغاء النشر' : 'نشر' }}"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors
                                                           {{ $post->status === 'published' ? 'text-green-600 hover:bg-green-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                           title="تعديل"
                                           class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                              x-data
                                              @submit.prevent="if(confirm('هل أنت متأكد من حذف هذا المقال؟')) $el.submit()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="حذف"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
