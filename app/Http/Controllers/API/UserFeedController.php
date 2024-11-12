<?php

namespace App\Http\Controllers\API;

use App\Filters\UserFeedFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class UserFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserFeedFilter $filters)
    {
        $articles = $filters->apply(Article::with(['source', 'category', 'author']))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return ArticleResource::collection($articles);
    }
}
