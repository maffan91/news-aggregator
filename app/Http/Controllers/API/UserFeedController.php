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
     * @OA\Get(
     *  path="/api/user/feed",
     *  tags={"UserFeed"},
     *  summary="Get News Feed for user",
     *  description="Get paginated feed of news articles",
     *  @OA\Response(
     *      response=200,
     *      description="User feed",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="title", type="string", example="Sample Article Title"),
     *              @OA\Property(property="content", type="string", example="This is the content of the article."),
     *              @OA\Property(property="author", type="string", example="John Doe"),
     *          )
     *      )
     *  ),
     *  @OA\Response(
     *      response=401,
     *      description="Unauthorized - Authentication required",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="error", type="string", example="Unauthorized")
     *      )
     *  ),
     * )
     * security={{"BearerAuth": {}}}
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
