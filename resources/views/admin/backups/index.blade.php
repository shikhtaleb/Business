@extends('layouts.admin')
@section('title', 'النسخ الاحتياطية')
@section('page-title', 'النسخ الاحتياطية')

@section('content')
<div class="space-y-5">

    {{-- Action Bar --}}
    <div class="flex flex-wrap items-center gap-3">
        <form method="POST" action="{{ route('admin.backups.create') }}" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="type" value="database">
            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
                style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'"
                onclick="this.disabled=true; this.innerHTML='⏳ جارٍ النسخ...'; this.form.submit();">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582 4 8 4s8-1.79 4-4M4 7c0-2.21 3.582 4 8 4"/>
                </svg>
                نسخ قاعدة البيانات
            </button>
        </form>
        <form method="POST" action="{{ route('admin.backups.create') }}" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="type" value="files">
            <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                نسخ الملفات
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Info Card --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-medium text-blue-800">تخزين النسخ الاحتياطية</p>
            <p class="text-xs text-blue-600 mt-0.5">تُحفظ النسخ في <code class="bg-blue-100 px-1 rounded">storage/app/backups/</code> على السيرفر. يمكنك تحميلها وحفظها في مكان آمن.</p>
        </div>
    </div>

    {{-- Backups List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($backups->isEmpty())
            <div class="py-16 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">لا توجد نسخ احتياطية بعد</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">الملف</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">النوع</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">الحجم</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">الحالة</th>
                        <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">التاريخ</th>
                        <th class="px-6 py-3.5 text-end text-xs font-semibold text-gray-500 uppercase tracking-wide">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($backups as $backup)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-gray-700">{{ $backup->filename ?: '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs px-2 py-1 rounded-lg {{ $backup->type === 'database' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $backup->type === 'database' ? '🗄️ قاعدة بيانات' : '📁 ملفات' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $backup->size > 0 ? $backup->formattedSize() : '—' }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'completed' => 'bg-green-100 text-green-700',
                                        'running'   => 'bg-yellow-100 text-yellow-700',
                                        'failed'    => 'bg-red-100 text-red-700',
                                        'pending'   => 'bg-gray-100 text-gray-600',
                                    ];
                                    $statusLabels = ['completed'=>'مكتمل','running'=>'جارٍ','failed'=>'فشل','pending'=>'انتظار'];
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $statusClasses[$backup->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$backup->status] ?? $backup->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $backup->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    @if($backup->status === 'completed')
                                        <a href="{{ route('admin.backups.download', $backup) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            تحميل
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.backups.destroy', $backup) }}"
                                          onsubmit="return confirm('حذف هذه النسخة الاحتياطية؟')">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($backups->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $backups->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection
