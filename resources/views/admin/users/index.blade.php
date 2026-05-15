@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'User Management')

@section('content')

    <div class="space-y-5">

        {{-- Header row --}}
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Manage admin panel users and their roles.
            </p>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold
                      shadow-md hover:shadow-lg transition-all duration-150"
               style="background-color:#FF8528;"
               onmouseover="this.style.backgroundColor='#E06800'"
               onmouseout="this.style.backgroundColor='#FF8528'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </a>
        </div>

        {{-- Table card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">All Users</h2>
                @if(isset($users) && method_exists($users, 'total'))
                    <span class="text-sm text-gray-400">{{ $users->total() }} total</span>
                @endif
            </div>

            @if(isset($users) && $users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role(s)</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Name --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                                                 style="background-color:#FF8528;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">You</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Email --}}
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>

                                    {{-- Roles --}}
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($user->roles as $role)
                                                @php
                                                    $roleColors = [
                                                        'super_admin' => 'bg-red-100 text-red-700',
                                                        'editor'      => 'bg-blue-100 text-blue-700',
                                                        'viewer'      => 'bg-gray-100 text-gray-600',
                                                    ];
                                                    $roleColor = $roleColors[$role->name] ?? 'bg-purple-100 text-purple-700';
                                                @endphp
                                                <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full {{ $roleColor }}">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">No role</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    {{-- Created --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Edit --}}
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               title="Edit user"
                                               class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200
                                                      hover:bg-gray-50 transition-colors text-gray-500 hover:text-gray-700">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            {{-- Delete (not self) --}}
                                            @if($user->id !== auth()->id())
                                                <form method="POST"
                                                      action="{{ route('admin.users.destroy', $user) }}"
                                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            title="Delete user"
                                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200
                                                                   hover:bg-red-50 transition-colors text-red-400 hover:text-red-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="w-8 h-8"></span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(method_exists($users, 'links'))
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="py-16 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">No users found</p>
                    <p class="text-sm text-gray-400 mt-1">
                        <a href="{{ route('admin.users.create') }}" class="underline" style="color:#FF8528;">Create the first user</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

@endsection
