<?php

use App\Models\Article;
use Database\Seeders\ArticleSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Article APIs', function () {
    describe('/api/article', function () {
        it('returns a list or articles', function () {
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
    });


    describe('/api/article/{id}', function () {
        
        it('returns a single article', function () {
            $article = Article::factory()->create();
            $response = $this->get('/api/articles/'.$article->id);
            $response->assertStatus(200);
        });
    });
});

