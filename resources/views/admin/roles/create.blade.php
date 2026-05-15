@extends('layouts.admin')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">New Role</h2>
            <p class="text-sm text-gray-500 mt-0.5">Define a name and assign permissions to the new role.</p>
        </div>

        <form method="POST" action="{{ route('admin.roles.store') }}" class="px-6 py-6 space-y-6">
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

            {{-- Role Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Role Name <span class="text-red-500">*</span>
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                           focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                           @error('name') border-red-400 bg-red-50 @enderror"
                    placeholder="e.g. moderator"
                >
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Permissions --}}
            @if($permissions->count())
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                    @foreach($permissions as $permission)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
                            <input type="checkbox"
                                name="permissions[]"
                                value="{{ $permission->name }}"
                                {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300"
                                style="accent-color: #FF8528;">
                            <span class="text-sm text-gray-800">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                    style="background-color:#FF8528;"
                    onmouseover="this.style.backgroundColor='#E06800'"
                    onmouseout="this.style.backgroundColor='#FF8528'">
                    Create Role
                </button>
                <a href="{{ route('admin.roles.index') }}"
                   class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
