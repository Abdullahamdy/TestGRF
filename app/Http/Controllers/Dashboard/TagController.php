<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BaseController;
use App\Http\Requests\Dashboard\TagRequest;
use App\Services\Dashboard\TagService;


class TagController extends BaseController
{

    protected $tagService;
    public function __construct(TagService $tagService){
      $this->tagService = $tagService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $response = $this->tagService->index();
        return $this->respondWithPagination($response, '', 200);
    }
    public function getNewsCommonTags()
    {

        $response = $this->tagService->getNewsCommonTags();
        return $this->respondData($response, '', 200);
    }
    public function getArticleCommonTags()
    {

        $response = $this->tagService->getArticleCommonTags();
        return $this->respondData($response, '', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $response = $this->tagService->store($request->all());
        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : $this->respondMessage(__('general.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->tagService->show($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, string $id)
    {

        $response = $this->tagService->update($request->all(), $id);

        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : ($response == 'not_found'
                ? $this->respondMessage(__('general.not_found'), 404)
                : $this->respondMessage(__('general.updated')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->tagService->destroy($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response,__('general.deleted'));
    }

    public function export()
    {
        return  $this->tagService->export();
    }
}
