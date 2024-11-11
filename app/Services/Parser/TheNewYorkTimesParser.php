<?php

namespace App\Services\Parser;

use App\Interfaces\ArticleParser;

class TheNewYorkTimesParser implements ArticleParser
{
    public function parseArticle(array $data): array
    {
        return [
            'title' => $data['headline']['main'] ?? 'Untitled',
            'description' => $data['lead_paragraph'] ?? 'No description available yet',
            'thumbnail' =>  $this->setThumbnail($data),
            'url' => $data['web_url'] ?? 'https://www.nytimes.com/',
            'author' => "The New York Times Author", // author is hardcoded
        ];
    }

    private function setThumbnail(array $data): string
    {
        $url = isset($data['multimedia']) && isset($data['multimedia'][0]['url']) ? ('https://www.nytimes.com/' . $data['multimedia'][0]['url']) : 'https://via.placeholder.com/150';
        return $url;
    }
}
