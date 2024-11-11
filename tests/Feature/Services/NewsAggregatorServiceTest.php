<?php

use App\Models\Category;
use App\Models\Source;
use App\Services\NewsAggregatorService;
use App\Repositories\ArticleRepository;
use App\Services\Http\NewsApiClient;
use App\Factories\ArticleParserFactory;
use App\Interfaces\ArticleParser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('NewsAggregatorService', function () {

  it('fetches and saves articles correctly', function () {

    $category = Category::factory()->create();
    $source = Source::factory()->create([
      'name' => 'The Guardian'
    ]);

    $newsApiClient = $this->mock(NewsApiClient::class)
      ->shouldReceive('fetchArticles')
      ->andReturn(
        [

          [
            'fields' => [
              'headline' => 'Dummy Article 1',
              'trailText' => 'This is a dummy article description 1.'
            ]
          ],
          [
            'fields' => [
              'headline' => 'Dummy Article 2',
              'trailText' => 'This is a dummy article description 2.'
            ]
            ],


        ]
      )
      ->getMock();

    $articles = $newsApiClient->fetchArticles($source, $category->name);

    $parser = ArticleParserFactory::create($source);

    // Simulate parsing articles
    $parsedArticles = [];
    foreach ($articles as $articleData) {
      $parsedArticles[] = $parser->parseArticle($articleData);
    }

    // Now we can use the ArticleRepository to save articles if they don't already exist
    $articleRepository = new ArticleRepository();
    foreach ($parsedArticles as $parsedData) {
      $articleRepository->saveIfNotExists($parsedData, $source->id, $category->id);
    }

    // Assert that articles are saved in the database
    $this->assertDatabaseHas('articles', [
      'title' => 'Dummy Article 1',
      'description' => 'This is a dummy article description 1.',
      'category_id' => $category->id,
      'source_id' => $source->id
    ]);

    $this->assertDatabaseHas('articles', [
      'title' => 'Dummy Article 2',
      'description' => 'This is a dummy article description 2.',
      'category_id' => $category->id,
      'source_id' => $source->id
    ]);
  });
});
