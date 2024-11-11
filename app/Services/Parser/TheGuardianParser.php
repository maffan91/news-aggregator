<?php

namespace App\Services\Parser;

use App\Interfaces\ArticleParser;

class TheGuardianParser implements ArticleParser
{
    public function parseArticle(array $data): array
    {
        if (empty($data)) return [];
        return [
            'title' => $data['fields']['headline'] ?? 'Untitled',
            'description' => $data['fields']['trailText'] ?? 'No description available yet',
            'thumbnail' =>  $this->setThumbnail($data),
            'url' => $data['webUrl'] ?? 'https://www.theguardian.com/',
            'author' => "The Guardian Author", // author is hardcoded here as I couldn't find one
        ];
    }

    private function setThumbnail(array $data): string
    {
        return $data['fields']['thumbnail'] ?? 'https://via.placeholder.com/150';
    }
}
