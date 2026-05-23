<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with(['author', 'category'])
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'ilike', "%{$search}%")
                    ->orWhere('content', 'ilike', "%{$search}%")
                    ->orWhereHas('author', fn($q) => $q->where('name', 'ilike', "%{$search}%"));
            })
            ->orderByRaw('published_at IS NOT NULL ASC, published_at DESC')
            ->paginate(10)
            ->withQueryString();
        
        return Inertia::render('Admin/Articles/Index', [
            'articles' => $articles,
            'filters' => ['search' => $request->search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Articles/Form', [
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
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['author_id'] = $request->user()->id;

        // Auto-generate excerpt from content if not provided
        if (empty($validated['excerpt']) && !empty($validated['content'])) {
            $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 160);
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(Article $article)
    {
        return Inertia::render('Admin/Articles/Form', [
            'article' => $article->load('category'),
            'categories' => ArticleCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Article $article)
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
        ]);

        if ($request->title !== $article->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        }

        // Auto-generate excerpt from content if not provided
        if (empty($validated['excerpt']) && !empty($validated['content'])) {
            $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 160);
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
