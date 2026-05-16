<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $status   = $request->get('status', 'all');
        $priority = $request->get('priority');
        $search   = $request->get('search');

        $query = Ticket::with('assignedTo')->orderBy('updated_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }
        if ($priority) {
            $query->where('priority', $priority);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('requester_name', 'like', "%{$search}%")
                  ->orWhere('requester_email', 'like', "%{$search}%");
            });
        }

        $tickets = $query->paginate(20)->withQueryString();

        $counts = [];
        foreach (['open', 'in_progress', 'resolved', 'closed'] as $s) {
            $counts[$s] = Ticket::where('status', $s)->count();
        }
        $counts['all'] = Ticket::count();

        $users = User::orderBy('name')->get();

        return view('admin.tickets.index', compact('tickets', 'status', 'priority', 'search', 'counts', 'users'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load(['assignedTo', 'replies.user']);
        $users = User::orderBy('name')->get();

        return view('admin.tickets.show', compact('ticket', 'users'));
    }

    public function reply(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate([
            'body'        => 'required|string|max:10000',
            'is_internal' => 'nullable|boolean',
        ]);

        TicketReply::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => Auth::id(),
            'body'        => $request->input('body'),
            'is_internal' => $request->boolean('is_internal'),
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        $ticket->touch();

        return back()->with('success', 'Reply added successfully.');
    }

    public function updateStatus(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $data = ['status' => $request->input('status')];

        if ($request->input('status') === 'resolved' && !$ticket->resolved_at) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        if ($request->wantsJson()) {
            return back()->with('success', 'Status updated.');
        }

        return back()->with('success', 'Ticket status updated.');
    }

    public function updatePriority(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate([
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $ticket->update(['priority' => $request->input('priority')]);

        if ($request->wantsJson()) {
            return back()->with('success', 'Priority updated.');
        }

        return back()->with('success', 'Ticket priority updated.');
    }

    public function assign(Request $request, Ticket $ticket): RedirectResponse
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update(['assigned_to' => $request->input('assigned_to')]);

        if ($request->wantsJson()) {
            return back()->with('success', 'Ticket assigned.');
        }

        return back()->with('success', 'Ticket assigned successfully.');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted.');
    }
}
