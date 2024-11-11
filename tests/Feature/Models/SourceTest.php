<?php

use App\Models\Article;
use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Source Model', function () {

  it('creates a new Source', function () {
    $source = Source::factory()->create();
    $this->assertModelExists($source);
  });

  it('checks that a Source can have multiple Articles', function () {
    $source = Source::factory()->create();
    $articles = Article::factory()->count(3)->create(['source_id' => $source->id]);

    // Assert that the source has 3 associated articles
    $this->assertCount(3, $source->articles);
    $this->assertEquals($articles->pluck('id')->sort()->values()->toArray(), $source->articles->pluck('id')->sort()->values()->toArray());
  });

  it('ensures the Source name is required', function () {
    // Attempt to create a source without a name
    $this->expectException(\Illuminate\Database\QueryException::class);
    Source::factory()->create(['name' => null]);
  });
});
