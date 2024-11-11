<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Author;

class ArticleRepository
{
    public function saveIfNotExists(array $parsedData, int $sourceId, int $categoryId): void
    {
        Article::firstOrCreate(
            ['title' => $parsedData['title']],
            [
                'description' => $parsedData['description'],
                'thumbnail' => $parsedData['thumbnail'],
                'url' => $parsedData['url'],
                'source_id' => $sourceId,
                'category_id' => $categoryId,
                'author_id' => Author::firstOrCreate(['name' => $parsedData['author']])->id
            ]
        );
    }
}
