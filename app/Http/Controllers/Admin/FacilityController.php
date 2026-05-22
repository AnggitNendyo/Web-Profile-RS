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
            'facilities' => Facility::when($request->search, function ($query, $search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('category', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Facilities/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'features' => 'nullable|array',
        ]);

        $validated['features'] = $validated['features'] ?? [];

        Facility::create($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        return Inertia::render('Admin/Facilities/Form', [
            'facility' => $facility
        ]);
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'features' => 'nullable|array',
        ]);

        $validated['features'] = $validated['features'] ?? $facility->features;

        $facility->update($validated);

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
