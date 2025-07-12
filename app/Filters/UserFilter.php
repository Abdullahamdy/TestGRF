<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends BaseFilters
{
    protected $filters = [
        'role_id',
        'search',
        'status',
        'official',
        'team',
        'created_at',

    ];

    protected function createdAt($value)
    {
        if ($value) {
            return $this->builder->whereDate('created_at', '=', $value);
        }
        return $this->builder;
    }


    protected function search($value)
    {
        if ($value) {
            return $this->builder->whereAny(
                ['email', 'first_name', 'last_name', 'user_name'],
                'LIKE',
                "%$value%"
            )
                ->orWhereHas('roles', function ($query) use ($value) {
                    $query->where('name', 'LIKE', "%$value%");
                });
        }
        return $this->builder;
    }

    protected function status($value)
    {
        if ($value != null) {
            return $this->builder->where('status', $value);
        }
        return $this->builder;
    }
    protected function official($value)
    {
     if ($value) {
            return $this->builder->whereHas('roles', function ($query) use ($value) {
                $query->where('name', 'Official');
            });
        }
        return $this->builder;
    }
    protected function team($value)
    {
     if ($value) {
            return $this->builder->whereHas('roles', function ($query) use ($value) {
                $query->where('name', 'Team Work');
            });
        }
        return $this->builder;
    }



    protected function roleId($value)
    {
        if ($value) {
            return $this->builder->whereHas('roles', function ($query) use ($value) {
                $query->where('name', $value);
            });
        }
    }
}
