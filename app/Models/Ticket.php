<?php

namespace App\Models;

use App\Filters\TicketFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
   use Filterable;
   protected $filter = TicketFilter::class;
   protected $guarded = [];
}
