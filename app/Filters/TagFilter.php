<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class TagFilter extends BaseFilters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'search',
        'name',
        'created_date',
        'type',
        'common_tag',
    ];

    protected function search($value)
    {
        if ($value) {
            return $this->builder->whereHas('translations', function (Builder $query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%");
            });
        }

        return $this->builder;
    }

    protected function commonTag($value)
    {
        if ($value) {
            return $this->builder->withCount(['news'])
                ->having('news_count', '>', 2)
                ->orderByDesc('news_count');
        }
        return $this->builder;
    }
    protected function type($value)
    {
        if ($value) {
            return $this->builder->where('type', $value);
        }
        return $this->builder;
    }

    /**
     * Filter the query by a given search.
     *
     * @param  string|int  $value
     * @return Builder
     */
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
