@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role Permissions')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-gray-800">Role: <span style="color:#FF8528;">{{ $role->name }}</span></h2>
            <p class="text-sm text-gray-500 mt-0.5">Select which permissions this role should have.</p>
        </div>
        <a href="{{ route('admin.roles.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-gray-600">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Roles
        </a>
    </div>

    <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="px-6 py-6">
        @csrf
        @method('PUT')

        @if($permissions->isEmpty())
            <p class="text-sm text-gray-500 mb-6">No permissions have been defined yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                @foreach($permissions as $permission)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:bg-orange-50 hover:border-orange-200 transition-colors cursor-pointer">
                        <input type="checkbox"
                            name="permissions[]"
                            value="{{ $permission->name }}"
                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300"
                            style="accent-color: #FF8528;">
                        <span class="text-sm font-medium text-gray-800">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
        @endif

        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                style="background-color:#FF8528;"
                onmouseover="this.style.backgroundColor='#E06800'"
                onmouseout="this.style.backgroundColor='#FF8528'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Permissions
            </button>
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
