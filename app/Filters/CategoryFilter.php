<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends BaseFilters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'name',
        'search',
        'created_date',
        'parent_id',
        'main_classification_id',
        'type',
    ];

    /**
     * Filter the query by a given search.
     *
     * @param  string|int  $value
     * @return Builder
     */
   protected function search($value)
    {
        if ($value) {
            return $this->builder->whereHas('translations', function (Builder $query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%");
            });
        }

        return $this->builder;
    }

    protected function type($value)
    {
        if ($value) {
            return $this->builder->where('type', $value);
        }
    }
    protected function mainClassificationId($value)
    {
        if ($value) {
            return $this->builder->where('parent_id', $value);
        }
    }
    protected function parentId($value)
    {
        if ($value) {
            return $this->builder->where('parent_id', $value);
        }
    }



    protected function name($value)
    {
        if ($value) {
            return $this->builder->where('name', 'LIKE', "%{$value}%");
        }
        return $this->builder;
    }

    protected function createdDate($value)
    {
        if ($value) {
            return $this->builder->whereDate('created_at', '=', $value);
        }
        return $this->builder;
    }
}
