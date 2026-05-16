@extends('layouts.admin')
@section('title', __('admin.add_plan'))
@section('page-title', __('admin.plans'))

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">{{ __('admin.add_plan') }}</h2>
        </div>

        <form method="POST" action="{{ route('admin.plans.store') }}" class="px-6 py-6 space-y-5">
            @csrf
            @include('admin.plans._form')
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.plans.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
                    {{ __('admin.cancel') }}
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
                    style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                    {{ __('admin.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
