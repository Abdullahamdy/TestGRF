<?php

namespace App\Models;

use App\Filters\TagFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Tag extends Model implements TranslatableContract
{
    use HasFactory, Filterable, Translatable;
    protected $filter = TagFilter::class;
    protected $fillable = [ 'user_id'];
     public $translatedAttributes = ['name', 'description', 'slug'];

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
            ->select(['id', 'name'])->filter();
        $perPage = request('per_page', 10);

        return $dropdown->paginate($perPage);
    }
}
