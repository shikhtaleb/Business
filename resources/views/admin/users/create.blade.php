@extends('layouts.admin')

@section('title', 'إنشاء مستخدم')
@section('page-title', 'إنشاء مستخدم جديد')

@section('content')

    <div class="max-w-lg">

        <div class="mb-4">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                العودة للمستخدمين
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">إنشاء مستخدم جديد</h2>
                <p class="text-sm text-gray-500 mt-0.5">أدخل بيانات المستخدم الجديد للوحة التحكم.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="px-6 py-6 space-y-5">
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

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">الاسم الكامل</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-shadow @error('name') border-red-400 bg-red-50 @enderror"
                        style="--tw-ring-color:#FF8528;" placeholder="محمد أحمد">
                    @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-shadow @error('email') border-red-400 bg-red-50 @enderror"
                        style="--tw-ring-color:#FF8528;" placeholder="user@example.com">
                    @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">كلمة المرور</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-shadow @error('password') border-red-400 bg-red-50 @enderror"
                        style="--tw-ring-color:#FF8528;" placeholder="٨ أحرف على الأقل">
                    @error('password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                        style="--tw-ring-color:#FF8528;" placeholder="أعد إدخال كلمة المرور">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">الدور</label>
                    <select id="role" name="role" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-shadow @error('role') border-red-400 @enderror"
                        style="--tw-ring-color:#FF8528;">
                        <option value="">اختر دوراً...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                        style="background-color:#FF8528;"
                        onmouseover="this.style.backgroundColor='#E06800'"
                        onmouseout="this.style.backgroundColor='#FF8528'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        إنشاء المستخدم
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
