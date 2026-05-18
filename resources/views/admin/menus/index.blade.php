@extends('layouts.admin')
@section('title', 'القوائم')
@section('page-title', 'إدارة القوائم')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">القوائم</h1>
            <p class="text-sm text-gray-500 mt-0.5">أنشئ وأدر قوائم التنقل في موقعك</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Create Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-1 text-base">قائمة جديدة</h3>
                <p class="text-xs text-gray-400 mb-5">أضف قائمة تنقل جديدة للموقع</p>

                <form method="POST" action="{{ route('admin.menus.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            اسم القائمة <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" required placeholder="مثال: القائمة الرئيسية"
                               value="{{ old('name') }}"
                               class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300 transition-colors">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">موقع القائمة</label>
                        <select name="location"
                                class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300 transition-colors">
                            <option value="header">الترويسة (Header)</option>
                            <option value="footer">التذييل (Footer)</option>
                            <option value="custom">مخصص</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 rounded-xl text-white text-sm font-semibold transition-opacity hover:opacity-90 flex items-center justify-center gap-2"
                            style="background:#FF8528;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        إنشاء القائمة
                    </button>
                </form>
            </div>

            {{-- Info card --}}
            <div class="mt-4 bg-orange-50 border border-orange-100 rounded-2xl p-4">
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-orange-700 mb-1">كيفية استخدام القوائم</p>
                        <ul class="text-xs text-orange-600 space-y-1">
                            <li>• أنشئ قائمة وحدد موقعها</li>
                            <li>• أضف عناصر من صفحات أو روابط مخصصة</li>
                            <li>• اسحب لإعادة الترتيب وأنشئ تسلسلاً هرمياً</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menus List --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 text-base">القوائم الموجودة</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full">
                        {{ $menus->count() }} قائمة
                    </span>
                </div>

                @if($menus->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-400">لا توجد قوائم بعد</p>
                        <p class="text-xs text-gray-300 mt-1">أنشئ أول قائمة تنقل من النموذج على اليمين</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($menus as $menu)
                            @php
                                $locationLabels = ['header' => 'الترويسة', 'footer' => 'التذييل', 'custom' => 'مخصص'];
                                $locationColors = ['header' => 'bg-blue-50 text-blue-600', 'footer' => 'bg-purple-50 text-purple-600', 'custom' => 'bg-gray-100 text-gray-500'];
                                $loc = $menu->location ?? 'custom';
                            @endphp
                            <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-50/60 transition-colors group">
                                <div class="flex items-center gap-3 min-w-0">
                                    {{-- Icon --}}
                                    <div class="w-9 h-9 rounded-xl bg-gray-100 group-hover:bg-orange-50 flex items-center justify-center flex-shrink-0 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-orange-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-sm font-semibold text-gray-800">{{ $menu->name }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $locationColors[$loc] ?? 'bg-gray-100 text-gray-500' }}">
                                                {{ $locationLabels[$loc] ?? $loc }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $menu->items_count }}
                                            {{ $menu->items_count == 1 ? 'عنصر' : 'عناصر' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a href="{{ route('admin.menus.show', $menu) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        إدارة
                                    </a>
                                    <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}"
                                          onsubmit="return confirm('هل أنت متأكد من حذف قائمة \'{{ addslashes($menu->name) }}\'؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg border border-transparent hover:border-red-200 hover:bg-red-50 text-gray-300 hover:text-red-400 transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed bottom-6 start-6 z-50 bg-white border border-green-200 rounded-2xl shadow-lg px-4 py-3 flex items-center gap-3">
    <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-sm font-medium text-gray-700">{{ session('success') }}</p>
</div>
@endif
@endsection
