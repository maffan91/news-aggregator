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
     * @OA\Get(
     *  path="/api/articles",
     *  tags={"Article"},
     *  summary="Get articles",
     *  description="Get paginated list of articles",
     *  @OA\Parameter(
     *      name="keyword",
     *      in="path",
     *      required=false,
     *      description="Search keyword for articles",
     *      @OA\Schema(type="string", example="elections")
     * ),
     *  @OA\Response(response=200, description="Get articles"),
     *  @OA\Response(response=401, description="Unauthorized"),
     * )
     * security={{"BearerAuth": {}}}
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
     * @OA\Get(
     *  path="/api/articles/{id}",
     *  tags={"Article"},
     *  summary="Get article by ID",
     *  description="Retrieve detailed information for a specific article by providing the article ID.",
     *  operationId="getArticleById",
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="ID of the article to retrieve",
     *      @OA\Schema(type="integer", example=1)
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Article retrieved successfully",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="id", type="integer", example=1),
     *          @OA\Property(property="title", type="string", example="Sample Article Title"),
     *          @OA\Property(property="content", type="string", example="This is the content of the article."),
     *          @OA\Property(property="author", type="string", example="John Doe"),
     *          @OA\Property(property="published_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
     *      )
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Bad Request - Invalid ID supplied",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="error", type="string", example="Invalid ID")
     *      )
     *  ),
     *  @OA\Response(
     *      response=404,
     *      description="Article not found",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="error", type="string", example="Article not found")
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
     *  security={{"BearerAuth": {}}}
     * )
     */
    public function show(int $id)
    {
        $article = Article::with(['source', 'category', 'author'])->findOrFail($id);

        // Return a single article as a resource
        return new ArticleResource($article);
    }
}
