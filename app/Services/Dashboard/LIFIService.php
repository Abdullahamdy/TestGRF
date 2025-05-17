<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\LIFIResource;
use App\Models\News;


class LIFIService extends BaseNewsService
{
    public function index()
    {
        $news = News::where('type','lifi')->with(['tags', 'category', 'sub_category', 'publisher'])->filter()
            ->orderBy('created_at', 'DESC')->paginate(request()->has('per_page') ? request()->per_page : 10);
        return LIFIResource::collection($news);
    }
    public function create(array $data)
    {
        $data['language']  = $data['language'] ?? auth()->user()->language;
        $data['main_image']  = isset($data['main_image']) ? $this->handleImage($data['main_image']) : null;
        $data['meta_image']  = isset($data['meta_image']) ? $this->handleImage($data['meta_image']) : null;
        $data['publisher_id']  = auth()?->user()?->id;
        $data['file'] = isset($data['file']) && is_file($data['file']) ? $this->handleImage($data['file']) : null;
        $data['order_featured']  = $this->handleFeatured($data);
        $data['type'] = 'special';
        $data['slug'] =  isset($data['title']) ? $data['title'] : $this->generateSlug($data['title']);

        $news = News::create($data);
        $this->handleTags($news, $data['tags'] ?? []);
        return 'success';
    }
    public function show(string $id)
    {
        $news   = News::find($id);
        if (!$news)  return 'not_found';

        return  new LIFIResource($news);
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
    public function update(array $data, $id)
    {
        $news = News::findOrFail($id);

        $newsData = [
            'language' => $news->language,
            'main_image' => isset($data['main_image'])
                ? $this->handleImage($data['main_image'])
                : $news->main_image,
            'meta_image' => isset($data['meta_image'])
                ? $this->handleImage($data['meta_image'])
                : $news->meta_image,
            'file' => isset($data['file']) && is_file($data['file'])
                ? $this->handleImage($data['file'])
                : $news->file,
            'order_featured' => $this->handleFeatured($data),
            'type' => 'special'
        ];

        $news->update(array_merge($data, $newsData));

        $this->handleTags($news, $data['tags'] ?? []);

        return 'success';
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
