<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FacilityCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = FacilityCategory::withCount('facilities')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/FacilityCategories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:facility_categories,name',
        ]);

        FacilityCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', 'Kategori Fasilitas berhasil ditambahkan.');
    }

    public function update(Request $request, FacilityCategory $facilityCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:facility_categories,name,' . $facilityCategory->id,
        ]);

        $facilityCategory->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', 'Kategori Fasilitas berhasil diperbarui.');
    }

    public function destroy(FacilityCategory $facilityCategory)
    {
        if ($facilityCategory->facilities()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh fasilitas.');
        }

        $facilityCategory->delete();

        return back()->with('success', 'Kategori Fasilitas berhasil dihapus.');
    }
}
