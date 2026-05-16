@extends('layouts.admin')
@section('title', 'حملات البريد الإلكتروني')
@section('page-title', 'الحملات البريدية')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">الحملات البريدية</h2>
            <p class="text-sm text-gray-500 mt-0.5">إرسال رسائل إلكترونية جماعية للمشتركين</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-sm"
           style="background:#FF8528;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            حملة جديدة
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($campaigns->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">لا توجد حملات بعد</p>
                <a href="{{ route('admin.campaigns.create') }}" class="text-sm mt-2 inline-block" style="color:#FF8528;">إنشاء حملة جديدة</a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الاسم</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الموضوع</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">اللغة المستهدفة</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                        <th class="px-5 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase">تاريخ الإرسال</th>
                        <th class="px-5 py-3.5 text-end text-xs font-semibold text-gray-500 uppercase">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($campaigns as $campaign)
                        @php
                            $statusColors = ['draft'=>'bg-gray-100 text-gray-600','sending'=>'bg-yellow-100 text-yellow-700','sent'=>'bg-green-100 text-green-700','failed'=>'bg-red-100 text-red-700'];
                            $statusLabels = ['draft'=>'مسودة','sending'=>'جارٍ الإرسال','sent'=>'تم الإرسال','failed'=>'فشل'];
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5 font-medium text-gray-900">{{ $campaign->name }}</td>
                            <td class="px-5 py-3.5 text-gray-600">{{ Str::limit($campaign->subject_ar, 50) }}</td>
                            <td class="px-5 py-3.5 text-gray-500 uppercase text-xs">{{ $campaign->target_lang ?? 'الكل' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$campaign->status] ?? $campaign->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-400 text-xs">
                                {{ $campaign->sent_at ? \Carbon\Carbon::parse($campaign->sent_at)->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="px-5 py-3.5 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    @if($campaign->status === 'draft')
                                        <form method="POST" action="{{ route('admin.campaigns.send', $campaign) }}" onsubmit="return confirm('إرسال الحملة الآن؟')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white" style="background:#FF8528;">
                                                إرسال
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                           class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">
                                            تعديل
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" onsubmit="return confirm('حذف هذه الحملة؟')">
                                        @csrf @method('DELETE')
                                        <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($campaigns->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">{{ $campaigns->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection
