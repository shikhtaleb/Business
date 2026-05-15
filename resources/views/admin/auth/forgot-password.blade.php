@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-900">Forgot your password?</h2>
        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
            No problem. Enter your email address below and we will send you a
            password reset link so you can choose a new one.
        </p>
    </div>

    <!-- Session Flash Success -->
    @if (session('status'))
        <div class="mb-5 flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-green-700">{{ session('status') }}</p>
        </div>
    @endif

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

    <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-5">
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
                placeholder="admin@example.com"
            >
            @error('email')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full py-2.5 px-4 rounded-xl text-white text-sm font-semibold
                   shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2
                   transition-all duration-150 active:scale-[0.98]"
            style="background-color:#FF8528;"
            onmouseover="this.style.backgroundColor='#E06800'"
            onmouseout="this.style.backgroundColor='#FF8528'"
        >
            Send Reset Link
        </button>
    </form>

    <!-- Back to login -->
    <div class="mt-6 text-center">
        <a href="{{ route('admin.login') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to login
        </a>
    </div>
@endsection
