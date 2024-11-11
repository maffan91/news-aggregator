<?php

namespace App\Interfaces;

interface ArticleParser
{
    public function parseArticle(array $data): array;
}
