<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\CategoryRequest;
use App\Services\Dashboard\CategoryService;

class CategoryController extends BaseController
{

    protected $categoryService;
    public function __construct(CategoryService $categoryService){
      $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $response = $this->categoryService->index();
        return $this->respondWithPagination($response, '', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $response = $this->categoryService->store($request->all());
        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : $this->respondData(__('general.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->categoryService->show($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {

        $response = $this->categoryService->update($request->all(), $id);

        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : ($response == 'not_found'
                ? $this->respondMessage(__('general.not_found'), 404)
                : $this->respondData($response, __('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->categoryService->destroy($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.deleted'));
    }

    public function export()
    {
        return $this->categoryService->export();

    }
}
