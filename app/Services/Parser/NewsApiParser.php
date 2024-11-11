<?php

namespace App\Services\Parser;

use App\Interfaces\ArticleParser;

class NewsApiParser implements ArticleParser
{
    public function parseArticle(array $data): array
    {
        if(empty($data)) return [];

        return [
            'title' => $data['title'] ?? 'Untitled',
            'description' => $data['description'] ??  'No description available yet',
            'thumbnail' => $this->setThumbnail($data),
            'url' => $data['url'] ?? 'http://newsapi.org',
            'author' => $data['author'] ?? 'News API Author',
        ];
    }

    private function setThumbnail(array $data): string
    {
        return $data['urlToImage'] ?? 'https://via.placeholder.com/150';
    }
}
