@extends('layouts.admin')
@section('title', 'تعديل التوجيه')
@section('page-title', 'تعديل إعادة التوجيه')

@section('content')
<div class="max-w-lg space-y-5">
    <a href="{{ route('admin.redirects.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        العودة إلى إعادة التوجيه
    </a>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.redirects.update', $redirect) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">من (مسار قديم)</label>
            <input type="text" name="from_path" value="{{ old('from_path', $redirect->from_path) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">إلى (مسار جديد)</label>
            <input type="text" name="to_path" value="{{ old('to_path', $redirect->to_path) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">نوع التوجيه</label>
            <select name="status_code" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm bg-white">
                <option value="301" {{ $redirect->status_code == 301 ? 'selected' : '' }}>301 — دائم</option>
                <option value="302" {{ $redirect->status_code == 302 ? 'selected' : '' }}>302 — مؤقت</option>
            </select>
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" {{ $redirect->is_active ? 'checked' : '' }} class="rounded border-gray-300">
            تفعيل التوجيه
        </label>
        <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
            <a href="{{ route('admin.redirects.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50">إلغاء</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold" style="background:#FF8528;">حفظ</button>
        </div>
    </form>
</div>
@endsection
