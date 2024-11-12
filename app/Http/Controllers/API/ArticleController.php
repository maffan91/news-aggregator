<?php

namespace App\Http\Controllers\API;

use App\Filters\ArticleFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArticleFilter $filters)
    {
        $articles = $filters->apply(Article::with(['source', 'category', 'author']))
                            ->paginate(10);

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
