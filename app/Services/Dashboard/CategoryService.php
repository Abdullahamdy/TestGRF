<?php

namespace App\Services\Dashboard;

use App\Exports\CategoriesExport;
use App\Http\Resources\Dashboard\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Maatwebsite\Excel\Facades\Excel;

class CategoryService
{
    use AuthorizesRequests;
    protected $avaliabeLang;


    public function index()
    {
        // $this->authorize('viewAny', Category::class);
        $categories = Category::withCount('news')->with('user')->orderBy('created_at','desc')->filter()
            ->paginate(request()->has('per_page') ? request()->per_page : 10);
        return CategoryResource::collection($categories);
    }

    public function store($data)
    {
        // $this->authorize('create', Category::class);
        try {
            $data['language'] = isset($data['language']) ? $data['language'] : auth()->user()->language;
            $data['slug'] = $data['name'];
            $data['user_id'] =auth()->user()->id;
            Category::create($data);
            return  'success';
        } catch (Exception $e) {
            return  'error';
        }
    }

    public function update($data, $id)
    {
        $category   = Category::find($id);
        if (!$category)  return 'not_found';

        // $this->authorize('update', $category);

        try {
            $category = Category::findOrFail($id);

            $data['language'] = $category->language;
            $category->update($data);

            return  'success';
        } catch (Exception $e) {
            return  'error';
        }
    }
    public function show(string $id)
    {
        $category   = Category::withCount('news')->find($id);
        if (!$category)  return 'not_found';

        // $this->authorize('view', $category);

        return  new CategoryResource($category);
    }
    public function destroy(string $id)
    {
        $category   = Category::find($id);
        if (!$category)  return 'not_found';

        // $this->authorize('delete', $category);

        $category->delete();
        return  'success';
    }

    public function handlingLanguage($data)
    {
        return prepare_translations($data, ['name', 'description', 'slug']);
    }

    // public function export()
    // {
    //     $categories = Category::filter()->get();
    //     try {
    //         return Excel::download(new CategoriesExport($categories), 'categories.xlsx');
    //     } catch (Exception $exception) {
    //         errorLog(__FILE__, __LINE__, $exception->getMessage(), $exception);
    //         return 'server_error';
    //     }
    // }


}
