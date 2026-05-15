@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Edit: {{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">Update user information and role assignment.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                <input type="password" name="password"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;"
                    placeholder="Min. 8 characters">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                <select name="role" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-700 focus:outline-none focus:ring-2 transition-shadow"
                    style="--tw-ring-color: #FF8528;">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="force_password_reset" id="fpr" value="1"
                    {{ $user->force_password_reset ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300" style="accent-color: #FF8528;">
                <label for="fpr" class="text-sm text-gray-700 cursor-pointer">Force password reset on next login</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                    style="background-color:#FF8528;"
                    onmouseover="this.style.backgroundColor='#E06800'"
                    onmouseout="this.style.backgroundColor='#FF8528'">
                    Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
