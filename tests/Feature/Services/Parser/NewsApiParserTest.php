<?php

use App\Services\Parser\NewsApiParser;

describe('NewsApiParser', function () {

    it('parses article data correctly', function () {
        $data = [
            'title' => 'Sample News Title',
            'description' => 'Sample description for the news article.',
            'urlToImage' => 'https://example.com/sample-image.jpg',
            'url' => 'https://newsapi.org/sample-article',
            'author' => 'Sample Author',
        ];

        $parser = new NewsApiParser();
        $result = $parser->parseArticle($data);

        expect($result)->toMatchArray([
            'title' => 'Sample News Title',
            'description' => 'Sample description for the news article.',
            'thumbnail' => 'https://example.com/sample-image.jpg',
            'url' => 'https://newsapi.org/sample-article',
            'author' => 'Sample Author',
        ]);
    });

    it('returns empty array when data is empty', function () {
        $data = []; // Empty data array

        $parser = new NewsApiParser();
        $result = $parser->parseArticle($data);

        expect($result)->toBeArray();
        expect($result)->toBeEmpty();
    });

    it('sets thumbnail from urlToImage if available', function () {
        $data = [
            'urlToImage' => 'https://example.com/news-thumbnail.jpg',
        ];

        $parser = new NewsApiParser();
        $result = $parser->parseArticle($data);

        expect($result['thumbnail'])->toBe('https://example.com/news-thumbnail.jpg');
    });

    it('uses placeholder thumbnail if urlToImage is missing', function () {
        $data = [
            'title' => 'Sample News Title',
            'description' => 'Sample description for the news article.',
        ];

        $parser = new NewsApiParser();
        $result = $parser->parseArticle($data);

        expect($result['thumbnail'])->toBe('https://via.placeholder.com/150');
    });
});
