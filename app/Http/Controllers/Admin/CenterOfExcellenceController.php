<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CenterOfExcellence;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CenterOfExcellenceController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Coes/Index', [
            'coes' => CenterOfExcellence::when($request->search, function ($query, $search) {
                    $query->where('title', 'ilike', "%{$search}%")
                        ->orWhere('short_description', 'ilike', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Coes/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'full_content' => 'required|string',
            'technology_used' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['technology_used'] = $validated['technology_used'] ?? [];

        CenterOfExcellence::create($validated);

        return redirect()->route('admin.coes.index')->with('success', 'Layanan unggulan berhasil ditambahkan.');
    }

    public function edit(CenterOfExcellence $coe)
    {
        return Inertia::render('Admin/Coes/Form', [
            'coe' => $coe
        ]);
    }

    public function update(Request $request, CenterOfExcellence $coe)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'full_content' => 'required|string',
            'technology_used' => 'nullable|array',
        ]);

        if ($request->title !== $coe->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['technology_used'] = $validated['technology_used'] ?? $coe->technology_used;

        $coe->update($validated);

        return redirect()->route('admin.coes.index')->with('success', 'Layanan unggulan berhasil diperbarui.');
    }

    public function destroy(CenterOfExcellence $coe)
    {
        $coe->delete();
        return redirect()->route('admin.coes.index')->with('success', 'Layanan unggulan berhasil dihapus.');
    }
}
