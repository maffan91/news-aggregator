<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        $this->applyKeywordFilter();
        $this->applyDateFilter();
        $this->applyAuthorFilter();
        $this->applyCategoryFilter();
        $this->applySourceFilter();

        return $this->builder;
    }

    private function applyKeywordFilter()
    {
        $keyword = $this->request->input('keyword');

        if ($keyword) {
            $this->builder->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                      ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }
    }

    private function applyDateFilter()
    {
        $startDate = $this->request->input('start_date');
        $endDate = $this->request->input('end_date');

        if ($startDate && $endDate) {
            $this->builder->whereBetween('created_at', [$startDate, $endDate]);
        } else if ($startDate) {
            $this->builder->where('created_at', '>=', $startDate);
        } else if ($endDate) {
            $this->builder->where('created_at', '<=', $endDate);
        }
    }

    private function applyAuthorFilter()
    {
        $authorId = $this->request->input('author_id');

        if ($authorId) {
            $this->builder->where('author_id', $authorId);
        }
    }

    private function applyCategoryFilter()
    {
        $categoryId = $this->request->input('category_id');

        if ($categoryId) {
            $this->builder->where('category_id', $categoryId);
        }
    }

    private function applySourceFilter()
    {
        $sourceId = $this->request->input('source_id');

        if ($sourceId) {
            $this->builder->where('source_id', $sourceId);
        }
    }
}
