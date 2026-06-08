<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TechnicianProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Validations
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'latitude'    => 'nullable|numeric|between:-90,90',
            'longitude'   => 'nullable|numeric|between:-180,180',
            'radius_km'   => 'nullable|numeric|min:1|max:100',
            'search'      => 'nullable|string|max:100',
        ]);

        // 2. Query ya Msingi
        $query = TechnicianProfile::query()
            ->with(['user:id,name,phone', 'category:id,name']);

        // 3. Filter: Jina
        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            );
        }

        // 4. Filter: Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 5. Filter: GPS / Umbali
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $lat    = $request->latitude;
            $lng    = $request->longitude;
            $radius = $request->radius_km ?? 20;

            $query->selectRaw("
                technician_profiles.*,
                ( 6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance_km
            ", [$lat, $lng, $lat])
            ->having('distance_km', '<=', $radius)
            ->orderBy('distance_km');
        } else {
            $query->orderByDesc('average_rating');
        }

        // 6. Pagination
        $technicians = $query->paginate(12)->withQueryString();
        $categories  = Category::where('is_active', true)->get();

        return view('customer.search.index', compact('technicians', 'categories'));
    }

    public function show(int $id): View
    {
        $profile = TechnicianProfile::with(['user', 'category'])
            ->where('user_id', $id)
            ->firstOrFail();

        $works = $profile->user->technicianWorks()->get();

        return view('customer.search.show', compact('profile', 'works'));
    }
}