<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\CenterOfExcellence;
use App\Http\Resources\CenterOfExcellenceResource;

class CenterOfExcellenceController extends Controller
{
    public function index()
    {
        $coes = CenterOfExcellence::all();
        return Inertia::render('Public/CentersOfExcellence/Index', [
            'centersOfExcellence' => CenterOfExcellenceResource::collection($coes)
        ]);
    }

    public function show(CenterOfExcellence $centerOfExcellence)
    {
        $otherCoes = CenterOfExcellence::where('id', '!=', $centerOfExcellence->id)
            ->latest()
            ->take(4)
            ->get();

        return Inertia::render('Public/CentersOfExcellence/Show', [
            'centerOfExcellence' => new CenterOfExcellenceResource($centerOfExcellence),
            'otherCoes' => CenterOfExcellenceResource::collection($otherCoes)
        ]);
    }
}
