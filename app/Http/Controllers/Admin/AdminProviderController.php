<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\ProviderDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminProviderController extends Controller
{
    /**
     * Display providers list with verification status filters.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $query = Provider::with(['user', 'documents'])->latest();

        if ($status !== 'all') {
            $query->where('verification', $status);
        }

        $providers = $query->paginate(12)->withQueryString();

        $counts = [
            'all' => Provider::count(),
            'pending' => Provider::where('verification', 'pending')->count(),
            'approved' => Provider::where('verification', 'approved')->count(),
            'rejected' => Provider::where('verification', 'rejected')->count(),
        ];

        return view('admin.providers.index', compact('providers', 'status', 'counts'));
    }

    /**
     * View detailed provider profile and KYC documents.
     */
    public function show(string $id): View
    {
        $provider = Provider::with([
            'user',
            'documents',
            'services.service',
            'serviceAreas.city',
            'bankAccounts',
            'reviews.customer',
            'bookings' => fn ($q) => $q->latest()->take(5),
        ])->findOrFail($id);

        return view('admin.providers.show', compact('provider'));
    }

    /**
     * Review & verify a provider document (approve or reject).
     */
    public function verifyDocument(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $document = ProviderDocument::findOrFail($id);
        $document->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // If all documents approved, update provider status
        $provider = Provider::findOrFail($document->provider_id);
        $pendingDocs = ProviderDocument::where('provider_id', $provider->user_id)
            ->where('status', '!=', 'approved')
            ->count();

        if ($pendingDocs === 0) {
            $provider->update([
                'verification' => 'approved',
                'onboarded_at' => now(),
            ]);
        } elseif ($request->status === 'rejected') {
            $provider->update([
                'verification' => 'rejected',
            ]);
        }

        return back()->with('success', "Document status updated to {$request->status} successfully.");
    }

    /**
     * Update overall provider verification status directly.
     */
    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'verification' => 'required|in:pending,in_review,approved,rejected',
        ]);

        $provider = Provider::findOrFail($id);
        $provider->update([
            'verification' => $request->verification,
            'onboarded_at' => $request->verification === 'approved' ? now() : $provider->onboarded_at,
        ]);

        return back()->with('success', "Provider verification updated to {$request->verification}.");
    }
}

