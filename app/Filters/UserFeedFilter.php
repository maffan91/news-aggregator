<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserFeedFilter
{
  protected $request;
  protected $builder;
  protected $userPreferences;

  public function __construct(Request $request)
  {
    $this->request = $request;
    $this->userPreferences = $request->user()->userPreference;
  }

  public function apply(Builder $builder)
  {
    $this->builder = $builder;

    if (isset($this->userPreferences)) {
      $this->applyAuthorFilter();
      $this->applyCategoryFilter();
      $this->applySourceFilter();
    }

    return $this->builder;
  }

  private function applyAuthorFilter()
  {
    $authorIds = $this->userPreferences->author_ids;

    if (!empty($authorIds)) {
      $this->builder->whereIn('author_id', $authorIds);
    }
  }

  private function applyCategoryFilter()
  {
    $categoryIds = $this->userPreferences->category_ids;

    if (!empty($categoryIds)) {
      $this->builder->whereIn('category_id', $categoryIds);
    }
  }

  private function applySourceFilter()
  {
    $sourceIds = $this->userPreferences->source_ids;

    if (!empty($sourceIds)) {
      $this->builder->whereIn('source_id', $sourceIds);
    }
  }
}
