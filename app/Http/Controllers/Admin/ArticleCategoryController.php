<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArticleCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ArticleCategory::withCount('articles')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/ArticleCategories/Index', [
            'categories' => $categories,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:article_categories,name',
        ]);

        $category = ArticleCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        if ($request->wantsJson()) {
            return response()->json($category, 201);
        }

        return back()->with('success', 'Kategori Artikel berhasil ditambahkan.');
    }

    public function update(Request $request, ArticleCategory $articleCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:article_categories,name,' . $articleCategory->id,
        ]);

        $articleCategory->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', 'Kategori Artikel berhasil diperbarui.');
    }

    public function destroy(Request $request, ArticleCategory $articleCategory)
    {
        if ($articleCategory->articles()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh artikel.');
        }

        $articleCategory->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kategori berhasil dihapus.']);
        }

        return back()->with('success', 'Kategori Artikel berhasil dihapus.');
    }
}
