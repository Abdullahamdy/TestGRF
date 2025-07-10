<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class NewsFilter extends BaseFilters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'title',
        'search',
        'category_id',
        'tag_ids',
        'type',
        'created_date',
        'status',
        'featured',
        'type',
        'date',
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
            return $this->builder->whereHas('translations', function ($query) use ($value) {
                $query->whereAny(['title', 'sub_title'], 'LIKE', "%$value%");
            });
        }
        return $this->builder;
    }



    protected function featured($value)
    {
        if ($value != null) {
            return $this->builder->where('is_featured', $value);
        }
        return $this->builder;
    }


    protected function slider($value)
    {
        if ($value != null) {
            return $this->builder->where('show_in_slider', $value);
        }
    }

    protected function type($value)
    {
        if ($value) {
            return $this->builder->where('type', $value);
        }
        return $this->builder;
    }

    protected function title($value)
    {
        if ($value) {
            return $this->builder->where('title', 'LIKE', "%{$value}%");
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
    protected function date($value)
    {
        if ($value) {
            return $this->builder->whereDate('created_at', '>=', $value);
        }
        return $this->builder;
    }
    protected function editorId($value)
    {
        if ($value) {
            return $this->builder->where('publisher_id', 'LIKE', "%{$value}%");
        }
        return $this->builder;
    }
    protected function categoryId($value)
    {
        if ($value) {
            return $this->builder->where('category_id', $value);
        }

        return $this->builder;
    }
    protected function tagIds($value)
    {
        return $this->builder->whereHas('tags', function ($q) use ($value) {
            $q->whereIn('tag_id', $value);
        });
    }
    protected function status($value)
    {
        if ($value != null) {
            return $this->builder->where('status', $value);
        }
        return $this->builder;
    }


    /**
     * Filter the query by countries.
     *
     * @param array $value
     * @return Builder
     */
}
