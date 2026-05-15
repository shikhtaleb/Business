@extends('layouts.admin')

@section('title', 'General Settings')
@section('page-title', 'General Settings')

@section('content')

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Site Configuration</h2>
                <p class="text-sm text-gray-500 mt-0.5">Basic settings for your website.</p>
            </div>

            <form method="POST" action="{{ route('admin.settings.general.save') }}" class="px-6 py-6 space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Site Name --}}
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Site Name
                    </label>
                    <input
                        id="site_name"
                        type="text"
                        name="site_name"
                        value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                               @error('site_name') border-red-400 bg-red-50 @enderror"
                        placeholder="Retont Business"
                    >
                    @error('site_name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Site URL --}}
                <div>
                    <label for="site_url" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Site URL
                    </label>
                    <input
                        id="site_url"
                        type="url"
                        name="site_url"
                        value="{{ old('site_url', $settings['site_url'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                               @error('site_url') border-red-400 bg-red-50 @enderror"
                        placeholder="https://example.com"
                    >
                    @error('site_url')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Language --}}
                <div>
                    <label for="default_language" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Default Language
                    </label>
                    <select
                        id="default_language"
                        name="default_language"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @foreach (['ar' => 'Arabic (العربية)', 'en' => 'English', 'nl' => 'Dutch (Nederlands)', 'de' => 'German (Deutsch)'] as $code => $label)
                            <option value="{{ $code }}"
                                {{ old('default_language', $settings['default_language'] ?? 'en') === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Timezone --}}
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Timezone
                    </label>
                    <select
                        id="timezone"
                        name="timezone"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                    >
                        @php
                            $timezones = [
                                'UTC'                    => 'UTC',
                                'Europe/London'          => 'Europe/London (GMT)',
                                'Europe/Amsterdam'       => 'Europe/Amsterdam (CET)',
                                'Europe/Berlin'          => 'Europe/Berlin (CET)',
                                'Europe/Paris'           => 'Europe/Paris (CET)',
                                'Europe/Rome'            => 'Europe/Rome (CET)',
                                'Europe/Madrid'          => 'Europe/Madrid (CET)',
                                'Europe/Istanbul'        => 'Europe/Istanbul (TRT)',
                                'Asia/Riyadh'            => 'Asia/Riyadh (AST)',
                                'Asia/Dubai'             => 'Asia/Dubai (GST)',
                                'Asia/Kuwait'            => 'Asia/Kuwait (AST)',
                                'Asia/Baghdad'           => 'Asia/Baghdad (AST)',
                                'Asia/Beirut'            => 'Asia/Beirut (EET)',
                                'Africa/Cairo'           => 'Africa/Cairo (EET)',
                                'America/New_York'       => 'America/New_York (EST)',
                                'America/Chicago'        => 'America/Chicago (CST)',
                                'America/Denver'         => 'America/Denver (MST)',
                                'America/Los_Angeles'    => 'America/Los_Angeles (PST)',
                                'America/Toronto'        => 'America/Toronto (EST)',
                                'America/Sao_Paulo'      => 'America/Sao_Paulo (BRT)',
                                'Asia/Tokyo'             => 'Asia/Tokyo (JST)',
                                'Asia/Singapore'         => 'Asia/Singapore (SGT)',
                                'Asia/Shanghai'          => 'Asia/Shanghai (CST)',
                                'Asia/Kolkata'           => 'Asia/Kolkata (IST)',
                                'Australia/Sydney'       => 'Australia/Sydney (AEDT)',
                                'Pacific/Auckland'       => 'Pacific/Auckland (NZDT)',
                            ];
                            $currentTz = old('timezone', $settings['timezone'] ?? 'UTC');
                        @endphp
                        @foreach ($timezones as $tz => $label)
                            <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Google Analytics ID --}}
                <div>
                    <label for="ga_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Google Analytics ID
                        <span class="text-gray-400 font-normal ml-1">(optional)</span>
                    </label>
                    <input
                        id="ga_id"
                        type="text"
                        name="ga_id"
                        value="{{ old('ga_id', $settings['ga_id'] ?? '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                               focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                               font-mono"
                        placeholder="G-XXXXXXXXXX"
                    >
                    <p class="mt-1 text-xs text-gray-400">Enter your Google Analytics 4 Measurement ID.</p>
                </div>

                {{-- Maintenance Mode --}}
                <div class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-4"
                     x-data="{ maintenance: {{ old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'true' : 'false' }} }">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Maintenance Mode</p>
                        <p class="text-xs text-gray-500 mt-0.5">When enabled, the frontend will show a maintenance page to visitors.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer ml-4 flex-shrink-0">
                        <input
                            type="checkbox"
                            name="maintenance_mode"
                            value="1"
                            x-model="maintenance"
                            :checked="maintenance"
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
                                    peer-checked:after:translate-x-full peer-checked:after:border-white
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                    transition-colors"
                             :style="maintenance ? 'background-color:#FF8528;' : ''">
                        </div>
                    </label>
                </div>

                {{-- Save Button --}}
                <div class="flex items-center justify-end pt-2 border-t border-gray-100">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold
                               shadow-md hover:shadow-lg focus:outline-none transition-all duration-150 active:scale-[0.98]"
                        style="background-color:#FF8528;"
                        onmouseover="this.style.backgroundColor='#E06800'"
                        onmouseout="this.style.backgroundColor='#FF8528'"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
