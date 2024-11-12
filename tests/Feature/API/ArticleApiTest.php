<?php

use App\Models\Article;
use App\Models\User;
use Database\Seeders\ArticleSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('Article APIs', function () {
    describe('GET /api/article', function () {
        it('returns a list or articles for an authenticated user', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            $this->seed(ArticleSeeder::class);

            $response = $this->get('/api/articles');

            $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 10)
                ->has('meta')
                ->etc()
            )
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'thumbnail',
                        'created',
                        'updated',
                        'source' => [
                            'name',
                        ],
                        'author' => [
                            'name',
                        ],
                        'category' => [
                            'name',
                        ],
                    ],
                ],
            ]);
        });

        it('returns 401 for an unauthenticated request', function(){
            Article::factory()->create();

            $response = $this->getJson('/api/articles/');
            $response->assertStatus(401);
        });
    });


    describe('GET /api/article/{id}', function () {

        it('returns a single article for authenticated user', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            $article = Article::factory()->create();
            $response = $this->get('/api/articles/'.$article->id);
            $response->assertStatus(200);
        });

        it('returns a 404 error for non-existing article', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);
            $response = $this->getJson('/api/articles/' . 99999);
            $response->assertStatus(404);
            $response->assertJson(['message' => 'Not found.']);
        });

        it('returns a 401 error for unauthenticated requests', function () {
            $article = Article::factory()->create();

            $response = $this->getJson('/api/articles/' . $article->id);
            $response->assertStatus(401);
        });
    });
});

