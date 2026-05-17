@extends('layouts.admin')

@section('title', 'تعديل: ' . $lead->name)
@section('page-title', 'تعديل العميل المحتمل')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.leads.show', $lead) }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            العودة للعميل
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-900 mb-5">تعديل بيانات العميل</h3>

        <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">الاسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $lead->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $lead->email) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;">
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">المصدر</label>
                    @php $sourceLabels = ['contact_form' => 'نموذج تواصل', 'manual' => 'يدوي', 'import' => 'استيراد', 'api' => 'API']; @endphp
                    <select name="source"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($sources as $s)
                            <option value="{{ $s }}" {{ old('source', $lead->source) === $s ? 'selected' : '' }}>{{ $sourceLabels[$s] ?? $s }}</option>
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
                            <option value="{{ $s }}" {{ old('status', $lead->status) === $s ? 'selected' : '' }}>{{ $statusLabels[$s] ?? $s }}</option>
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
                            <option value="{{ $p }}" {{ old('priority', $lead->priority) === $p ? 'selected' : '' }}>{{ $priorityLabels[$p] ?? $p }}</option>
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
                            <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">اللغة</label>
                    <select name="lang"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach(['ar' => 'العربية', 'en' => 'الإنجليزية', 'nl' => 'الهولندية', 'de' => 'الألمانية'] as $code => $label)
                            <option value="{{ $code }}" {{ old('lang', $lead->lang) === $code ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">ملاحظات</label>
                    <textarea name="notes" rows="4"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                              style="--tw-ring-color:#FF8528;">{{ old('notes', $lead->getRawOriginal('notes')) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold"
                        style="background:#FF8528;">حفظ التغييرات</button>
                <a href="{{ route('admin.leads.show', $lead) }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
