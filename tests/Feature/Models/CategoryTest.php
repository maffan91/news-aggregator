<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Category Model', function () {

  it('creates a new Category', function () {
    $category = Category::factory()->create();
    $this->assertModelExists($category);
  });
});