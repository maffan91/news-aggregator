<?php

use App\Services\Parser\TheGuardianParser;

describe('TheGuardianParser', function () {
    it('returns an empty array when no data is provided', function () {
        $data = []; // Empty data array

        $parser = new TheGuardianParser();
        $result = $parser->parseArticle($data);

        expect($result)->toBeArray(); // Ensure it's an array
        expect($result)->toBeEmpty(); // Assert that the array is empty
    });

    it('parses article data correctly when all fields are provided', function () {
        $data = [
            'fields' => [
                'headline' => 'Sample Guardian Headline',
                'trailText' => 'Sample description of the article.',
                'thumbnail' => 'https://example.com/sample-thumbnail.jpg',
            ],
            'webUrl' => 'https://www.theguardian.com/sample-article',
        ];

        $parser = new TheGuardianParser();
        $result = $parser->parseArticle($data);

        expect($result)->toMatchArray([
            'title' => 'Sample Guardian Headline',
            'description' => 'Sample description of the article.',
            'thumbnail' => 'https://example.com/sample-thumbnail.jpg',
            'url' => 'https://www.theguardian.com/sample-article',
            'author' => 'The Guardian Author',
        ]);
    });

    it('returns empty array when no data given', function () {
        $data = []; // Empty data array

        $parser = new TheGuardianParser();
        $result = $parser->parseArticle($data);

        expect($result)->toBeArray();
        expect($result)->toBeEmpty();
    });

    it('sets thumbnail from fields.thumbnail if available', function () {
        $data = [
            'fields' => [
                'thumbnail' => 'https://example.com/guardian-thumbnail.jpg',
            ],
        ];

        $parser = new TheGuardianParser();
        $result = $parser->parseArticle($data);

        expect($result['thumbnail'])->toBe('https://example.com/guardian-thumbnail.jpg');
    });

    it('uses placeholder thumbnail if fields.thumbnail is missing', function () {
        $data = [
            'fields' => [
                'headline' => 'Sample Guardian Headline',
                'trailText' => 'Sample description of the article.',
            ],
        ];

        $parser = new TheGuardianParser();
        $result = $parser->parseArticle($data);

        expect($result['thumbnail'])->toBe('https://via.placeholder.com/150');
    });
});
