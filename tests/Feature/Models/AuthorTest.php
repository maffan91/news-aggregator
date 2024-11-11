<?php

use App\Models\Article;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Author Model', function () {

  it('creates a new Author', function () {
    $author = Author::factory()->create();
    $this->assertModelExists($author);
  });

  it('checks that an Author can have multiple Articles', function () {
    $author = Author::factory()->create();
    $articles = Article::factory()->count(3)->create(['author_id' => $author->id]);

    // Assert that the author has 3 associated articles
    $this->assertCount(3, $author->articles);
    $this->assertEquals($articles->pluck('id')->sort()->values()->toArray(), $author->articles->pluck('id')->sort()->values()->toArray());
  });

  it('verifies that deleting an Author deletes associated Articles', function () {
    $author = Author::factory()->create();
    Article::factory()->count(2)->create(['author_id' => $author->id]);

    $author->delete();

    // Assert that the articles related to this author are deleted
    $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    $this->assertDatabaseMissing('articles', ['author_id' => $author->id]);
  });

  it('ensures the Author name is required', function () {
    // Attempt to create an author without a name
    $this->expectException(\Illuminate\Database\QueryException::class);
    Author::factory()->create(['name' => null]);
  });

  it('updates an Author\'s attributes successfully', function () {
    $author = Author::factory()->create(['name' => 'Original Name']);

    $author->update(['name' => 'Updated Name']);

    $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Updated Name']);
  });

  it('prevents duplicate Author names if they must be unique', function () {
    Author::factory()->create(['name' => 'Unique Name']);

    $this->expectException(\Illuminate\Database\QueryException::class);
    Author::factory()->create(['name' => 'Unique Name']);
  });
});
