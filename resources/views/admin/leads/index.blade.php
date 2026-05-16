@extends('layouts.admin')

@section('title', 'Leads / CRM')
@section('page-title', 'Leads / CRM')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Leads</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage your CRM leads pipeline</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.leads.export') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.leads.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-white text-sm font-medium transition-colors"
               style="background:#FF8528;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Lead
            </a>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex overflow-x-auto border-b border-gray-100">
            @php
                $tabStatuses = [
                    'all'       => ['label' => 'All',       'color' => 'gray'],
                    'new'       => ['label' => 'New',       'color' => 'blue'],
                    'contacted' => ['label' => 'Contacted', 'color' => 'yellow'],
                    'qualified' => ['label' => 'Qualified', 'color' => 'green'],
                    'lost'      => ['label' => 'Lost',      'color' => 'red'],
                    'converted' => ['label' => 'Converted', 'color' => 'purple'],
                ];
            @endphp
            @foreach($tabStatuses as $key => $tab)
                <a href="{{ route('admin.leads.index', array_merge(request()->query(), ['status' => $key])) }}"
                   class="flex items-center gap-2 px-5 py-3.5 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
                          {{ $status === $key
                              ? 'border-[#FF8528] text-[#FF8528]'
                              : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ $tab['label'] }}
                    <span class="px-1.5 py-0.5 rounded-full text-xs font-bold
                                 {{ $status === $key ? 'bg-[#FF8528] text-white' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts[$key] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Filters --}}
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
            <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-wrap gap-3">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search name, email, phone..."
                       class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent sm:w-64"
                       style="--tw-ring-color:#FF8528;">
                <select name="priority"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:border-transparent sm:w-40"
                        style="--tw-ring-color:#FF8528;">
                    <option value="">All Priorities</option>
                    @foreach(['low','normal','high','urgent'] as $p)
                        <option value="{{ $p }}" {{ $priority === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl text-white text-sm font-medium"
                        style="background:#FF8528;">Filter</button>
                @if($search || $priority)
                    <a href="{{ route('admin.leads.index', ['status' => $status]) }}"
                       class="px-4 py-2.5 rounded-xl border border-gray-300 bg-white text-sm text-gray-600 hover:bg-gray-50">Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-start font-semibold">Lead</th>
                        <th class="px-5 py-3 text-start font-semibold">Phone</th>
                        <th class="px-5 py-3 text-start font-semibold">Source</th>
                        <th class="px-5 py-3 text-start font-semibold">Priority</th>
                        <th class="px-5 py-3 text-start font-semibold">Status</th>
                        <th class="px-5 py-3 text-start font-semibold">Assigned</th>
                        <th class="px-5 py-3 text-start font-semibold">Last Contact</th>
                        <th class="px-5 py-3 text-end font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.leads.show', $lead) }}" class="font-semibold text-gray-900 hover:text-[#FF8528]">{{ $lead->name }}</a>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $lead->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $lead->phone ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            @php
                                $sourceBadge = [
                                    'contact_form' => 'bg-blue-100 text-blue-700',
                                    'manual'       => 'bg-gray-100 text-gray-700',
                                    'import'       => 'bg-purple-100 text-purple-700',
                                    'api'          => 'bg-cyan-100 text-cyan-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sourceBadge[$lead->source] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ str_replace('_', ' ', $lead->source) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
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
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $statusBadge = [
                                    'new'       => 'bg-blue-100 text-blue-700',
                                    'contacted' => 'bg-yellow-100 text-yellow-700',
                                    'qualified' => 'bg-green-100 text-green-700',
                                    'lost'      => 'bg-red-100 text-red-700',
                                    'converted' => 'bg-purple-100 text-purple-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadge[$lead->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $lead->assignedTo?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-gray-500 text-xs">
                            {{ $lead->last_contacted_at?->diffForHumans() ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.leads.show', $lead) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-[#FF8528] hover:bg-orange-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.leads.edit', $lead) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}"
                                      onsubmit="return confirm('Delete this lead?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm font-medium">No leads found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $leads->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
