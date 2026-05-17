@extends('layouts.admin')

@section('title', 'الأدوار والصلاحيات')
@section('page-title', 'الأدوار والصلاحيات')

@section('content')
<div x-data="{
    createOpen: {{ $errors->any() ? 'true' : 'false' }},
    editOpen: false,
    editRole: { id: null, name: '', permissions: [] },
    openEdit(role) {
        this.editRole = role;
        this.editOpen = true;
    }
}">

    {{-- ════════ Create Role Modal ════════ --}}
    <div x-show="createOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="createOpen = false">

        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="createOpen = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-800">إنشاء دور جديد</h3>
                    <button @click="createOpen = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.roles.store') }}" class="px-6 py-6 space-y-5">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم الدور <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-gray-900 text-sm
                                   focus:outline-none focus:ring-2 focus:border-transparent transition-shadow
                                   @error('name') border-red-400 bg-red-50 @enderror"
                            placeholder="مثال: moderator, editor, viewer">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-400">استخدم أحرفاً صغيرة وشرطات سفلية فقط.</p>
                    </div>

                    @if($permissions->count())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الصلاحيات</label>
                        <div class="space-y-1.5 max-h-52 overflow-y-auto pr-1">
                            @foreach($permissions as $permission)
                                <label class="flex items-center gap-3 p-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                        {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-gray-300" style="accent-color: #FF8528;">
                                    <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all"
                            style="background-color:#FF8528;"
                            onmouseover="this.style.backgroundColor='#E06800'"
                            onmouseout="this.style.backgroundColor='#FF8528'">
                            إنشاء الدور
                        </button>
                        <button type="button" @click="createOpen = false"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">
                            إلغاء
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ════════ Edit Role Modal ════════ --}}
    <div x-show="editOpen" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="editOpen = false">

        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="editOpen = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800">
                            الدور: <span x-text="editRole.name" style="color:#FF8528;"></span>
                        </h3>
                        <p class="text-sm text-gray-500 mt-0.5">اختر الصلاحيات التي سيمتلكها هذا الدور.</p>
                    </div>
                    <button @click="editOpen = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- One form per role, shown via x-show --}}
                @foreach($roles as $role)
                    <form method="POST" action="{{ route('admin.roles.update', $role->id) }}"
                          x-show="editRole.id === {{ $role->id }}"
                          class="px-6 py-5">
                        @csrf
                        @method('PUT')

                        @if($permissions->isEmpty())
                            <p class="text-sm text-gray-500 mb-4">لا توجد صلاحيات محددة بعد.</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4 max-h-64 overflow-y-auto pr-1">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center gap-3 p-2.5 rounded-xl border border-gray-200 hover:bg-orange-50 hover:border-orange-200 transition-colors cursor-pointer">
                                        <input type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                            class="w-4 h-4 rounded border-gray-300"
                                            style="accent-color: #FF8528;">
                                        <span class="text-sm text-gray-800">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center gap-3 pt-3 border-t border-gray-100">
                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all"
                                style="background-color:#FF8528;"
                                onmouseover="this.style.backgroundColor='#E06800'"
                                onmouseout="this.style.backgroundColor='#FF8528'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                حفظ الصلاحيات
                            </button>
                            <button type="button" @click="editOpen = false"
                                class="px-4 py-2.5 rounded-xl text-sm text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">
                                إلغاء
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Roles List --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-800">جميع الأدوار</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ $roles->count() }} دور</p>
            </div>
            <button @click="createOpen = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-semibold shadow-md hover:shadow-lg transition-all"
                style="background-color:#FF8528;"
                onmouseover="this.style.backgroundColor='#E06800'"
                onmouseout="this.style.backgroundColor='#FF8528'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                دور جديد
            </button>
        </div>

        <div class="divide-y divide-gray-50">
            @forelse($roles as $role)
                <div class="px-6 py-4 flex items-center gap-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color:#FFF4EA;">
                        <svg class="w-4 h-4" fill="none" stroke="#FF8528" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-800">{{ $role->name }}</span>
                            @if(in_array($role->name, ['super_admin', 'editor', 'viewer']))
                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">نظامي</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $role->permissions_count }} صلاحية
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button"
                            @click="openEdit({
                                id: {{ $role->id }},
                                name: {{ json_encode($role->name) }},
                                permissions: {{ json_encode($role->permissions->pluck('name')->toArray()) }}
                            })"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-gray-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            تعديل الصلاحيات
                        </button>
                        @if(!in_array($role->name, ['super_admin', 'editor', 'viewer']))
                            <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}"
                                  onsubmit="return confirm('حذف الدور {{ $role->name }}؟')">
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
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <p class="text-sm text-gray-500">لا توجد أدوار بعد. أنشئ أول دور.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
