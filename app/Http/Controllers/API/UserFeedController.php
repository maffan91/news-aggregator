<?php

namespace App\Http\Controllers\API;

use App\Filters\UserFeedFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserFeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserFeedFilter $filters)
    {
         $cacheKey = $request->user()->id . '_feed_' . md5(json_encode(request()->query()));
        // Try to get articles from cache or fetch and store them if not cached
        $articles = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
            return $filters->apply(Article::with(['source', 'category', 'author']))
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        });

        return ArticleResource::collection($articles);
    }
}
