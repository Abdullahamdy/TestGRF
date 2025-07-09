<?php

namespace App\Models;

use App\Filters\CategoryFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    use HasFactory, Filterable, Translatable;
    protected $filter = CategoryFilter::class;
    protected $fillable = ['parent_id', 'user_id'];
    public $translatedAttributes = ['name', 'description', 'slug'];

    public function news()
    {
        return $this->hasMany(News::class);
    }
    public static function forDropdown()
    {
        $dropdown =   self::query()
            ->select(['id', 'name'])->whereNull('parent_id')->filter();
        $perPage = request('per_page', 10);

        return $dropdown->paginate($perPage);
    }
    public static function SubForDropdown()
    {
        $dropdown =   self::query()
            ->select(['id', 'name'])->whereNotNull('parent_id')->filter();
        $perPage = request('per_page', 10);

        return $dropdown->paginate($perPage);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }
}
