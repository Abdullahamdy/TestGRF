<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\LIFIResource;
use App\Models\News;
use App\Traits\HandlesTranslations;
use Exception;

class LIFIService extends BaseNewsService
{
    use HandlesTranslations;
    public function index()
    {
        $news = News::where('type', 'lfii')->with(['tags', 'category', 'sub_category', 'publisher'])->filter()
            ->orderBy('created_at', 'DESC')->paginate(request()->has('per_page') ? request()->per_page : 10);
        return LIFIResource::collection($news);
    }
    // $data['order_featured']  = $this->handleFeatured($data);
    public function create(array $data)
    {

        try {
            $data['publisher_id'] = auth()?->user()?->id;
            $data['type'] = 'lfii';

            $news = $this->storeWithTranslations($data, News::class, function ($model, $data) {
                $this->handleNewsOperations($model, $data);
            });

            return 'success';
        } catch (Exception $e) {
            return 'error';
        }
    }

    private function handleNewsOperations($model, $data)
    {
        $imageFields = ['main_image', 'meta_image'];
        foreach ($imageFields as $field) {
            if (isset($data[$field])) {
                $model->$field = $this->handleImage($data[$field]);
            }
        }

        if (isset($data['file']) && is_file($data['file'])) {
            $model->file = $this->handleImage($data['file']);
        }

        if (isset($data['tags'])) {
            $model->save();
            $this->handleTags($model, $data['tags']);
        }
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

        try {
            $data['type'] = 'lfii';
            $data['order_featured'] = $this->handleFeatured($data);

            $news = $this->updateWithTranslations($data, $news, function ($model, $data) {
                $this->handleNewsUpdateOperations($model, $data);
            }, function ($model, $data) {
                $this->handleTags($model, $data['tags'] ?? []);
            });

            return 'success';
        } catch (Exception $e) {
            dd($e);
            return 'error';
        }
    }

    protected function handleNewsUpdateOperations(&$model, &$data)
    {
        if (isset($data['main_image']) && is_file($data['main_image'])) {
            $data['main_image'] = $this->handleImage($data['main_image']);
            $model->main_image = $data['main_image'];
        }
        if (isset($data['meta_image']) && is_file($data['meta_image'])) {
            $data['meta_image'] = $this->handleImage($data['meta_image']);
            $model->meta_image = $data['meta_image'];
        }
        if (isset($data['file']) && is_file($data['file'])) {
            $data['file'] = $this->handleImage($data['file']);
            $model->file = $data['file'];
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
