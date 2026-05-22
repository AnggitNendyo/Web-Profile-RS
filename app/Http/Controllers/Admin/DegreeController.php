<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Degree;
use Inertia\Inertia;

class DegreeController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Degrees/Index', [
            'degrees' => Degree::when($request->search, function ($query, $search) {
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
            'name' => 'required|string|max:255|unique:degrees',
            'description' => 'nullable|string',
        ]);

        Degree::create($validated);

        return redirect()->route('admin.degrees.index')->with('success', 'Gelar berhasil ditambahkan.');
    }

    public function update(Request $request, Degree $degree)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:degrees,name,' . $degree->id,
            'description' => 'nullable|string',
        ]);

        $degree->update($validated);

        return redirect()->route('admin.degrees.index')->with('success', 'Gelar berhasil diperbarui.');
    }

    public function destroy(Degree $degree)
    {
        $degree->delete();

        return redirect()->route('admin.degrees.index')->with('success', 'Gelar berhasil dihapus.');
    }
}
