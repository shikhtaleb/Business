@extends('layouts.admin')
@section('title', 'إعادة التوجيه')
@section('page-title', 'إدارة إعادة التوجيه')

@section('content')
<div class="space-y-5">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- Add Redirect Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-4">
                <h3 class="font-semibold text-gray-900 mb-4">إضافة توجيه جديد</h3>
                <form method="POST" action="{{ route('admin.redirects.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">من (مسار قديم) <span class="text-red-500">*</span></label>
                        <input type="text" name="from_path" required placeholder="/old-page" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">إلى (مسار جديد) <span class="text-red-500">*</span></label>
                        <input type="text" name="to_path" required placeholder="/new-page" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">نوع التوجيه</label>
                        <select name="status_code" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                            <option value="301">301 — دائم</option>
                            <option value="302">302 — مؤقت</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300">
                        تفعيل التوجيه
                    </label>
                    <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">
                        إضافة التوجيه
                    </button>
                </form>

                {{-- CSV Import --}}
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-600 mb-2">استيراد من CSV</h4>
                    <form method="POST" action="{{ route('admin.redirects.import') }}" enctype="multipart/form-data" class="flex gap-2">
                        @csrf
                        <input type="file" name="csv_file" accept=".csv" required class="text-xs text-gray-500 flex-1">
                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">استيراد</button>
                    </form>
                    <p class="text-xs text-gray-400 mt-1">الأعمدة: from_path, to_path, status_code</p>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <h3 class="font-semibold text-gray-900">قواعد التوجيه ({{ $redirects->total() }})</h3>
                <form method="GET" action="{{ route('admin.redirects.index') }}" class="flex gap-2">
                    <div class="relative">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="بحث في المسارات..."
                               class="rounded-xl border border-gray-200 pr-9 pl-3 py-2 text-sm text-gray-700 w-52 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    </div>
                    <button type="submit" class="px-3 py-2 rounded-xl text-sm font-semibold text-white shadow-sm" style="background:#FF8528;">بحث</button>
                    @if(request('q'))
                        <a href="{{ route('admin.redirects.index') }}" class="px-3 py-2 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">×</a>
                    @endif
                </form>
            </div>

            @if($redirects->isEmpty())
                <div class="py-12 text-center text-gray-400">
                    <p class="text-sm">لا توجد قواعد توجيه</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($redirects as $redirect)
                        <div class="px-5 py-3.5 hover:bg-gray-50">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 text-sm">
                                        <code class="text-gray-500 text-xs truncate max-w-[120px]">{{ $redirect->from_path }}</code>
                                        <svg class="w-3.5 h-3.5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                        <code class="text-gray-700 text-xs truncate max-w-[120px]">{{ $redirect->to_path }}</code>
                                        <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">{{ $redirect->status_code }}</span>
                                        <span class="text-xs px-1.5 py-0.5 rounded {{ $redirect->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $redirect->is_active ? 'نشط' : 'معطل' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <form method="POST" action="{{ route('admin.redirects.toggle', $redirect) }}">
                                        @csrf
                                        <button class="px-2 py-1 rounded-lg border border-gray-200 text-xs text-gray-500 hover:bg-gray-50">
                                            {{ $redirect->is_active ? 'تعطيل' : 'تفعيل' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.redirects.edit', $redirect) }}"
                                       class="px-2 py-1 rounded-lg border border-gray-200 text-xs text-gray-500 hover:bg-gray-50">تعديل</a>
                                    <form method="POST" action="{{ route('admin.redirects.destroy', $redirect) }}" onsubmit="return confirm('حذف؟')">
                                        @csrf @method('DELETE')
                                        <button class="w-6 h-6 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($redirects->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">{{ $redirects->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
