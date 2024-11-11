<?php

use App\Factories\ArticleParserFactory;
use App\Models\Source;
use App\Services\Parser\NewsApiParser;
use App\Services\Parser\TheGuardianParser;
use App\Services\Parser\TheNewYorkTimesParser;
use App\Interfaces\ArticleParser;

describe('ArticleParser Factory', function () {
  it('returns NewsApiParser when source name is NewsAPI', function () {
    $source = new Source(['name' => 'NewsAPI']);
    $parser = ArticleParserFactory::create($source);
    expect($parser)->toBeInstanceOf(NewsApiParser::class);
    expect($parser)->toBeInstanceOf(ArticleParser::class);
  });

  it('returns TheGuardianParser when source name is The Guardian', function () {
    $source = new Source(['name' => 'The Guardian']);
    $parser = ArticleParserFactory::create($source);
    expect($parser)->toBeInstanceOf(TheGuardianParser::class);
    expect($parser)->toBeInstanceOf(ArticleParser::class);
  });

  it('returns TheNewYorkTimesParser when source name is The New York Times', function () {
    $source = new Source(['name' => 'The New York Times']);
    $parser = ArticleParserFactory::create($source);
    expect($parser)->toBeInstanceOf(TheNewYorkTimesParser::class);
    expect($parser)->toBeInstanceOf(ArticleParser::class);
  });

  it('throws an exception for an unknown source name', function () {
    $source = new Source(['name' => 'Unknown Source']);
    ArticleParserFactory::create($source);
  })->throws(Exception::class, 'No parser found for source Unknown Source');
});
