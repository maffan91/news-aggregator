<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use App\Models\Author;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('User Feed Api', function () {

    describe('GET api/user/feed', function () {
        it('returns articles set by user as preferences', function () {
            //Create some categories, sources, and authors
            $categories = Category::factory()->count(3)->create();
            $sources = Source::factory()->count(3)->create();
            $authors = Author::factory()->count(3)->create();

            //Create a user
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            // Capture the IDs for setting user preferences later
            $categoryIds = $categories->pluck('id')->toArray();
            $sourceIds = $sources->pluck('id')->toArray();
            $authorIds = $authors->pluck('id')->toArray();

            UserPreference::create([
                'user_id' => $user->id,
                'category_ids' => $categoryIds,
                'source_ids' => $sourceIds,
                'author_ids' => $authorIds,
            ]);

            $articles = Article::factory()->count(5)->create([
                'category_id' => $categoryIds[0],
                'source_id' => $sourceIds[0],
                'author_id' => $authorIds[0],
            ]);

            // More articles that don't match
            Article::factory()->count(5)->create();

            $response = $this->getJson('/api/user/feed');
            $response->assertStatus(200);
            $feedArticleIds = collect($response->json('data'))->pluck('id')->toArray();

            // Check that only articles with matching categories, sources, and authors are in the feed
            foreach ($articles as $article) {
                expect(in_array($article->id, $feedArticleIds))->toBeTrue();
            }
        });

        it('returns all articles if no user preference is set', function () {

            //Create a user
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            // Default Articles
            $defaultArticles = Article::factory()->count(5)->create();

            $response = $this->getJson('/api/user/feed');
            $response->assertStatus(200);
            $feedArticleIds = collect($response->json('data'))->pluck('id')->toArray();

            // Check that only articles with matching categories, sources, and authors are in the feed
            foreach ($defaultArticles as $article) {
                expect(in_array($article->id, $feedArticleIds))->toBeTrue();
            }
        });

        it('returns a 401 error for unauthenticated requests', function () {
            $article = Article::factory()->create();

            $response = $this->getJson('/api/user/feed');
            $response->assertStatus(401);
        });
    });
});
