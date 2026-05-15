@extends('layouts.admin')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-lg space-y-6">

    {{-- Profile Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Profile Information</h2>
        </div>
        <form method="POST" action="{{ route('admin.profile.update') }}" class="px-6 py-6 space-y-5">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;">
            </div>

            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                style="background-color:#FF8528;"
                onmouseover="this.style.backgroundColor='#E06800'"
                onmouseout="this.style.backgroundColor='#FF8528'">
                Update Profile
            </button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Change Password</h2>
        </div>
        <form method="POST" action="{{ route('admin.profile.password') }}" class="px-6 py-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                <input type="password" name="password" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;"
                    placeholder="Min. 8 characters">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;">
            </div>

            <button type="submit"
                class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                style="background-color:#FF8528;"
                onmouseover="this.style.backgroundColor='#E06800'"
                onmouseout="this.style.backgroundColor='#FF8528'">
                Update Password
            </button>
        </form>
    </div>

</div>
@endsection
