<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Provider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminBookingController extends Controller
{
    /**
     * Display live booking dispatch monitoring.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $query = Booking::with(['customer', 'provider', 'address.city', 'items.service'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Booking::count(),
            'searching' => Booking::where('status', 'searching')->count(),
            'assigned' => Booking::where('status', 'assigned')->count(),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'status', 'statusCounts'));
    }

    /**
     * Update booking status or override dispatch.
     */
    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:created,searching,assigned,accepted,en_route,in_progress,completed,cancelled,failed',
            'reason' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;

        $booking->update([
            'status' => $request->status,
            'cancelled_by' => $request->status === 'cancelled' ? Auth::id() : $booking->cancelled_by,
            'cancel_reason' => $request->status === 'cancelled' ? ($request->reason ?? 'Admin override') : $booking->cancel_reason,
        ]);

        BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'from_status' => $oldStatus,
            'to_status' => $request->status,
            'changed_by' => Auth::id(),
            'changed_at' => now(),
            'meta' => ['admin_override' => true, 'note' => $request->reason],
        ]);

        return back()->with('success', "Booking #".substr($booking->id, 0, 8)." updated to {$request->status}.");
    }
}

