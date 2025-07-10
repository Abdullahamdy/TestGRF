<?php

namespace App\Models;

use App\Enums\NewsStatusEnum;
use App\Filters\NewsFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class News extends Model implements TranslatableContract
{
    use Filterable, Translatable;
    protected $filter = NewsFilter::class;

    public $translatedAttributes = ['title', 'sub_title', 'meta_title', 'description', 'meta_description', 'slug'];
    protected $fillable = [
        'source',
        'show_in_slider',
        'publisher_id',
        'is_featured',
        'main_image',
        'image_description',
        'is_published',
        'schudle_date',
        'schudle_time',
        'meta_image',
        'category_id',
        'status',
        'order_featured',
        'sub_category_id',
        'source',
        'editor_id',
        'type',
        'file',
        'direction',

    ];

    public function tags(): MorphToMany
    {
        return $this->MorphToMany(Tag::class, 'taggable');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function getEditorNameAttribute()
    {
        if ($this->editor != null) {
            return $this->editor?->name ?? $this->editor->full_name;
        }

        return $this->editor_id;
    }


    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public static function StatusforDropdown()
    {
        return NewsStatusEnum::toCollection();
    }


    public static function forDropdown()
    {
        $dropdown =   self::query()
            ->select(['id', 'title'])->filter();
        $perPage = request('per_page', 10);

        return $dropdown->paginate($perPage);
    }
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->orderBy('order_featured', 'asc');
    }
}
