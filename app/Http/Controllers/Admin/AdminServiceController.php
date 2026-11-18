<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\CityServicePrice;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminServiceController extends Controller
{
    /**
     * Display service catalog & category manager.
     */
    public function index(): View
    {
        $categories = Category::with(['services.cityPrices.city'])->orderBy('sort_order')->get();
        $cities = City::where('is_active', true)->get();

        return view('admin.services.index', compact('categories', 'cities'));
    }

    /**
     * Store a new service category.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon_url' => 'nullable|url',
            'requires_license' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.time(),
            'parent_id' => $validated['parent_id'] ?? null,
            'icon_url' => $validated['icon_url'] ?? null,
            'requires_license' => $request->boolean('requires_license'),
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'New service category created successfully.');
    }

    /**
     * Store a new service with initial base pricing.
     */
    public function storeService(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pricing_model' => 'required|in:fixed,hourly,per_unit,quote',
            'mode' => 'required|in:at_customer,at_provider,online',
            'duration_min' => 'nullable|integer|min:5',
            'base_price' => 'nullable|numeric|min:0',
            'city_id' => 'nullable|exists:cities,id',
        ]);

        $service = Service::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'pricing_model' => $validated['pricing_model'],
            'mode' => $validated['mode'],
            'duration_min' => $validated['duration_min'] ?? 60,
            'is_active' => true,
        ]);

        if (! empty($validated['base_price']) && ! empty($validated['city_id'])) {
            CityServicePrice::create([
                'service_id' => $service->id,
                'city_id' => $validated['city_id'],
                'base_price' => $validated['base_price'],
                'unit_label' => $service->pricing_model === 'hourly' ? 'hour' : 'visit',
                'platform_commission_pct' => 20.00,
            ]);
        }

        return back()->with('success', 'Service added to marketplace catalog successfully.');
    }

    /**
     * Toggle active status of a service.
     */
    public function toggleService(int $id): RedirectResponse
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => ! $service->is_active]);

        $status = $service->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Service {$service->name} has been {$status}.");
    }
}

