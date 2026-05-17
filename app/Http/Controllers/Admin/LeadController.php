<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $status   = $request->get('status', 'all');
        $priority = $request->get('priority');
        $search   = $request->get('search');

        $query = Lead::with('assignedTo')->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($priority) {
            $query->where('priority', $priority);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(20)->withQueryString();

        $counts = [];
        foreach (['new', 'contacted', 'qualified', 'lost', 'converted'] as $s) {
            $counts[$s] = Lead::where('status', $s)->count();
        }
        $counts['all'] = Lead::count();

        $users = User::orderBy('name')->get();

        return view('admin.leads.index', compact('leads', 'status', 'priority', 'search', 'counts', 'users'));
    }

    public function create(): View
    {
        $users    = User::orderBy('name')->get();
        $statuses = ['new', 'contacted', 'qualified', 'lost', 'converted'];
        $sources  = ['contact_form', 'manual', 'import', 'api'];
        $priorities = ['low', 'normal', 'high', 'urgent'];

        return view('admin.leads.create', compact('users', 'statuses', 'sources', 'priorities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:50',
            'source'     => 'required|in:contact_form,manual,import,api',
            'status'     => 'required|in:new,contacted,qualified,lost,converted',
            'priority'   => 'required|in:low,normal,high,urgent',
            'notes'      => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'lang'       => 'nullable|string|max:10',
        ]);

        Lead::create($data);

        return redirect()->route('admin.leads.index')->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead): View
    {
        $lead->load(['assignedTo', 'message', 'leadNotes.user']);
        $users      = User::orderBy('name')->get();
        $statuses   = ['new', 'contacted', 'qualified', 'lost', 'converted'];
        $priorities = ['low', 'normal', 'high', 'urgent'];

        return view('admin.leads.show', compact('lead', 'users', 'statuses', 'priorities'));
    }

    public function edit(Lead $lead): View
    {
        $users      = User::orderBy('name')->get();
        $statuses   = ['new', 'contacted', 'qualified', 'lost', 'converted'];
        $sources    = ['contact_form', 'manual', 'import', 'api'];
        $priorities = ['low', 'normal', 'high', 'urgent'];

        return view('admin.leads.edit', compact('lead', 'users', 'statuses', 'sources', 'priorities'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'source'      => 'required|in:contact_form,manual,import,api',
            'status'      => 'required|in:new,contacted,qualified,lost,converted',
            'priority'    => 'required|in:low,normal,high,urgent',
            'notes'       => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'lang'        => 'nullable|string|max:10',
        ]);

        $lead->update($data);

        return redirect()->route('admin.leads.show', $lead)->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }

    public function addNote(Request $request, Lead $lead): RedirectResponse
    {
        $request->validate([
            'note' => 'required|string|max:5000',
        ]);

        LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'note'    => $request->input('note'),
        ]);

        $lead->touch();

        return back()->with('success', 'Note added.');
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:new,contacted,qualified,lost,converted',
        ]);

        $lead->update([
            'status'             => $request->input('status'),
            'last_contacted_at'  => in_array($request->input('status'), ['contacted', 'qualified'])
                                    ? now()
                                    : $lead->last_contacted_at,
        ]);

        if ($request->wantsJson()) {
            return back()->with('success', 'Status updated.');
        }

        return back()->with('success', 'Status updated.');
    }

    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads_' . date('Y-m-d') . '.csv"',
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'Source', 'Status', 'Priority', 'Lang', 'Assigned To', 'Last Contacted', 'Created At'];

        return response()->stream(function () use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            Lead::with('assignedTo')->orderBy('id')->chunk(200, function ($leads) use ($handle) {
                foreach ($leads as $lead) {
                    fputcsv($handle, [
                        $lead->id,
                        $lead->name,
                        $lead->email,
                        $lead->phone,
                        $lead->source,
                        $lead->status,
                        $lead->priority,
                        $lead->lang,
                        $lead->assignedTo?->name ?? '',
                        $lead->last_contacted_at?->format('Y-m-d H:i') ?? '',
                        $lead->created_at->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
