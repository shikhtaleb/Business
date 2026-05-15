@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page-title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">

    {{-- Create Role --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Create New Role</h2>
        </div>
        <form method="POST" action="{{ route('admin.roles.store') }}" class="px-6 py-5 flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 transition-shadow @error('name') border-red-400 @enderror"
                    style="--tw-ring-color: #FF8528;"
                    placeholder="e.g. moderator">
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all"
                style="background-color:#FF8528;"
                onmouseover="this.style.backgroundColor='#E06800'"
                onmouseout="this.style.backgroundColor='#FF8528'">
                Create Role
            </button>
        </form>
    </div>

    {{-- Roles List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">All Roles</h2>
        </div>

        <div class="divide-y divide-gray-50">
            @foreach($roles as $role)
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-800">{{ $role->name }}</span>
                            @if(in_array($role->name, ['super_admin', 'editor', 'viewer']))
                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">system</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $role->permissions_count }} permission(s)
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                            class="px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-gray-600">
                            Edit Permissions
                        </a>
                        @if(!in_array($role->name, ['super_admin', 'editor', 'viewer']))
                            <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}"
                                  onsubmit="return confirm('Delete role {{ $role->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 hover:bg-red-50 text-red-400 hover:text-red-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
