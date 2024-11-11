<?php

namespace App\Factories;

use App\Interfaces\ArticleParser;
use App\Models\Source;
use App\Services\Parser\NewsApiParser;
use App\Services\Parser\TheGuardianParser;
use App\Services\Parser\TheNewYorkTimesParser;

class ArticleParserFactory
{
  public static function create(Source $source): ArticleParser
  {
    return match($source->name)
    {
      'NewsAPI' => new NewsApiParser(),
      'The Guardian' => new TheGuardianParser(),
      'The New York Times' => new TheNewYorkTimesParser(),
      default => throw new \Exception("No parser found for source {$source->name}"),
    };
  }
}