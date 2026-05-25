<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $promos = Article::with(['author', 'category'])
            ->where('is_promo', true)
            ->when($request->search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'ilike', "%{$search}%")
                      ->orWhere('content', 'ilike', "%{$search}%");
                });
            })
            ->orderByRaw('published_at IS NOT NULL ASC, published_at DESC')
            ->paginate(10)
            ->withQueryString();
        
        return Inertia::render('Admin/Promos/Index', [
            'articles' => $promos,
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Promos/Form', [
            'categories' => ArticleCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'cover_image' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:article_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'price' => 'nullable|integer|min:0',
            'promo_price' => 'nullable|integer|min:0',
        ]);

        $validated['is_promo'] = true;
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['author_id'] = $request->user()->id;

        if (empty($validated['excerpt']) && !empty($validated['content'])) {
            $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 160);
        }

        Article::create($validated);

        return redirect()->route('admin.promos.index')->with('success', 'Promo/Paket berhasil dibuat.');
    }

    public function edit(Article $promo)
    {
        return Inertia::render('Admin/Promos/Form', [
            'article' => $promo->load('category'),
            'categories' => ArticleCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Article $promo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'cover_image' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:article_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'price' => 'nullable|integer|min:0',
            'promo_price' => 'nullable|integer|min:0',
        ]);

        if ($request->title !== $promo->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        }

        if (empty($validated['excerpt']) && !empty($validated['content'])) {
            $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 160);
        }

        $promo->update($validated);

        return redirect()->route('admin.promos.index')->with('success', 'Promo/Paket berhasil diperbarui.');
    }

    public function destroy(Article $promo)
    {
        $promo->delete();
        return redirect()->route('admin.promos.index')->with('success', 'Promo/Paket berhasil dihapus.');
    }
}
