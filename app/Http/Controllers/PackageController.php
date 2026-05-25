<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Inertia\Inertia;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Article::with(['category'])
            ->where('is_promo', true)
            ->published()
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'ilike', "%{$search}%")
                    ->orWhere('content', 'ilike', "%{$search}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();
            
        return Inertia::render('Public/Packages/Index', [
            'packages' => $packages,
            'filters' => ['search' => $request->search],
        ]);
    }

    public function show(Article $package)
    {
        if (!$package->is_promo) {
            abort(404);
        }

        return Inertia::render('Public/Packages/Show', [
            'package' => $package->load('category'),
            'relatedPackages' => Article::where('is_promo', true)
                ->published()
                ->where('id', '!=', $package->id)
                ->inRandomOrder()
                ->take(3)
                ->get(),
        ]);
    }
}
