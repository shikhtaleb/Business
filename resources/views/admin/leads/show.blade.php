@extends('layouts.admin')

@section('title', 'Lead: ' . $lead->name)
@section('page-title', 'Lead Details')

@section('content')
<div class="space-y-5 max-w-5xl">

    {{-- Back + Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.leads.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Leads
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.leads.edit', $lead) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Lead
            </a>
            <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}"
                  onsubmit="return confirm('Delete this lead?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-red-200 bg-white text-sm font-medium text-red-600 hover:bg-red-50">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Lead Details Card --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $lead->name }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $lead->email }}</p>
                    </div>
                    @php
                        $statusBadge = [
                            'new'       => 'bg-blue-100 text-blue-700',
                            'contacted' => 'bg-yellow-100 text-yellow-700',
                            'qualified' => 'bg-green-100 text-green-700',
                            'lost'      => 'bg-red-100 text-red-700',
                            'converted' => 'bg-purple-100 text-purple-700',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusBadge[$lead->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($lead->status) }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500 font-medium">Phone</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $lead->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">Source</dt>
                        <dd class="text-gray-900 mt-0.5">{{ str_replace('_', ' ', $lead->source) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">Priority</dt>
                        <dd class="mt-0.5">
                            @php
                                $priorityBadge = [
                                    'low'    => 'bg-gray-100 text-gray-600',
                                    'normal' => 'bg-blue-100 text-blue-700',
                                    'high'   => 'bg-orange-100 text-orange-700',
                                    'urgent' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityBadge[$lead->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($lead->priority) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">Language</dt>
                        <dd class="text-gray-900 mt-0.5">{{ strtoupper($lead->lang) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">Last Contacted</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $lead->last_contacted_at?->format('d M Y, H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium">Created</dt>
                        <dd class="text-gray-900 mt-0.5">{{ $lead->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($lead->message)
                    <div class="col-span-2">
                        <dt class="text-gray-500 font-medium">Original Message</dt>
                        <dd class="mt-0.5">
                            <a href="{{ route('admin.messages.show', $lead->message) }}"
                               class="text-[#FF8528] hover:underline text-sm">View message: {{ $lead->message->subject }}</a>
                        </dd>
                    </div>
                    @endif
                    @if($lead->notes)
                    <div class="col-span-2">
                        <dt class="text-gray-500 font-medium">Notes</dt>
                        <dd class="text-gray-900 mt-0.5 whitespace-pre-line">{{ $lead->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Notes Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-sm font-bold text-gray-900 mb-4">Activity Notes</h4>

                @if($lead->notes->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-6">No notes yet. Add the first note below.</p>
                @else
                    <div class="space-y-4 mb-6">
                        @foreach($lead->notes->sortByDesc('created_at') as $note)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                 style="background:linear-gradient(135deg,#FF8528,#c45e00);">
                                {{ strtoupper(substr($note->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-1 bg-gray-50 rounded-xl p-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-semibold text-gray-700">{{ $note->user->name ?? 'Unknown' }}</span>
                                    <span class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $note->note }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- Add Note Form --}}
                <form method="POST" action="{{ route('admin.leads.note', $lead) }}">
                    @csrf
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Add Note</label>
                    <textarea name="note" rows="3" placeholder="Write a note..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                              style="--tw-ring-color:#FF8528;"></textarea>
                    @error('note')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                            class="mt-2 px-4 py-2 rounded-xl text-white text-sm font-medium"
                            style="background:#FF8528;">Add Note</button>
                </form>
            </div>
        </div>

        {{-- Sidebar: Quick Updates --}}
        <div class="space-y-5">

            {{-- Update Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-bold text-gray-900 mb-3">Update Status</h4>
                <form method="POST" action="{{ route('admin.leads.status', $lead) }}">
                    @csrf
                    <select name="status"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent mb-3"
                            style="--tw-ring-color:#FF8528;">
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full py-2 rounded-xl text-white text-sm font-medium"
                            style="background:#FF8528;">Update Status</button>
                </form>
            </div>

            {{-- Update Priority --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h4 class="text-sm font-bold text-gray-900 mb-3">Priority &amp; Assignment</h4>
                <form method="POST" action="{{ route('admin.leads.update', $lead) }}" class="space-y-3">
                    @csrf @method('PUT')
                    {{-- Hidden fields to keep other data --}}
                    <input type="hidden" name="name" value="{{ $lead->name }}">
                    <input type="hidden" name="email" value="{{ $lead->email }}">
                    <input type="hidden" name="phone" value="{{ $lead->phone }}">
                    <input type="hidden" name="source" value="{{ $lead->source }}">
                    <input type="hidden" name="status" value="{{ $lead->status }}">
                    <input type="hidden" name="notes" value="{{ $lead->notes }}">
                    <input type="hidden" name="lang" value="{{ $lead->lang }}">

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Priority</label>
                        <select name="priority"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#FF8528;">
                            @foreach($priorities as $p)
                                <option value="{{ $p }}" {{ $lead->priority === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Assigned To</label>
                        <select name="assigned_to"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent"
                                style="--tw-ring-color:#FF8528;">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $lead->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full py-2 rounded-xl text-white text-sm font-medium"
                            style="background:#FF8528;">Save Changes</button>
                </form>
            </div>

            {{-- Meta Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 text-xs text-gray-500 space-y-2">
                <div class="flex justify-between">
                    <span>Created</span>
                    <span class="text-gray-700">{{ $lead->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Updated</span>
                    <span class="text-gray-700">{{ $lead->updated_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Notes count</span>
                    <span class="text-gray-700">{{ $lead->notes->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
