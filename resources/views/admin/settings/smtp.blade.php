@extends('layouts.admin')
@section('title', __('admin.smtp_settings'))
@section('page-title', __('admin.smtp_settings'))

@section('content')

@php
$settingsTabs = [
    'admin.settings.general'    => ['label' => __('admin.general'),    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    'admin.settings.appearance' => ['label' => __('admin.appearance'), 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
    'admin.settings.seo'        => ['label' => __('admin.seo'),        'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
    'admin.settings.smtp'       => ['label' => __('admin.smtp'),       'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
];
@endphp

<div class="flex gap-6 items-start">
    <div class="w-52 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @foreach($settingsTabs as $routeName => $tab)
                @php $active = request()->routeIs($routeName.'*'); @endphp
                <a href="{{ route($routeName) }}"
                   class="flex items-center gap-3 px-5 py-4 text-sm font-medium transition-all duration-150 border-b border-gray-50 last:border-0
                          {{ $active ? 'text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}"
                   @if($active) style="background-color:#FF8528;" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/>
                    </svg>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex-1 min-w-0 space-y-5">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        {{-- SMTP Config --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">{{ __('admin.smtp_settings') }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ __('admin.smtp_lead') }}</p>
            </div>

            <form method="POST" action="{{ route('admin.settings.smtp.save') }}" class="px-6 py-6 space-y-5">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm font-mono focus:outline-none focus:ring-2 focus:border-transparent"
                               placeholder="smtp.gmail.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Port</label>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? '587') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm font-mono focus:outline-none focus:ring-2 focus:border-transparent"
                               placeholder="587">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Encryption</label>
                    <select name="smtp_encryption" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2">
                        @foreach(['tls' => 'TLS (port 587)', 'ssl' => 'SSL (port 465)', 'none' => 'None'] as $val => $label)
                            <option value="{{ $val }}" {{ old('smtp_encryption', $settings['smtp_encryption'] ?? 'tls') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Username / Email</label>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2"
                               placeholder="user@example.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <input type="password" name="smtp_password" value=""
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2"
                               placeholder="{{ $settings['smtp_password'] ?? '' ? '••••••••' : '' }}"
                               autocomplete="new-password">
                        @if(!empty($settings['smtp_password']))
                            <p class="mt-1 text-xs text-gray-400">{{ __('admin.leave_blank_to_keep') }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.from_email') }}</label>
                        <input type="email" name="smtp_from_email" value="{{ old('smtp_from_email', $settings['smtp_from_email'] ?? '') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2"
                               placeholder="no-reply@yourdomain.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.from_name') }}</label>
                        <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $settings['smtp_from_name'] ?? '') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2"
                               placeholder="اسم المرسل">
                    </div>
                </div>

                <div class="flex items-center justify-end pt-2 border-t border-gray-100">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all active:scale-[0.98]"
                        style="background:#FF8528;" onmouseover="this.style.background='#E06800'" onmouseout="this.style.background='#FF8528'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('admin.save_settings') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Test Email --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">{{ __('admin.test_smtp') }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ __('admin.test_smtp_lead') }}</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.smtp.test') }}" class="px-6 py-5 flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.test_email_address') }}</label>
                    <input type="email" name="test_email" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2"
                           placeholder="test@example.com">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    {{ __('admin.send_test') }}
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
