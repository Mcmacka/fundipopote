<?php

namespace App\Services;

use App\Models\TechnicianProfile;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * SearchService
 *
 * Handles all technician search logic.
 * The ActiveSubscriptionScope is applied automatically — only
 * subscribed technicians are returned from any query here.
 */
class SearchService
{
    /**
     * Search for available (subscribed) technicians.
     *
     * @param int|null   $categoryId  Filter by service category
     * @param float|null $latitude    Customer's latitude
     * @param float|null $longitude   Customer's longitude
     * @param float      $radiusKm    Search radius in kilometres (default 20km)
     * @param int        $perPage     Results per page
     */
    public function search(
        ?int   $categoryId = null,
        ?float $latitude   = null,
        ?float $longitude  = null,
        float  $radiusKm   = 20,
        int    $perPage    = 12
    ): LengthAwarePaginator {

        $query = TechnicianProfile::query()
            ->with(['user:id,name,phone', 'category:id,name,icon']);

        // Filter by service category
        if ($categoryId) {
            $query->inCategory($categoryId);
        }

        // Filter by proximity using the Haversine local scope
        if ($latitude && $longitude) {
            $query->nearby($latitude, $longitude, $radiusKm);
        } else {
            // Default sort: highest rating first
            $query->orderByDesc('average_rating');
        }

        return $query->paginate($perPage);
    }
}
