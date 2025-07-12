<?php

namespace App\Models;

use App\Filters\RoleFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use Filterable;
    protected $filter = RoleFilter::class;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'model_has_roles',
            'role_id',
            'model_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
