<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Article;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['author', 'category'])->published()->latest('published_at')->paginate(9);
        return Inertia::render('Public/Articles/Index', [
            'articles' => ArticleResource::collection($articles)
        ]);
    }

    public function show(Article $article)
    {
        if (!$article->published_at || $article->published_at > now()) {
            abort(404);
        }
        
        $article->load(['author', 'category']);
        
        // Fetch related articles
        $relatedArticlesQuery = Article::published()
            ->where('id', '!=', $article->id)
            ->with(['author', 'category']);

        if ($article->category_id) {
            $relatedArticlesQuery->where('category_id', $article->category_id);
        }

        $relatedArticles = $relatedArticlesQuery->latest('published_at')->take(3)->get();
        
        // Fetch categories for sidebar
        $categories = \App\Models\ArticleCategory::withCount('articles')->has('articles')->orderBy('articles_count', 'desc')->take(6)->get();
        
        // Fetch latest articles for sidebar
        $latestArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->with(['category'])
            ->latest('published_at')
            ->take(4)
            ->get();
        
        return Inertia::render('Public/Articles/Show', [
            'article' => new ArticleResource($article),
            'relatedArticles' => ArticleResource::collection($relatedArticles),
            'categories' => $categories,
            'latestArticles' => ArticleResource::collection($latestArticles),
        ]);
    }
}
