<?php

namespace App\Models;

use App\Filters\CategoryFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, Filterable;
    protected $filter = CategoryFilter::class;
    protected $fillable = ['language', 'parent_id',  'name', 'slug','user_id', 'description', 'show_in_dashboard'];

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
    public function news()
    {
        return $this->hasMany(News::class);
    }
    public static function forDropdown()
    {
        $dropdown =   self::query()
        ->where('language',app()->getLocale())
        ->where('show_in_dashboard',true)
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

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }



}
