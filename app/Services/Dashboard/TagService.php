<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\TagResource;
use App\Models\Tag;
use App\Traits\HandlesTranslations;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class TagService
{
    use AuthorizesRequests, HandlesTranslations;

    public function index()
    {
        $tags = Tag::withCount(['news'])->with('user')
            ->orderBy('created_at', 'desc')->filter()
            ->paginate(request()->has('per_page') ? request()->per_page : 10);
        return TagResource::collection($tags);
    }


    public function store($data)
    {
        try {
            $data['user_id'] = Auth::id();
            $tag = $this->storeWithTranslations($data, Tag::class, function ($model, $data) {
                $this->handleCommonOperations($model, $data, []);
            });
            return  new TagResource($tag);
        } catch (Exception $e) {
            return  'error';
        }
    }

    public function update($data, $id)
    {
        $tag   = Tag::find($id);
        if (!$tag)  return 'not_found';


        try {
            $tag = $this->updateWithTranslations($data, $tag, function ($model, $data) {
                $this->handleCommonOperations($model, $data, []);
            });

            return  new TagResource($tag);
        } catch (Exception $e) {
            return  'error';
        }
    }
    public function show(string $id)
    {
        $tag   = Tag::withCount(['news'])->find($id);
        if (!$tag)  return 'not_found';



        return  new TagResource($tag);
    }
    public function destroy(string $id)
    {
        $tag   = Tag::find($id);
        if (!$tag)  return 'not_found';


        $tag->delete();
        return  'success';
    }
}
