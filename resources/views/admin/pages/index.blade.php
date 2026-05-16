@extends('layouts.admin')

@section('title', 'Pages')
@section('page-title', 'Pages')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">All Pages</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $pages->total() }} {{ Str::plural('page', $pages->total()) }}</p>
        </div>
        <a href="{{ route('admin.pages.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-medium transition-colors"
           style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Page
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($pages->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">No pages yet. <a href="{{ route('admin.pages.create') }}" class="font-medium" style="color:#FF8528;">Create your first page</a></p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Title</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Slug</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Status</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Author</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wide">Updated</th>
                            <th class="px-5 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pages as $page)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-gray-900">{{ $page->title_ar }}</div>
                                    @if($page->title_en)
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $page->title_en }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">/{{ $page->slug }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($page->status === 'published')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">
                                    {{ $page->author?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs whitespace-nowrap">
                                    {{ $page->updated_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.pages.edit', $page) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
                                              x-data
                                              @submit.prevent="if(confirm('Delete this page?')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                    title="Delete">
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

            @if($pages->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $pages->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
