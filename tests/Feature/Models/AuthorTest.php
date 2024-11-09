<?php

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Author Model', function () {

  it('creates a new Author', function () {
    $author = Author::factory()->create();
    $this->assertModelExists($author);
  });
});
