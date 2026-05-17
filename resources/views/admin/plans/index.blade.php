@extends('layouts.admin')
@section('title', __('admin.plans'))
@section('page-title', __('admin.plans'))

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $plans->count() }} {{ __('admin.plans_count') }}</p>
        <a href="{{ route('admin.plans.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
           style="background:#FF8528;"
           onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('admin.add_plan') }}
        </a>
    </div>

    @if($plans->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm">{{ __('admin.no_plans') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($plans as $plan)
                <div class="bg-white rounded-2xl border shadow-sm overflow-hidden {{ $plan->is_popular ? 'border-orange-300' : 'border-gray-100' }}">
                    @if($plan->is_popular)
                        <div class="px-4 py-1.5 text-xs font-bold text-white text-center" style="background:#FF8528;">
                            ★ {{ __('admin.popular') }}
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $plan->name_ar }}</h3>
                                <p class="text-xs text-gray-400">{{ $plan->name_en }} · {{ $plan->slug }}</p>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $plan->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </div>

                        <div class="flex items-end gap-3 mb-4">
                            <div>
                                <p class="text-xs text-gray-400">{{ __('admin.monthly') }}</p>
                                <p class="text-xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 0) }} <span class="text-sm font-normal text-gray-500">{{ $plan->currency }}</span></p>
                            </div>
                            <div class="text-gray-300">|</div>
                            <div>
                                <p class="text-xs text-gray-400">{{ __('admin.yearly') }}</p>
                                <p class="text-lg font-semibold text-gray-700">{{ number_format($plan->price_yearly, 0) }} <span class="text-sm font-normal text-gray-500">{{ $plan->currency }}</span></p>
                            </div>
                        </div>

                        @php $features = $plan->getFeatures('ar'); @endphp
                        @if(!empty($features))
                            <ul class="space-y-1 mb-4">
                                @foreach(array_slice($features, 0, 4) as $f)
                                    <li class="flex items-start gap-1.5 text-xs text-gray-600">
                                        <svg class="w-3.5 h-3.5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $f }}
                                    </li>
                                @endforeach
                                @if(count($features) > 4)
                                    <li class="text-xs text-gray-400">+ {{ count($features) - 4 }} {{ __('admin.more') }}</li>
                                @endif
                            </ul>
                        @endif

                        <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.plans.edit', $plan) }}"
                               class="flex-1 text-center px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                {{ __('admin.edit') }}
                            </a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}"
                                  onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 rounded-lg border border-red-200 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors">
                                    {{ __('admin.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
