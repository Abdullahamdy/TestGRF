<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class TicketFilter extends BaseFilters
{
   /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
          'created_date',
          'search'
    ];

    public function search($value){
        return $this->builder->whereAny(['name','phone','email'],'like', "%" . $value . "%");
    }

     protected function createdDate($value)
    {
        if ($value) {
            return $this->builder->whereDate('created_at', '=', $value);
        }
        return $this->builder;
    }
}
