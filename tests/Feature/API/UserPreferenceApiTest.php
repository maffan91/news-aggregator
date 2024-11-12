<?php

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('UserPreferences API', function () {
    describe('GET /api/user/preferences', function () {
        it('returns user preferences successfully', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            $userPreference = $user->userPreference()->create([
                'category_ids' => json_encode([1, 2, 3]),
                'source_ids' => json_encode([4, 5]),
                'author_ids' => json_encode([6, 7]),
            ]);

            $response = $this->getJson('api/user/preferences');

            $response->assertStatus(200)
                ->assertJson($userPreference->toArray());
        });

        it('returns empty when user has no preferences', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            $response = $this->getJson('api/user/preferences');

            $response->assertStatus(200)
                ->assertExactJson([]);
        });
    });

    describe('POST /api/user/preferences', function () {

        it('saves user preferences successfully', function () {
            $user = User::factory()->create();
            $authorIds = Author::factory()->count(3)->create()->pluck('id')->toArray();
            $sourceIds = Source::factory()->count(4)->create()->pluck('id')->toArray();;
            $categoryIds = Category::factory()->count(2)->create()->pluck('id')->toArray();;
            Sanctum::actingAs($user);

            $validData = [
                'source_ids' => $sourceIds,
                'category_ids' => $categoryIds,
                'author_ids' => $authorIds,
            ];

            $response = $this->postJson('api/user/preferences', $validData);

            $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'author_ids',
                'source_ids',
                'category_ids',
            ]);
        });

        it('fails to save preferences with invalid data', function () {

            $user = User::factory()->create();
            Sanctum::actingAs($user);

            $invalidData = [
                'category_ids' => 'invalid',
                'source_ids' => 'invalid',
                'author_ids' => 'invalid',
            ];

            $response = $this->postJson('api/user/preferences', $invalidData);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['category_ids', 'source_ids', 'author_ids']);
        });
    });
});
