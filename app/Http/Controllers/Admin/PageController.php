<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Page;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/Pages/Index', [
            'pages' => Page::when($request->search, function ($query, $search) {
                    $query->where('title', 'ilike', "%{$search}%")
                        ->orWhere('slug', 'ilike', "%{$search}%");
                })
                ->paginate(10)
                ->withQueryString(),
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Pages/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->input('is_active', true);
        
        Page::create($validated);
        
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit(Page $page)
    {
        return Inertia::render('Admin/Pages/Form', [
            'page' => $page
        ]);
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);
        
        if ($request->title !== $page->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['is_active'] = $request->input('is_active', true);
        
        $page->update($validated);
        
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        
        return back()->with('success', 'Halaman berhasil dihapus.');
    }
}
