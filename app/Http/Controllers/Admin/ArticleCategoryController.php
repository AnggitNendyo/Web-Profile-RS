<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:article_categories,name',
        ]);

        $category = ArticleCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json($category, 201);
    }

    public function destroy(ArticleCategory $articleCategory)
    {
        $articleCategory->delete();

        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}
