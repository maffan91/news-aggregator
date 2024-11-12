<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceRequest;
use App\Models\User;
use App\Repositories\UserPreferenceRepository;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user/preferences",
     *     tags={"User Preferences"},
     *     summary="Get user preferences",
     *     description="Retrieve the preferences of the authenticated user",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User preferences retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
     *             @OA\Property(property="source_ids", type="array", @OA\Items(type="integer"), example={5, 7}),
     *             @OA\Property(property="author_ids", type="array", @OA\Items(type="integer"), example={10, 15, 20})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication required",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $userPreference = $request->user()->userPreference ?? [];
        return response()->json($userPreference);
    }

    /**
     * @OA\Post(
     *     path="/api/user/preferences",
     *     tags={"User Preferences"},
     *     summary="Store user preferences",
     *     description="Store the preferences for the authenticated user",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
     *             @OA\Property(property="source_ids", type="array", @OA\Items(type="integer"), example={5, 7}),
     *             @OA\Property(property="author_ids", type="array", @OA\Items(type="integer"), example={10, 15, 20})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User preferences stored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="category_ids", type="array", @OA\Items(type="integer"), example={1, 2, 3}),
     *             @OA\Property(property="source_ids", type="array", @OA\Items(type="integer"), example={5, 7}),
     *             @OA\Property(property="author_ids", type="array", @OA\Items(type="integer"), example={10, 15, 20})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error - Failed to save preference",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Failed to save preference")
     *         )
     *     )
     * )
     */
    public function store(PreferenceRequest $request, UserPreferenceRepository $userPreferenceRepository)
    {
        $validData = $request->validated();
        $userPreference = $userPreferenceRepository->save($request->user()->id, $validData);
        if ($userPreference) {
            return response()->json($userPreference);
        }
        return response()->json(['message' => 'Failed to save preference'], 500);
    }
}
