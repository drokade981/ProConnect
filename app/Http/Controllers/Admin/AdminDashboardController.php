<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\ProviderDocument;
use App\Models\Service;
use App\Models\SupportTicket;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Command Dashboard with key marketplace metrics.
     */
    public function index(): View
    {
        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('status', 'captured')->sum('amount');
        $activeProviders = Provider::where('verification', 'approved')->count();
        $pendingVerifications = ProviderDocument::where('status', 'pending')->count();
        $openTickets = SupportTicket::where('status', 'open')->count();
        $totalServices = Service::count();

        $recentBookings = Booking::with(['customer', 'provider', 'items.service'])
            ->latest()
            ->take(6)
            ->get();

        $pendingDocuments = ProviderDocument::with('provider.user')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $categories = Category::withCount('services')->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'activeProviders',
            'pendingVerifications',
            'openTickets',
            'totalServices',
            'recentBookings',
            'pendingDocuments',
            'categories'
        ));
    }
}

