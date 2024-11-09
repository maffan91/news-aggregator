<?php

use App\Models\Source;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Source Model', function () {

  it('creates a new Source', function () {
    $source = Source::factory()->create();
    $this->assertModelExists($source);
  });
});