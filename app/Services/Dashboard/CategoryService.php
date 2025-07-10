<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\CategoryResource;
use App\Models\Category;
use App\Traits\HandlesTranslations;
use Exception;
use Illuminate\Support\Facades\Auth;

class CategoryService
{
    use  HandlesTranslations;
    protected $avaliabeLang;


    public function index()
    {
        $categories = Category::withCount('news')->with('user')->orderBy('created_at', 'desc')->filter()
            ->paginate(request()->has('per_page') ? request()->per_page : 10);
        return CategoryResource::collection($categories);
    }

    public function store($data)
    {
        try {
            $data['user_id'] = Auth::id();
            $this->storeWithTranslations($data, Category::class, function ($model, $data) {
                $this->handleCommonOperations($model, $data, []);
            });
            return  'success';
        } catch (Exception $e) {
            return  'error';
        }
    }

    public function update($data, $id)
    {
        $category   = Category::find($id);
        if (!$category)  return 'not_found';


        try {
            $this->updateWithTranslations($data, $category, function ($model, $data) {
                $this->handleCommonOperations($model, $data, []);
            });

            return  new CategoryResource($category);
        } catch (Exception $e) {
            return  'error';
        }
    }
    public function show(string $id)
    {
        $category   = Category::withCount('news')->find($id);
        if (!$category)  return 'not_found';


        return  new CategoryResource($category);
    }
    public function destroy(string $id)
    {
        $category   = Category::find($id);
        if (!$category)  return 'not_found';


        $category->delete();
        return  'success';
    }
}
