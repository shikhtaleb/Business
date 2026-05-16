@extends('layouts.admin')
@section('title', __('admin.languages'))
@section('page-title', __('admin.languages'))

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $languages->count() }} {{ __('admin.languages') }}</p>
        <a href="{{ route('admin.languages.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
           style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('admin.add_language') ?? 'إضافة لغة' }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">اللغة</th>
                    <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">الكود</th>
                    <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">الاتجاه</th>
                    <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">الحالة</th>
                    <th class="px-6 py-3.5 text-start text-xs font-semibold text-gray-500 uppercase tracking-wide">الترجمة</th>
                    <th class="px-6 py-3.5 text-end text-xs font-semibold text-gray-500 uppercase tracking-wide">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($languages as $lang)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-6 rounded text-[10px] font-bold flex items-center justify-center bg-gray-100 text-gray-600">
                                    {{ $lang->flag_code ?: strtoupper($lang->code) }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $lang->native_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $lang->name }}</p>
                                </div>
                                @if($lang->is_default)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-medium">افتراضي</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-gray-600">{{ $lang->code }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded-lg {{ $lang->is_rtl ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $lang->is_rtl ? 'RTL ←' : 'LTR →' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $lang->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $lang->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            @if($lang->translations)
                                {{ count($lang->translations) }} {{ 'مفتاح' }}
                                <a href="{{ route('admin.languages.export', $lang) }}"
                                   class="ms-2 text-blue-500 hover:underline">تصدير</a>
                            @else
                                <span class="text-amber-600">لا يوجد ملف ترجمة</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.languages.edit', $lang) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if(!$lang->is_default)
                                    <form method="POST" action="{{ route('admin.languages.destroy', $lang) }}"
                                          onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 transition-colors text-red-400 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">لا توجد لغات مضافة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
