@extends('layouts.admin')

@section('title', 'مفاتيح API')
@section('page-title', 'مفاتيح API')

@section('content')
<div class="space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">مفاتيح API</h2>
            <p class="text-sm text-gray-500 mt-0.5">إدارة وصول البرامج والتطبيقات إلى المنصة.</p>
        </div>
        <button onclick="document.getElementById('create-form').scrollIntoView({behavior:'smooth'})"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-sm transition-opacity hover:opacity-90"
                style="background:#FF8528;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            مفتاح جديد
        </button>
    </div>

    {{-- One-time key display --}}
    @if($plainKey)
    <div x-data="{copied:false}" class="rounded-2xl border-2 p-5" style="background:#FFF4EA; border-color:#FF8528;">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#FF8528;">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-900 text-sm">مفتاح API الجديد — انسخه الآن!</p>
                <p class="text-xs text-gray-600 mt-0.5 mb-3">هذا المفتاح <strong>لن يُعرض مجدداً</strong>. احفظه في مكان آمن.</p>
                <div class="flex items-center gap-2">
                    <code id="plain-key-display"
                          class="flex-1 font-mono text-sm px-3 py-2 rounded-xl border border-orange-200 bg-white text-gray-900 break-all select-all"
                          style="word-break:break-all;">{{ $plainKey }}</code>
                    <button @click="navigator.clipboard.writeText('{{ $plainKey }}').then(()=>{copied=true; setTimeout(()=>copied=false,2000)})"
                            class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition-colors"
                            :class="copied ? 'bg-green-500 text-white' : 'bg-white border border-orange-200 text-gray-700 hover:bg-orange-50'">
                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <svg x-show="copied" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span x-text="copied ? 'تم النسخ!' : 'نسخ'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Keys table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">المفاتيح النشطة</h3>
            <span class="text-xs text-gray-500">{{ $keys->count() }} مفتاح</span>
        </div>

        @if($keys->isEmpty())
        <div class="px-6 py-16 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gray-50">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">لا توجد مفاتيح API بعد</p>
            <p class="text-sm text-gray-400 mt-1">أنشئ أول مفتاح أدناه.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-start">
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-start">الاسم</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-start">المفتاح</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-start">الصلاحيات</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-start">آخر استخدام</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-start">ينتهي في</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-start">الحالة</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($keys as $key)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $key->name }}</p>
                                @if(isset($key->user) && auth()->user()->hasRole('super_admin'))
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $key->user->name }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="font-mono text-sm px-2 py-1 bg-gray-100 rounded-lg text-gray-700">
                                {{ $key->key_prefix }}••••••••
                            </code>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $permLabels = ['read' => 'قراءة', 'write' => 'كتابة', 'delete' => 'حذف', 'admin' => 'مسؤول'];
                                    $permColors = ['read' => 'bg-blue-100 text-blue-700', 'write' => 'bg-green-100 text-green-700', 'delete' => 'bg-red-100 text-red-700', 'admin' => 'bg-purple-100 text-purple-700'];
                                @endphp
                                @forelse($key->permissions ?? [] as $perm)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $permColors[$perm] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $permLabels[$perm] ?? $perm }}
                                </span>
                                @empty
                                <span class="text-xs text-gray-400 italic">—</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $key->last_used_at ? $key->last_used_at->diffForHumans() : '—' }}
                        </td>
                        <td class="px-6 py-4 text-xs">
                            @if($key->expires_at)
                                <span class="{{ $key->isExpired() ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                    {{ $key->expires_at->format('Y-m-d') }}
                                    @if($key->isExpired()) <span class="text-[10px]">(منتهي)</span> @endif
                                </span>
                            @else
                                <span class="text-gray-400">لا ينتهي</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($key->is_active && !$key->isExpired())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>نشط
                                </span>
                            @elseif($key->isExpired())
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>منتهي
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>معطل
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 justify-end">
                                {{-- Toggle (POST route, no method spoofing needed) --}}
                                <form method="POST" action="{{ route('admin.api-keys.toggle', $key) }}">
                                    @csrf
                                    <button type="submit"
                                            title="{{ $key->is_active ? 'تعطيل' : 'تفعيل' }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                                        @if($key->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        @else
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @endif
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.api-keys.destroy', $key) }}"
                                      onsubmit="return confirm('حذف مفتاح API هذا؟ لا يمكن التراجع عن هذا الإجراء.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
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
        @endif
    </div>

    {{-- Create form --}}
    <div id="create-form" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#FF8528;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            إنشاء مفتاح API جديد
        </h3>

        <form method="POST" action="{{ route('admin.api-keys.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم المفتاح <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="مثال: مفتاح الإنتاج، CI/CD"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Expires at --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">تاريخ الانتهاء <span class="text-gray-400 font-normal">(اختياري)</span></label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                    @error('expires_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Permissions --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الصلاحيات</label>
                <div class="flex flex-wrap gap-3">
                    @foreach(['read' => ['blue','قراءة — عرض البيانات'], 'write' => ['green','كتابة — إنشاء وتعديل'], 'delete' => ['red','حذف — إزالة السجلات'], 'admin' => ['purple','مسؤول — وصول كامل']] as $perm => [$color, $desc])
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="permissions[]" value="{{ $perm }}"
                               {{ in_array($perm, old('permissions', [])) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-400">
                        <span class="text-sm text-gray-700 group-hover:text-gray-900">
                            <span class="font-medium">{{ $desc }}</span>
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold shadow-sm hover:opacity-90 transition-opacity"
                        style="background:#FF8528;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    إنشاء المفتاح
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
