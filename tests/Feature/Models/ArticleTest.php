<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

uses(RefreshDatabase::class);

describe('Article Model', function () {

  it('creates a new Article', function () {
    $article = Article::factory()->create();
    echo $article->id;
    $this->assertModelExists($article);
  });

  it('checks article has title, description, and url attributes', function () {
    $article = Article::factory()->create([
      'title' => 'Sample Article',
      'description' => 'This is a description of the sample article.',
      'url' => 'https://example.com/sample-article',
    ]);

    expect($article->title)->toBe('Sample Article');
    expect($article->description)->toBe('This is a description of the sample article.');
    expect($article->url)->toBe('https://example.com/sample-article');
  });

  it('checks created_at and updated_at timestamps are set', function () {
    $article = Article::factory()->create();

    expect($article->created_at)->not()->toBeNull();
    expect($article->updated_at)->not()->toBeNull();
  });

  it('checks that articles with null description are handled', function () {
    $article = Article::factory()->create(['description' => null]);

    $this->assertDatabaseHas('articles', [
      'id' => $article->id,
      'description' => null,
    ]);
  });

  it('verifies an article belongs to a category', function () {
    $category = Category::factory()->create();
    $article = Article::factory()->for($category)->create();

    expect($article->category->id)->toBe($category->id);
  });

  it('verifies an article belongs to a source', function () {
    $source = Source::factory()->create();
    $article = Article::factory()->for($source)->create();

    expect($article->source->id)->toBe($source->id);
  });

  it('retrieves articles created today', function () {
    Article::factory()->count(3)->create(['created_at' => now()]);
    Article::factory()->count(2)->create(['created_at' => now()->subDays(1)]);

    $todayArticles = Article::whereDate('created_at', now()->toDateString())->get();

    expect($todayArticles)->toHaveCount(3);
  });

  it('retrieves articles with a specific title', function () {
    Article::factory()->create(['title' => 'Unique Article Title']);
    Article::factory()->count(2)->create(['title' => 'General Article Title']);

    $uniqueArticle = Article::where('title', 'Unique Article Title')->first();

    expect($uniqueArticle)->not()->toBeNull();
    expect($uniqueArticle->title)->toBe('Unique Article Title');
  });

  it('ensures the Article title is required', function () {
    // Attempt to create an author without a name
    $this->expectException(\Illuminate\Database\QueryException::class);
    Article::factory()->create(['title' => null]);
  });
});
