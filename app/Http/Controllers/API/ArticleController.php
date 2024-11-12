<?php

namespace App\Http\Controllers\API;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArticleFilter $filters)
    {
        $cacheKey = 'articles_' . md5(json_encode(request()->query()));
        // Try to get articles from cache or fetch and store them if not cached
        $articles = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
            return $filters->apply(Article::with(['source', 'category', 'author']))
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        });

        return ArticleResource::collection($articles);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $article = Article::with(['source', 'category', 'author'])->findOrFail($id);

        // Return a single article as a resource
        return new ArticleResource($article);
    }
}
