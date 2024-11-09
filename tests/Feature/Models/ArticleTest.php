<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Article Model', function () {

  it('creates a new Article', function () {
    $article = Article::factory()->create();
    echo $article->id;
    $this->assertModelExists($article);
  });
});
