<?php

namespace App\Models;

use App\Filters\TagFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, Filterable;
    protected $filter = TagFilter::class;
    protected $fillable = ['language',  'name', 'slug', 'user_id', 'description'];

    public function taggables()
    {
        return $this->morphToMany(Taggable::class, 'taggable');
    }

    public function news(): MorphToMany
    {
        return $this->morphedByMany(News::class, 'taggable');
    }


    public function setSlugAttribute($value)
    {
        if (preg_match('/\p{Arabic}/u', $value)) {
            $slug = preg_replace('/\s+/u', '-', trim($value));
            $slug = preg_replace('/[^\p{Arabic}a-zA-Z0-9\-]/u', '', $slug);
        } else {
            $slug = Str::slug($value);
        }

        $this->attributes['slug'] = $slug;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function forDropdown()
    {
        $dropdown =   self::query()
        ->where('language',app()->getLocale())
            ->select(['id', 'name'])->filter();
        $perPage = request('per_page', 10);

        return $dropdown->paginate($perPage);
    }
}
