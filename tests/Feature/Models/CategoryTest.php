<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Category Model', function () {

  it('creates a new Category', function () {
    $category = Category::factory()->create();
    $this->assertModelExists($category);
  });

  it('checks that a Category can have multiple Articles', function () {
    $category = Category::factory()->create();
    $articles = Article::factory()->count(3)->create(['category_id' => $category->id]);
    // Assert that the category has 3 associated articles
    $this->assertCount(3, $category->articles);
    $this->assertEquals($articles->pluck('id')->sort()->values()->toArray(), $category->articles->pluck('id')->sort()->values()->toArray());
  });

  it('ensures the Category name is required', function () {
    // Attempt to create a category without a name
    $this->expectException(\Illuminate\Database\QueryException::class);
    Category::factory()->create(['name' => null]);
  });

  it('updates a Category\'s attributes successfully', function () {
    $category = Category::factory()->create(['name' => 'Original Name']);

    $category->update(['name' => 'Updated Name']);

    $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Updated Name']);
  });

  it('prevents duplicate Category names if they must be unique', function () {
    Category::factory()->create(['name' => 'Unique Name']);

    $this->expectException(\Illuminate\Database\QueryException::class);
    Category::factory()->create(['name' => 'Unique Name']);
  });
});
