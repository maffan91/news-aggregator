<?php

use App\Models\Source;
use App\Services\Http\NewsApiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

describe('NewsApiClient', function () {


  it('fetches articles successfully when the API request is successful', function () {
    $source = new Source([
      'api_key' => 'mock-api-key',
      'url' => 'https://api.example.com/articles?apiKey=[api_key]&category=[category]',
    ]);
    $categoryName = 'technology';

    $mockResponse = [
      'articles' => [
        ['title' => 'Sample Article 1', 'description' => 'Sample description 1'],
        ['title' => 'Sample Article 2', 'description' => 'Sample description 2'],
      ],
    ];

    Http::fake([
      'https://api.example.com/articles*' => Http::response($mockResponse, 200),
    ]);

    $client = new NewsApiClient();
    $articles = $client->fetchArticles($source, $categoryName);

    expect($articles)->toHaveCount(2);
    expect($articles[0]['title'])->toBe('Sample Article 1');
    expect($articles[1]['description'])->toBe('Sample description 2');
  });

  it('returns an empty array and logs an error if the API request fails', function () {
    $source = new Source([
      'api_key' => 'mock-api-key',
      'url' => 'https://api.example.com/articles?apiKey=[api_key]&category=[category]',
    ]);
    $categoryName = 'technology';

    Http::fake([
      'https://api.example.com/articles*' => Http::response([], 500), // Simulate a failed request
    ]);

    Log::shouldReceive('error')
      ->once()
      ->with('Failed to fetch articles', ['status' => 500]);

    $client = new NewsApiClient();
    $articles = $client->fetchArticles($source, $categoryName);

    expect($articles)->toBeEmpty();
  });

  it('logs an exception and returns an empty array if an error occurs during the API request', function () {
    $source = new Source([
      'api_key' => 'mock-api-key',
      'url' => 'https://api.example.com/articles?apiKey=[api_key]&category=[category]',
    ]);
    $categoryName = 'technology';

    Http::fake([
      'https://api.example.com/articles*' => Http::response([], 200), // Simulate an empty successful response
    ]);

    Log::shouldReceive('error')
      ->once()
      ->with('Error fetching article', ['message' => 'Fake exception message']);

    $client = new NewsApiClient();

    // Simulate an exception during the API request
    Http::shouldReceive('get')->andThrow(new Exception('Fake exception message'));

    $articles = $client->fetchArticles($source, $categoryName);

    expect($articles)->toBeEmpty();
  });
});
