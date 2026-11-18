<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminTicketController extends Controller
{
    /**
     * Display support tickets & dispute center.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $query = SupportTicket::with(['booking', 'raisedByUser', 'assignedToUser'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('admin.tickets.index', compact('tickets', 'status'));
    }

    /**
     * Resolve or update support ticket.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'status' => $request->status,
            'assigned_to' => Auth::id(),
            'resolved_at' => in_array($request->status, ['resolved', 'closed']) ? now() : null,
        ]);

        return back()->with('success', "Ticket status updated to {$request->status}.");
    }
}

