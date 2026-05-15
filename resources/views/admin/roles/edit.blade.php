@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role Permissions')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Role: <span class="text-orange-500">{{ $role->name }}</span></h2>
            <p class="text-sm text-gray-500 mt-0.5">Select which permissions this role should have.</p>
        </div>

        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="px-6 py-6">
            @csrf
            @method('PUT')

            <div class="space-y-3 mb-6">
                @foreach($permissions as $permission)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
                        <input type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->name }}"
                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300"
                            style="accent-color: #FF8528;">
                        <div>
                            <span class="text-sm font-medium text-gray-800">{{ $permission->name }}</span>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                    style="background-color:#FF8528;"
                    onmouseover="this.style.backgroundColor='#E06800'"
                    onmouseout="this.style.backgroundColor='#FF8528'">
                    Save Permissions
                </button>
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
