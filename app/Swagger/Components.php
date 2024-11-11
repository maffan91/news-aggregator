<?php

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Sample Article Title"),
 *     @OA\Property(property="content", type="string", example="This is the content of the article."),
 *     @OA\Property(property="author", type="string", example="John Doe"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Author",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sample Article Title")
 * )
 * @OA\Schema(
 *     schema="Author",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Smith")
 * )
 * )
 *
 * @OA\Response(
 *     response="NotFound",
 *     description="Not found",
 *     @OA\JsonContent(
 *         type="object",
 *         @OA\Property(property="error", type="string", example="Not found")
 *     )
 * )
 */

