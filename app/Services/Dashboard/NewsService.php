<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\NewsResource;
use App\Models\News;

class NewsService extends BaseNewsService
{

    public function index()
    {
        $news = News::where('type', 'normal')->with(['tags', 'category', 'sub_category', 'publisher'])->filter()
            ->orderBy('created_at', 'DESC')->paginate(request()->has('per_page') ? request()->per_page : 10);
        return NewsResource::collection($news);
    }
    public function create(array $data)
    {

        $data['language'] = $data['language'] ?? auth()->user()->language;
        $data['main_image'] = isset($data['main_image']) ? $this->handleImage($data['main_image']) : null;
        $data['meta_image'] = isset($data['meta_image']) ? $this->handleImage($data['meta_image']) : null;
        $data['publisher_id'] =  auth()?->user()?->id;
        $data['order_featured'] = $this->handleFeatured($data);
        $data['type'] = 'normal';
        $data['slug'] =  isset($data['title']) ? $data['title'] : $this->generateSlug($data['title']);
        $news = News::create($data);
        $this->handleTags($news, $data['tags'] ?? []);
        return 'success';
    }

    public function update(array $data, $id)
    {
        $news = News::where('type', 'normal')->findOrFail($id);

        $data['language'] = $news->language;
        $data['main_image'] = isset($data['main_image'])
            ? $this->handleImage($data['main_image'])
            : $news->main_image;
        $data['meta_image'] = isset($data['meta_image'])
            ? $this->handleImage($data['meta_image'])
            : $news->meta_image;
        $data['order_featured'] = $this->handleFeatured($data);
        $data['publisher_id'] =  isset($data['editor_id']) ? $data['editor_id'] : auth()?->user()?->id;
        $data['type'] = 'normal';

        $news->update($data);
        return 'success';
    }
    private function generateSlug($title)
    {
        $words = explode(' ', $title);

        if (count($words) < 3) {
            return $title;
        } else {
            $slugWords = array_slice($words, 0, 6);
            return  $slugWords;
        }
    }

    public function changeStatus($request, $id)
    {
        $news   = News::find($id);
        if (!$news)  return 'not_found';
        if ($request->status == 2) {
            $news->schudle_date = $request->schudle_date;
            $news->schudle_time = $request->schudle_time;
            $news->is_published = 0;
            $news->status = 2;
        } else if ($request->status == 1) {
            $news->schudle_date = null;
            $news->schudle_time = null;
            $news->is_published = 1;
            $news->status = 1;
        } else {
            $news->status = $request->status;
        }
        $news->save();
        return  'success';
    }

    public function getFeatured()
    {
        //featured
        $news = News::where('is_featured', 1)
            ->where(function ($query) {
                $query->where('is_published', 1)
                    ->orWhere(function ($q) {
                        $q->whereNotNull('schudle_date')
                            ->whereNotNull('schudle_time')
                            ->whereRaw("STR_TO_DATE(CONCAT(schudle_date, ' ', schudle_time), '%Y-%m-%d %H:%i:%s') <= ?", [now()]);
                    });
            })
            ->filter()
            ->orderBy('order_featured', 'asc')
            ->take(5)
            ->paginate(request()->has('per_page') ? request()->per_page : 10);
        return NewsResource::collection($news);
    }

    public function get_news_status()
    {
        return  News::StatusforDropdown();
    }


    public function changeFeatured($id)
    {
        $news   = News::find($id);
        if (!$news)  return 'not_found';

        $news->is_featured =  $this->handleFeatured($news);

        $news->save();
        return  'success';
    }

    public function show(string $id)
    {
        $news   = News::find($id);
        if (!$news)  return 'not_found';

        return  new NewsResource($news);
    }
    public function destroy(string $id)
    {
        $news = News::find($id);
        if (!$news) return 'not_found';


        if ($news->image) {
            $this->removeImage($news->getRawOriginal('image'));
        }
        if ($news->meta_image) {
            $this->removeImage($news->getRawOriginal('meta_image'));
        }

        $isFeatured = $news->is_featured == 1;
        $news->delete();

        if ($isFeatured) {
            $featuredNews = News::where('is_featured', 1)
                ->where('order_featured', '>', 0)
                ->orderBy('order_featured')
                ->get();

            foreach ($featuredNews as $index => $item) {
                $item->update(['order_featured' => $index + 1]);
            }
        }

        return 'success';
    }
}
