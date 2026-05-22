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
        // Group facilities by category
        $facilities = Facility::all()->groupBy('category');
        
        return Inertia::render('Public/Facilities/Index', [
            // Convert to array format expected by frontend
            'facilitiesGrouped' => $facilities->map(function ($items, $key) {
                return [
                    'category' => $key,
                    'items' => FacilityResource::collection($items)->resolve()
                ];
            })->values()
        ]);
    }
}
