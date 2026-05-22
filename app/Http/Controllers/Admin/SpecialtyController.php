<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Specialty;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SpecialtyController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Specialties/Index', [
            'specialties' => Specialty::withCount('doctors')
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        
        Specialty::create($validated);
        
        return back()->with('success', 'Spesialisasi berhasil ditambahkan.');
    }

    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        if ($request->name !== $specialty->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $specialty->update($validated);
        
        return back()->with('success', 'Spesialisasi berhasil diperbarui.');
    }

    public function destroy(Specialty $specialty)
    {
        if ($specialty->doctors()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus spesialisasi karena masih memiliki dokter yang terdaftar.');
        }
        
        $specialty->delete();
        
        return back()->with('success', 'Spesialisasi berhasil dihapus.');
    }
}
