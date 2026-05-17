@extends('layouts.admin')

@section('title', 'عميل جديد')
@section('page-title', 'إضافة عميل محتمل')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.leads.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            العودة للعملاء
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-900 mb-5">إنشاء عميل محتمل جديد</h3>

        <form method="POST" action="{{ route('admin.leads.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">الاسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;" placeholder="الاسم الكامل">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;" placeholder="email@example.com">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;" placeholder="+966 5X XXX XXXX">
                    @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">المصدر</label>
                    @php $sourceLabels = ['contact_form' => 'نموذج تواصل', 'manual' => 'يدوي', 'import' => 'استيراد', 'api' => 'API']; @endphp
                    <select name="source"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($sources as $s)
                            <option value="{{ $s }}" {{ old('source', 'manual') === $s ? 'selected' : '' }}>{{ $sourceLabels[$s] ?? $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">الحالة</label>
                    @php $statusLabels = ['new' => 'جديد', 'contacted' => 'تم التواصل', 'qualified' => 'مؤهل', 'lost' => 'خُسر', 'converted' => 'تحوّل']; @endphp
                    <select name="status"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ old('status', 'new') === $s ? 'selected' : '' }}>{{ $statusLabels[$s] ?? $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">الأولوية</label>
                    @php $priorityLabels = ['low' => 'منخفضة', 'normal' => 'عادية', 'high' => 'عالية', 'urgent' => 'عاجلة']; @endphp
                    <select name="priority"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($priorities as $p)
                            <option value="{{ $p }}" {{ old('priority', 'normal') === $p ? 'selected' : '' }}>{{ $priorityLabels[$p] ?? $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">المسؤول</label>
                    <select name="assigned_to"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        <option value="">غير محدد</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">اللغة</label>
                    <select name="lang"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach(['ar' => 'العربية', 'en' => 'الإنجليزية', 'nl' => 'الهولندية', 'de' => 'الألمانية'] as $code => $label)
                            <option value="{{ $code }}" {{ old('lang', 'ar') === $code ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">ملاحظات</label>
                    <textarea name="notes" rows="4" placeholder="ملاحظات داخلية عن هذا العميل..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                              style="--tw-ring-color:#FF8528;">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold"
                        style="background:#FF8528;">إنشاء عميل</button>
                <a href="{{ route('admin.leads.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
