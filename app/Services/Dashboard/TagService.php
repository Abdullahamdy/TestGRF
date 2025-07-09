<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\TagResource;
use App\Models\Tag;
use App\Models\User;
use App\Traits\HandlesTranslations;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class TagService
{
    use AuthorizesRequests, HandlesTranslations;

    public function index()
    {
        // $this->authorize('viewAny', Tag::class);
        $tags = Tag::withCount(['news'])->with('user')
            ->orderBy('created_at', 'desc')->filter()
            ->paginate(request()->has('per_page') ? request()->per_page : 10);
        return TagResource::collection($tags);
    }
    public function getNewsCommonTags()
    {
        $topTags = Tag::select('tags.id', 'tags.name', \DB::raw('COUNT(taggables.tag_id) as usage_count'))
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->where('taggables.taggable_type', 'App\Models\News')
            ->groupBy('tags.id', 'tags.name')
            ->where('tags.language', app()->getLocale())
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get();
        return $topTags;
    }

    public function getArticleCommonTags()
    {
        $topTags = Tag::select('tags.id', 'tags.name', \DB::raw('COUNT(taggables.tag_id) as usage_count'))
            ->join('taggables', 'tags.id', '=', 'taggables.tag_id')
            ->where('taggables.taggable_type', 'App\Models\Article')
            ->where('tags.language', app()->getLocale())
            ->groupBy('tags.id', 'tags.name')
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get();
        return $topTags;
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

        // $this->authorize('view', $tag);


        return  new TagResource($tag);
    }
    public function destroy(string $id)
    {
        $tag   = Tag::find($id);
        if (!$tag)  return 'not_found';

        // $this->authorize('delete', $tag);

        $tag->delete();
        return  'success';
    }

    public function handlingLanguage($data)
    {
        return prepare_translations($data, ['name', 'description', 'slug']);
    }

    // public function export()
    // {
    //     $tags = Tag::filter()->get();
    //     try {
    //         return Excel::download(new TagsExport($tags), 'tags.xlsx');
    //     } catch (Exception $exception) {
    //         errorLog(__FILE__, __LINE__, $exception->getMessage(), $exception);
    //         return 'server_error';
    //     }
    // }
}
