<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsTranslation extends Model
{
     public $fillable = ['title', 'sub_title','meta_title', 'description', 'meta_description','slug'];
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
}
