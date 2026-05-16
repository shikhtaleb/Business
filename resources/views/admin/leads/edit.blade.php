@extends('layouts.admin')

@section('title', 'Edit Lead: ' . $lead->name)
@section('page-title', 'Edit Lead')

@section('content')
<div class="max-w-2xl">

    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.leads.show', $lead) }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Lead
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-base font-bold text-gray-900 mb-5">Edit Lead</h3>

        <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $lead->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $lead->email) }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                           style="--tw-ring-color:#FF8528;">
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Source</label>
                    <select name="source"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($sources as $s)
                            <option value="{{ $s }}" {{ old('source', $lead->source) === $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Status</label>
                    <select name="status"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ old('status', $lead->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Priority</label>
                    <select name="priority"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($priorities as $p)
                            <option value="{{ $p }}" {{ old('priority', $lead->priority) === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Assigned To</label>
                    <select name="assigned_to"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Language</label>
                    <select name="lang"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                            style="--tw-ring-color:#FF8528;">
                        @foreach(['ar' => 'Arabic', 'en' => 'English', 'nl' => 'Dutch', 'de' => 'German'] as $code => $label)
                            <option value="{{ $code }}" {{ old('lang', $lead->lang) === $code ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="4"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                              style="--tw-ring-color:#FF8528;">{{ old('notes', $lead->notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold"
                        style="background:#FF8528;">Save Changes</button>
                <a href="{{ route('admin.leads.show', $lead) }}"
                   class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
