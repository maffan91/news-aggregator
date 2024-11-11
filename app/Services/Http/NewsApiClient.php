<?php

namespace App\Services\Http;

use App\Models\Source;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsApiClient
{
  public function fetchArticles(Source $source, $categoryName): array
  {
    $url = str_replace(
      ['[api_key]', '[category]'],
      [$source->api_key, $categoryName],
      $source->url
    );
    try {
      $response = Http::get($url);

      if ($response->successful()) {
        $jsonData = $response->json();
        // TODO: need more better handling of responses from different sources
        $articles = $jsonData['articles'] ?? $jsonData['response']['results'] ?? $jsonData['response']['docs'];
        return $articles;
      } else {
        Log::error('Failed to fetch articles', ['status' => $response->status()]);
      }
    } catch (Exception $e) {
      Log::error('Error fetching article', ['message' => $e->getMessage()]);
    }
    return [];
  }
}
