<?php

namespace App\Services\Dashboard;

use App\Models\News;
use App\Models\Tag;
use App\Traits\MediaTrait;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

abstract class BaseNewsService
{
    use MediaTrait;
    const imageFolder =  'photos/news';

    protected function handleImage($file)
    {
        if ($file instanceof UploadedFile) {
            return $this->uploadFile($file, self::imageFolder);
        }

        return null;
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
                    ],
                    [
                        'slug' => Str::slug($tag),
                        'description' => $tag,
                        'user_id' => auth()->id(),
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

        if (isset($data['is_featured']) && $data['is_featured'] == 1) {

            $featuredNewsCount = News::where('is_featured', 1)->count();

            if ($featuredNewsCount < 30) {

                News::where('is_featured', 1)->where('order_featured', '>', 0)
                    ->increment('order_featured');
            } else {
                $lastFeaturedNews = News::where('is_featured', 1)
                    ->orderByDesc('order_featured')->first();

                if ($lastFeaturedNews) {
                    $lastFeaturedNews->update([
                        'order_featured' => 0,
                        'is_featured' => 0,
                    ]);
                }


                News::where('is_featured', 1)->where('order_featured', '>', 0)
                    ->increment('order_featured');
            }


            return 1;
        }
        return 0;
    }

    protected function toogleFeatured($data)
    {

        if (isset($data['is_featured']) && $data['is_featured'] == 1) {

            $featuredNewsCount = News::where('is_featured', 1)->count();

            if ($featuredNewsCount < 30) {

                News::increment('order_featured');
            } else {
                $lastFeaturedNews = News::where('is_featured', 1)
                    ->orderByDesc('order_featured')->first();

                if ($lastFeaturedNews) {
                    $lastFeaturedNews->update([
                        'order_featured' => 0,
                        'is_featured' => 0,
                    ]);
                }


                News::where('is_featured', 1)->where('order_featured', '>', 0)
                    ->increment('order_featured');
            }


            return 1;
        }
        return 0;
    }
}
