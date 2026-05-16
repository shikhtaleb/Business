@extends('layouts.admin')
@section('title', 'المشتركون')
@section('page-title', 'إدارة المشتركين')

@section('content')
<div class="space-y-5" x-data="{ selected: [] }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">المشتركون</h2>
            <p class="text-sm text-gray-500 mt-0.5">إجمالي {{ $totalCount }} مشترك</p>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            {{-- Import --}}
            <label class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                استيراد CSV
                <form id="importForm" method="POST" action="{{ route('admin.subscribers.import') }}" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv,.txt" onchange="document.getElementById('importForm').submit()">
                </form>
            </label>
            <a href="{{ route('admin.subscribers.export') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                تصدير CSV
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach(['bg-blue-50 text-blue-700' => [$totalCount, 'الكل'], 'bg-green-50 text-green-700' => [$activeCount, 'نشط'], 'bg-gray-50 text-gray-700' => [$unsubscribedCount, 'ألغى الاشتراك'], 'bg-red-50 text-red-700' => [$bouncedCount, 'مرتد']] as $cls => [$cnt, $lbl])
            <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center">
                <div class="text-2xl font-bold {{ explode(' ', $cls)[1] }}">{{ $cnt }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $lbl }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filters + Bulk --}}
    <div class="flex flex-wrap gap-2 items-center justify-between">
        <form method="GET" class="flex flex-wrap gap-2">
            <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>كل الحالات</option>
                @foreach(['active'=>'نشط','unsubscribed'=>'ألغى الاشتراك','bounced'=>'مرتد'] as $k=>$v)
                    <option value="{{ $k }}" {{ $status === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            <input type="text" name="search" value="{{ $search }}" placeholder="بحث بالبريد أو الاسم..."
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-64">
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background:#FF8528;">بحث</button>
        </form>
        <form method="POST" action="{{ route('admin.subscribers.bulk') }}" x-show="selected.length > 0" @submit.prevent="if(confirm('تأكيد العملية؟')) $el.submit()">
            @csrf
            <template x-for="id in selected"><input type="hidden" name="ids[]" :value="id"></template>
            <select name="action" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                <option value="delete">حذف المحدد</option>
                <option value="unsubscribe">إلغاء اشتراك</option>
            </select>
            <button type="submit" class="ml-2 px-4 py-2 rounded-lg text-sm font-medium bg-red-500 text-white hover:bg-red-600">تنفيذ</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($subscribers->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <p class="text-sm">لا توجد نتائج</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3.5 w-10">
                            <input type="checkbox" @change="selected = $event.target.checked ? @json($subscribers->pluck('id')) : []" class="rounded border-gray-300">
                        </th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">البريد الإلكتروني</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الاسم</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">اللغة</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">المصدر</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">تاريخ الاشتراك</th>
                        <th class="px-5 py-3.5 text-end text-xs font-semibold text-gray-500 uppercase">إجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($subscribers as $sub)
                        @php
                            $statusColors = ['active'=>'bg-green-100 text-green-700','unsubscribed'=>'bg-gray-100 text-gray-600','bounced'=>'bg-red-100 text-red-700'];
                            $statusLabels = ['active'=>'نشط','unsubscribed'=>'ملغى','bounced'=>'مرتد'];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3.5 w-10">
                                <input type="checkbox" :value="{{ $sub->id }}" x-model="selected" class="rounded border-gray-300">
                            </td>
                            <td class="px-5 py-3.5 font-medium text-gray-800">{{ $sub->email }}</td>
                            <td class="px-5 py-3.5 text-gray-600">{{ $sub->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-gray-500 uppercase text-xs">{{ $sub->lang }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $statusColors[$sub->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$sub->status] ?? $sub->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $sub->source }}</td>
                            <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $sub->subscribed_at ? \Carbon\Carbon::parse($sub->subscribed_at)->format('Y-m-d') : '—' }}</td>
                            <td class="px-5 py-3.5 text-end">
                                <form method="POST" action="{{ route('admin.subscribers.destroy', $sub) }}" onsubmit="return confirm('حذف هذا المشترك؟')">
                                    @csrf @method('DELETE')
                                    <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400 hover:text-red-600 ml-auto">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($subscribers->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">{{ $subscribers->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection
