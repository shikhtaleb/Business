@extends('layouts.admin')
@section('title', 'قائمة: ' . $menu->name)
@section('page-title', 'إدارة قائمة: ' . $menu->name)

@section('content')
<div class="space-y-5">
    <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        العودة إلى القوائم
    </a>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- Add Item Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-4">
                <h3 class="font-semibold text-gray-900 mb-4">إضافة عنصر</h3>
                <form method="POST" action="{{ route('admin.menus.items.store', $menu) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">التسمية (عربي) <span class="text-red-500">*</span></label>
                        <input type="text" name="label_ar" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">التسمية (إنجليزي)</label>
                        <input type="text" name="label_en" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">الرابط (URL) <span class="text-red-500">*</span></label>
                        <input type="text" name="url" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200" placeholder="/">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">فتح في</label>
                        <select name="target" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                            <option value="_self">نفس الصفحة</option>
                            <option value="_blank">نافذة جديدة</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">
                        إضافة العنصر
                    </button>
                </form>
            </div>
        </div>

        {{-- Items List --}}
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">عناصر القائمة</h3>
            </div>

            @if($menu->rootItems->isEmpty())
                <div class="py-12 text-center text-gray-400">
                    <p class="text-sm">لا توجد عناصر. أضف عنصراً من النموذج.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50" id="menu-items">
                    @foreach($menu->rootItems as $item)
                        <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50">
                            <div>
                                <span class="text-sm font-medium text-gray-800">{{ $item->label_ar }}</span>
                                <span class="text-xs text-gray-400 ml-2">{{ $item->url }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <form method="POST" action="{{ route('admin.menus.items.move-up', [$menu, $item]) }}">
                                    @csrf
                                    <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-400 text-xs">↑</button>
                                </form>
                                <form method="POST" action="{{ route('admin.menus.items.move-down', [$menu, $item]) }}">
                                    @csrf
                                    <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-400 text-xs">↓</button>
                                </form>
                                <form method="POST" action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" onsubmit="return confirm('حذف هذا العنصر؟')">
                                    @csrf @method('DELETE')
                                    <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @foreach($item->children as $child)
                            <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 bg-gray-50/50">
                                <div class="pl-4">
                                    <span class="text-gray-400 mr-1">↳</span>
                                    <span class="text-sm text-gray-700">{{ $child->label_ar }}</span>
                                    <span class="text-xs text-gray-400 ml-2">{{ $child->url }}</span>
                                </div>
                                <form method="POST" action="{{ route('admin.menus.items.destroy', [$menu, $child]) }}" onsubmit="return confirm('حذف؟')">
                                    @csrf @method('DELETE')
                                    <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
