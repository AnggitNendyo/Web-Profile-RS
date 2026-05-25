<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use Inertia\Inertia;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Facilities/Index', [
            'facilities' => Facility::with(['facilityCategory', 'media'])->when($request->search, function ($query, $search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhereHas('facilityCategory', function($q) use ($search) {
                            $q->where('name', 'ilike', "%{$search}%");
                        })
                        ->orWhere('description', 'ilike', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString()
                ->through(function ($facility) {
                    $facility->photos = $facility->getMedia('facility_photos')->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'url' => $media->getUrl(),
                        ];
                    });
                    return $facility;
                }),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Facilities/Form', [
            'categories' => \App\Models\FacilityCategory::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_category_id' => 'required|exists:facility_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_estimation' => 'nullable|string|max:255',
            'bed_capacity' => 'nullable|integer|min:0',
            'features' => 'nullable|array',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048'
        ]);

        $validated['features'] = $validated['features'] ?? [];

        $facility = Facility::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $facility->addMedia($photo)->toMediaCollection('facility_photos');
            }
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        $facility->photos = $facility->getMedia('facility_photos')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
            ];
        });

        return Inertia::render('Admin/Facilities/Form', [
            'facility' => $facility,
            'categories' => \App\Models\FacilityCategory::orderBy('name')->get()
        ]);
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'facility_category_id' => 'required|exists:facility_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_estimation' => 'nullable|string|max:255',
            'bed_capacity' => 'nullable|integer|min:0',
            'features' => 'nullable|array',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
            'deleted_photos' => 'nullable|array'
        ]);

        $validated['features'] = $validated['features'] ?? $facility->features;

        $facility->update($validated);

        if (!empty($validated['deleted_photos'])) {
            $facility->media()->whereIn('id', $validated['deleted_photos'])->delete();
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $facility->addMedia($photo)->toMediaCollection('facility_photos');
            }
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
