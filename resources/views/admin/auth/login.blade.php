@extends('layouts.guest')

@section('title', 'Admin Login')

@section('content')
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-900">Sign in to your account</h2>
        <p class="mt-1 text-sm text-gray-500">Enter your credentials to access the admin panel</p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-700">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-5 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
            <p class="text-sm text-green-700">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Email address
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                       placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent
                       transition-shadow @error('email') border-red-400 bg-red-50 @enderror"
                style="--tw-ring-color: #FF8528;"
                placeholder="admin@example.com"
            >
            @error('email')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                Password
            </label>
            <div class="relative" x-data="{ showPwd: false }">
                <input
                    id="password"
                    :type="showPwd ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="w-full px-3.5 py-2.5 pr-10 rounded-xl border border-gray-300 text-gray-900 text-sm
                           placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent
                           transition-shadow @error('password') border-red-400 bg-red-50 @enderror"
                    placeholder="••••••••"
                >
                <button type="button"
                        @click="showPwd = !showPwd"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg x-show="!showPwd" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="showPwd" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    class="w-4 h-4 rounded border-gray-300 text-brand focus:ring-brand"
                    style="accent-color: #FF8528;"
                >
                <span class="text-sm text-gray-600">Remember me</span>
            </label>
            <a href="{{ route('admin.forgot-password') }}"
               class="text-sm font-medium hover:underline"
               style="color:#FF8528;">
                Forgot password?
            </a>
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full py-2.5 px-4 rounded-xl text-white text-sm font-semibold
                   shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2
                   transition-all duration-150 active:scale-[0.98]"
            style="background-color:#FF8528; --tw-ring-color:#FF8528;"
            onmouseover="this.style.backgroundColor='#E06800'"
            onmouseout="this.style.backgroundColor='#FF8528'"
        >
            Sign In
        </button>
    </form>
@endsection
