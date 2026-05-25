<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Facility;
use App\Http\Resources\FacilityResource;

class FacilityController extends Controller
{
    public function index()
    {
        // Load category relation
        $facilities = Facility::with('facilityCategory')->get();
        
        // Group by category name (or fallback)
        $grouped = $facilities->groupBy(function($item) {
            return $item->facilityCategory ? $item->facilityCategory->name : 'Lainnya';
        });
        
        return Inertia::render('Public/Facilities/Index', [
            // Convert to array format expected by frontend
            'facilitiesGrouped' => $grouped->map(function ($items, $key) {
                return [
                    'category' => $key,
                    'items' => FacilityResource::collection($items)->resolve()
                ];
            })->values()
        ]);
    }
}
