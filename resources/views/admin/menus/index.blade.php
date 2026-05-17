@extends('layouts.admin')
@section('title', 'القوائم')
@section('page-title', 'إدارة القوائم')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Create Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">إنشاء قائمة جديدة</h3>

        <form method="POST" action="{{ route('admin.menus.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">اسم القائمة <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">الموقع</label>
                <select name="location" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="header">الترويسة</option>
                    <option value="footer">التذييل</option>
                    <option value="custom">مخصص</option>
                </select>
            </div>
            <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">
                إنشاء القائمة
            </button>
        </form>
    </div>

    {{-- Menus List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">القوائم الموجودة</h3>
        </div>

        @if($menus->isEmpty())
            <div class="py-12 text-center text-gray-400">
                <p class="text-sm">لا توجد قوائم بعد</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($menus as $menu)
                    <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50">
                        <div>
                            <span class="text-sm font-medium text-gray-800">{{ $menu->name }}</span>
                            <span class="text-xs text-gray-400 ml-2">{{ $menu->items_count }} عنصر</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 ml-1">{{ $menu->location }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.menus.show', $menu) }}"
                               class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs text-gray-600 hover:bg-gray-50">إدارة</a>
                            <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" onsubmit="return confirm('حذف هذه القائمة؟')">
                                @csrf @method('DELETE')
                                <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
