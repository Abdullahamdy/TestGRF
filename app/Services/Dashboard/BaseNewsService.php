<?php

namespace App\Services\Dashboard;

use App\Models\News;
use App\Models\Tag;
use App\Traits\MediaTrait;
use Illuminate\Support\Str;

abstract class BaseNewsService
{
    use MediaTrait;
    const imageFolder =  'photos/news';

    protected function handleImage($file)
    {
        return $file ? $this->uploadFile($file, self::imageFolder) : null;
    }

    protected function handleTags($news, $tags)
    {
        if (!is_array($tags)) return;

        $tagIds = [];

        foreach ($tags as $tag) {
            if (is_numeric($tag)) {
                $tagIds[] = (int) $tag;
            } elseif (is_string($tag)) {
                $newTag = Tag::firstOrCreate(
                    [
                        'name' => $tag,
                        'language' => app()->getLocale(),
                    ],
                    [
                        'slug' => Str::slug($tag),
                        'description' => $tag,
                        'user_id' => auth()->id() ,
                    ]
                );

                $tagIds[] = $newTag->id;
            }
        }

        if (!empty($tagIds)) {
            $news->tags()->attach($tagIds);
        }
    }

    protected function handleFeatured($data)
    {

        $language = $data['language'] ?? auth()->user()->language;
        if (isset($data['is_featured']) && $data['is_featured'] == 1 ) {

            $featuredNewsCount = News::where('is_featured', 1)->where('language',$language)->count();

            if ($featuredNewsCount < 30) {
                $data['order_featured'] = 1;

                News::where('is_featured', 1)->where('order_featured', '>', 0)
                ->where('language',$language)
                    ->increment('order_featured');

                News::where('is_featured', 1)->where('order_featured', '>', 1)
                ->where('language',$language)
                    ->orderBy('order_featured')
                    ->update(['order_featured' => \DB::raw('order_featured + 1')]);
            } else {
                $lastFeaturedNews = News::where('is_featured', 1)
                ->where('language',$language)
                ->orderByDesc('order_featured')->first();

                if ($lastFeaturedNews) {
                    $lastFeaturedNews->update([
                        'order_featured' => 0,
                        'is_featured' => 0,
                    ]);
                }

                $data['order_featured'] = 1;

                News::where('is_featured', 1)->where('order_featured', '>', 0)
                ->where('language',$language)
                    ->increment('order_featured');
            }


            return 1;
        }
        return 0;
    }

}
