<?php

use App\Services\Parser\TheNewYorkTimesParser;


describe('TheNewYorkTimesParser', function () {
    it('parses article data correctly', function () {
        $data = [
            'headline' => ['main' => 'Sample Headline'],
            'lead_paragraph' => 'Sample description of the article.',
            'multimedia' => [['url' => 'images/sample.jpg']],
            'web_url' => 'https://www.nytimes.com/sample-article',
        ];

        $parser = new TheNewYorkTimesParser();
        $result = $parser->parseArticle($data);

        expect($result)->toMatchArray([
            'title' => 'Sample Headline',
            'description' => 'Sample description of the article.',
            'thumbnail' => 'https://www.nytimes.com/images/sample.jpg',
            'url' => 'https://www.nytimes.com/sample-article',
            'author' => 'The New York Times Author',
        ]);
    });

    it('uses default values when data fields are missing', function () {
        $data = []; // Empty data array

        $parser = new TheNewYorkTimesParser();
        $result = $parser->parseArticle($data);

        expect($result)->toBeArray();
        expect($result)->toBeEmpty();
    });

    it('sets thumbnail from multimedia if available', function () {
        $data = [
            'multimedia' => [['url' => 'images/sample-thumbnail.jpg']],
        ];

        $parser = new TheNewYorkTimesParser();
        $result = $parser->parseArticle($data);

        expect($result['thumbnail'])->toBe('https://www.nytimes.com/images/sample-thumbnail.jpg');
    });

    it('uses placeholder thumbnail if multimedia is missing', function () {
        $data = [
            'headline' => ['main' => 'Sample Headline'],
            'lead_paragraph' => 'Sample description of the article.',
        ];

        $parser = new TheNewYorkTimesParser();
        $result = $parser->parseArticle($data);

        expect($result['thumbnail'])->toBe('https://via.placeholder.com/150');
    });
});
