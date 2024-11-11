<?php

namespace App\Services;

use App\Factories\ArticleParserFactory;
use App\Models\Category;
use App\Models\Source;
use App\Services\Http\NewsApiClient;
use App\Repositories\ArticleRepository;

class NewsAggregatorService
{
    public function __construct(
        protected NewsApiClient $newsApiClient,
        protected ArticleRepository $articleRepository
    ) {}

    public function fetch(Category $category)
    {
        $sources = Source::enabled()->get();
        foreach ($sources as $source) {
            $articles = $this->newsApiClient->fetchArticles($source, $category->name);
            $parser = ArticleParserFactory::create($source);

            foreach ($articles as $articleData) {
                $parsedData = $parser->parseArticle($articleData);
                if (!empty($parsedData)) {
                    $this->articleRepository->saveIfNotExists($parsedData, $source->id, $category->id);
                }
            }
        }
    }
}
