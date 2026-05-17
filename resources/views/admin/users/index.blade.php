@extends('layouts.admin')

@section('title', __('admin.user_management'))
@section('page-title', __('admin.user_management'))

@section('content')

<div
    x-data="{
        showCreate: false,
        showEdit: false,
        editUser: { id: null, name: '', email: '', role: '', force_reset: false },
        openEdit(user) {
            this.editUser = user;
            this.showEdit = true;
        }
    }"
    class="space-y-5"
>

    {{-- Header row --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ __('admin.manage_users_roles') }}</p>
        <button
            type="button"
            @click="showCreate = true"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold
                   shadow-md hover:shadow-lg transition-all duration-150"
            style="background-color:#FF8528;"
            onmouseover="this.style.backgroundColor='#E06800'"
            onmouseout="this.style.backgroundColor='#FF8528'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('admin.add_user') }}
        </button>
    </div>

    {{-- Table card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-800">{{ __('admin.all_users') }}</h2>
            @if(isset($users) && method_exists($users, 'total'))
                <span class="text-sm text-gray-400">{{ $users->total() }} {{ __('admin.total') }}</span>
            @endif
        </div>

        @if(isset($users) && $users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.name') }}</th>
                            <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.email') }}</th>
                            <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.role') }}</th>
                            <th class="px-6 py-3 text-start text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.created') }}</th>
                            <th class="px-6 py-3 text-end text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('admin.actions') }}</th>
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
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">{{ __('admin.you') }}</span>
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
                                            <span class="text-xs text-gray-400 italic">{{ __('admin.no_role') }}</span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- Created --}}
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $user->created_at->translatedFormat('d M Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-end">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Edit (modal) --}}
                                        <button
                                            type="button"
                                            title="{{ __('admin.edit_user') }}"
                                            @click="openEdit({
                                                id: {{ $user->id }},
                                                name: {{ json_encode($user->name) }},
                                                email: {{ json_encode($user->email) }},
                                                role: {{ json_encode($user->roles->first()?->name ?? '') }},
                                                force_reset: {{ $user->force_password_reset ? 'true' : 'false' }}
                                            })"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200
                                                   hover:bg-gray-50 transition-colors text-gray-500 hover:text-gray-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        {{-- Delete (not self) --}}
                                        @if($user->id !== auth()->id())
                                            <form method="POST"
                                                  action="{{ route('admin.users.destroy', $user) }}"
                                                  onsubmit="return confirm('حذف {{ addslashes($user->name) }}؟ لا يمكن التراجع عن هذا.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        title="حذف المستخدم"
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
                <p class="text-gray-500 font-medium">لا يوجد مستخدمون</p>
                <p class="text-sm text-gray-400 mt-1">
                    <button type="button" @click="showCreate = true" class="underline" style="color:#FF8528;">{{ __('admin.create_user') }}</button>
                </p>
            </div>
        @endif
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════ --}}
    {{-- Create User Modal --}}
    {{-- ════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="showCreate" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="showCreate = false">

        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showCreate = false"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">{{ __('admin.create_user') }}</h2>
                        <p class="text-sm text-gray-500 mt-0.5">أدخل بيانات المستخدم الجديد في لوحة التحكم.</p>
                    </div>
                    <button @click="showCreate = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Form --}}
                <form method="POST" action="{{ route('admin.users.store') }}" class="px-6 py-5 space-y-4">
                    @csrf

                    @if ($errors->any() && old('_modal') === 'create')
                        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm text-red-700">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <input type="hidden" name="_modal" value="create">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.name') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                            placeholder="محمد أحمد">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                            placeholder="user@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.password') }}</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                            placeholder="٨ أحرف على الأقل">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                            placeholder="أعد إدخال كلمة المرور">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.role') }}</label>
                        <select name="role" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow">
                            <option value="">{{ __('admin.select_role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold
                                   shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                            style="background-color:#FF8528;"
                            onmouseover="this.style.backgroundColor='#E06800'"
                            onmouseout="this.style.backgroundColor='#FF8528'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('admin.create_user') }}
                        </button>
                        <button type="button" @click="showCreate = false"
                            class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
                            {{ __('admin.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════ --}}
    {{-- Edit User Modal --}}
    {{-- ════════════════════════════════════════════════════════════════════════ --}}
    <div x-show="showEdit" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="showEdit = false">

        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showEdit = false"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-semibold text-gray-800">{{ __('admin.edit_user') }}: <span x-text="editUser.name"></span></h2>
                        <p class="text-sm text-gray-500 mt-0.5">تحديث بيانات المستخدم وتخصيص الدور.</p>
                    </div>
                    <button @click="showEdit = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Edit Form --}}
                <form method="POST" :action="'{{ url('admin/users') }}/' + editUser.id" class="px-6 py-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.name') }}</label>
                        <input type="text" name="name" :value="editUser.name" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.email') }}</label>
                        <input type="email" name="email" :value="editUser.email" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            {{ __('admin.new_password') }}
                            <span class="text-gray-400 font-normal">({{ __('admin.leave_blank_password') }})</span>
                        </label>
                        <input type="password" name="password" autocomplete="new-password"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow"
                            placeholder="٨ أحرف على الأقل">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.confirm_new_password') }}</label>
                        <input type="password" name="password_confirmation" autocomplete="new-password"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('admin.role') }}</label>
                        <select name="role" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}"
                                    :selected="editUser.role === '{{ $role->name }}'">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="force_password_reset" id="fpr_edit" value="1"
                            :checked="editUser.force_reset"
                            class="w-4 h-4 rounded border-gray-300" style="accent-color: #FF8528;">
                        <label for="fpr_edit" class="text-sm text-gray-700 cursor-pointer">{{ __('admin.force_reset') }}</label>
                    </div>

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold
                                   shadow-md hover:shadow-lg transition-all duration-150 active:scale-[0.98]"
                            style="background-color:#FF8528;"
                            onmouseover="this.style.backgroundColor='#E06800'"
                            onmouseout="this.style.backgroundColor='#FF8528'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('admin.save_changes') }}
                        </button>
                        <button type="button" @click="showEdit = false"
                            class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition-colors">
                            {{ __('admin.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Re-open create modal if there are validation errors from create form
    @if($errors->any() && old('_modal') === 'create')
        document.addEventListener('alpine:init', () => {
            setTimeout(() => {
                const el = document.querySelector('[x-data]');
                if (el && el._x_dataStack) {
                    el._x_dataStack[0].showCreate = true;
                }
            }, 100);
        });
    @endif
</script>
@endpush

@endsection
