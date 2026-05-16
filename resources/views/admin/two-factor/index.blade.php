@extends('layouts.admin')
@section('title', 'المصادقة الثنائية')
@section('page-title', 'المصادقة الثنائية (2FA)')

@section('content')
<div class="max-w-2xl space-y-5">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Status Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl {{ $enabled ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                @if($enabled)
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                @else
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                @endif
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">المصادقة الثنائية</h2>
                <p class="text-sm {{ $enabled ? 'text-green-600' : 'text-gray-500' }} mt-0.5">
                    {{ $enabled ? 'مفعّلة — حسابك محمي بطبقة إضافية' : 'غير مفعّلة — يُنصح بتفعيلها لحماية حسابك' }}
                </p>
            </div>
        </div>

        @if(!$enabled)
            {{-- Enable 2FA --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 text-sm text-blue-700">
                <p class="font-medium mb-1">كيفية التفعيل:</p>
                <ol class="list-decimal list-inside space-y-1 text-blue-600">
                    <li>انقر على "تفعيل 2FA" أدناه</li>
                    <li>افتح تطبيق المصادقة (Google Authenticator, Authy, إلخ)</li>
                    <li>امسح رمز QR أو أدخل المفتاح يدوياً</li>
                    <li>أدخل الرمز المكوّن من 6 أرقام للتأكيد</li>
                </ol>
            </div>
            <form method="POST" action="{{ route('admin.two-factor.enable') }}">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl text-white font-semibold" style="background:#FF8528;">
                    تفعيل المصادقة الثنائية
                </button>
            </form>

            @if(session('2fa_setup_secret') || (!empty($user->totp_secret) && !$user->two_factor_enabled))
                @php
                    $secret = session('2fa_setup_secret') ?? '';
                    $issuer = config('app.name', 'Retont');
                    $otpauth = "otpauth://totp/{$issuer}:{$user->email}?secret={$secret}&issuer={$issuer}";
                @endphp
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <h3 class="font-medium text-gray-900 mb-3">امسح رمز QR</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
                        <div class="font-mono text-sm bg-white border border-gray-200 rounded-lg px-4 py-2 inline-block mb-3 tracking-widest text-gray-800">
                            {{ $secret }}
                        </div>
                        <p class="text-xs text-gray-500">أدخل هذا المفتاح في تطبيق المصادقة إذا تعذّر مسح الرمز</p>
                    </div>
                    <form method="POST" action="{{ route('admin.two-factor.verify') }}" class="mt-4 space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رمز التحقق (6 أرقام)</label>
                            <input type="text" name="code" required maxlength="6" pattern="\d{6}" placeholder="000000"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-center text-2xl font-mono tracking-widest focus:outline-none focus:ring-2 focus:ring-orange-200">
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-xl text-white font-semibold" style="background:#FF8528;">
                            تأكيد التفعيل
                        </button>
                    </form>
                </div>
            @endif

        @else
            {{-- Disable 2FA --}}
            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 mb-5 text-sm text-yellow-700">
                <strong>تحذير:</strong> تعطيل المصادقة الثنائية يجعل حسابك أقل أماناً. تأكد أنك تريد المتابعة.
            </div>
            <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="space-y-3" onsubmit="return confirm('تأكيد تعطيل 2FA؟')">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور للتأكيد</label>
                    <input type="password" name="password" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-200">
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl border border-red-300 text-red-600 font-medium hover:bg-red-50 transition-colors">
                    تعطيل المصادقة الثنائية
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
