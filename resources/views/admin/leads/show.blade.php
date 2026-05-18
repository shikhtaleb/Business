@extends('layouts.admin')

@section('title', 'عميل: ' . $lead->name)
@section('page-title', 'تفاصيل العميل المحتمل')

@section('content')
<div class="space-y-5 max-w-5xl">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.leads.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            العودة للعملاء
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.leads.edit', $lead) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                تعديل
            </a>
            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا العميل؟')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-red-200 bg-white text-sm font-medium text-red-600 hover:bg-red-50">
                    حذف
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Lead Details Card --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $lead->name }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $lead->email }}</p>
                    </div>
                    @php
                        $statusBadge = [
                            'new'       => 'bg-blue-100 text-blue-700',
                            'contacted' => 'bg-yellow-100 text-yellow-700',
                            'qualified' => 'bg-green-100 text-green-700',
                            'lost'      => 'bg-red-100 text-red-700',
                            'converted' => 'bg-purple-100 text-purple-700',
                        ];
                        $statusLabels = ['new' => 'جديد', 'contacted' => 'تم التواصل', 'qualified' => 'مؤهل', 'lost' => 'خُسر', 'converted' => 'تحوّل'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusBadge[$lead->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabels[$lead->status] ?? $lead->status }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500 font-medium">الهاتف</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $lead->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">المصدر</dt>
                        @php
                            $sourceLabels = ['contact_form' => 'نموذج تواصل', 'manual' => 'يدوي', 'import' => 'استيراد', 'api' => 'API'];
                        @endphp
                        <dd class="text-gray-900 mt-0.5">{{ $sourceLabels[$lead->source] ?? $lead->source }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">الأولوية</dt>
                        <dd class="mt-0.5">
                            @php
                                $priorityBadge = [
                                    'low'    => 'bg-gray-100 text-gray-600',
                                    'normal' => 'bg-blue-100 text-blue-700',
                                    'high'   => 'bg-orange-100 text-orange-700',
                                    'urgent' => 'bg-red-100 text-red-700',
                                ];
                                $priorityLabels = ['low' => 'منخفضة', 'normal' => 'عادية', 'high' => 'عالية', 'urgent' => 'عاجلة'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityBadge[$lead->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $priorityLabels[$lead->priority] ?? $lead->priority }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">اللغة</dt>
                        <dd class="text-gray-900 mt-0.5">{{ strtoupper($lead->lang) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">آخر تواصل</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $lead->last_contacted_at?->format('d M Y, H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">تاريخ الإنشاء</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $lead->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($lead->message)
                    <div class="col-span-2">
                        <dt class="text-gray-500 font-medium">الرسالة الأصلية</dt>
                        <dd class="mt-0.5">
                            <a href="{{ route('admin.messages.show', $lead->message) }}"
                               class="text-[#FF8528] hover:underline text-sm">عرض الرسالة: {{ $lead->message->subject }}</a>
                        </dd>
                    </div>
                    @endif
                    @if($lead->getRawOriginal('notes'))
                    <div class="col-span-2">
                        <dt class="text-gray-500 font-medium">ملاحظات</dt>
                        <dd class="text-gray-900 mt-0.5 whitespace-pre-line">{{ $lead->getRawOriginal('notes') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Notes Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-sm font-bold text-gray-900 mb-4">سجل الأنشطة</h4>

                @if($lead->leadNotes->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-6">لا توجد ملاحظات بعد. أضف أول ملاحظة أدناه.</p>
                @else
                    <div class="space-y-4 mb-6">
                        @foreach($lead->leadNotes->sortByDesc('created_at') as $note)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 style="background:linear-gradient(135deg,#FF8528,#c45e00);">
                                {{ strtoupper(substr($note->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-xl p-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-semibold text-gray-700">{{ $note->user->name ?? 'مجهول' }}</span>
                                    <span class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $note->note }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add Note Form --}}
                <form method="POST" action="{{ route('admin.leads.note', $lead) }}">
                    @csrf
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">إضافة ملاحظة</label>
                    <textarea name="note" rows="3" placeholder="اكتب ملاحظة..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                              style="--tw-ring-color:#FF8528;"></textarea>
                    @error('note')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                            class="mt-2 px-4 py-2 rounded-xl text-white text-sm font-medium"
                            style="background:#FF8528;">إضافة ملاحظة</button>
                </form>
            </div>
        </div>

        {{-- Sidebar: Quick Updates --}}
        <div class="space-y-5">

            {{-- Update Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-bold text-gray-900 mb-3">تحديث الحالة</h4>
                <form method="POST" action="{{ route('admin.leads.status', $lead) }}">
                    @csrf
                    <select name="status"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent mb-3"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ $statusLabels[$s] ?? $s }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full py-2 rounded-xl text-white text-sm font-medium"
                            style="background:#FF8528;">تحديث الحالة</button>
                </form>
            </div>

            {{-- Update Priority --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-bold text-gray-900 mb-3">الأولوية والمسؤول</h4>
                <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="space-y-3">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $lead->name }}">
                    <input type="hidden" name="email" value="{{ $lead->email }}">
                    <input type="hidden" name="phone" value="{{ $lead->phone }}">
                    <input type="hidden" name="source" value="{{ $lead->source }}">
                    <input type="hidden" name="status" value="{{ $lead->status }}">
                    <input type="hidden" name="notes" value="{{ $lead->getRawOriginal('notes') }}">
                    <input type="hidden" name="lang" value="{{ $lead->lang }}">

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">الأولوية</label>
                        <select name="priority"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#FF8528;">
                            @foreach($priorities as $p)
                                <option value="{{ $p }}" {{ $lead->priority === $p ? 'selected' : '' }}>{{ $priorityLabels[$p] ?? $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">المسؤول</label>
                        <select name="assigned_to"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#FF8528;">
                            <option value="">غير محدد</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $lead->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full py-2 rounded-xl text-white text-sm font-medium"
                            style="background:#FF8528;">حفظ التغييرات</button>
                </form>
            </div>

            {{-- Meta Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-xs text-gray-500 space-y-2">
                <div class="flex justify-between">
                    <span>تاريخ الإنشاء</span>
                    <span class="text-gray-700">{{ $lead->created_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>آخر تحديث</span>
                    <span class="text-gray-700">{{ $lead->updated_at->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>عدد الملاحظات</span>
                    <span class="text-gray-700">{{ $lead->leadNotes->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
