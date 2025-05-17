<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\ChangeStatusRequest;
use App\Http\Requests\Dashboard\NewsRequest;
use App\Services\Dashboard\NewsService;

class NewsController extends BaseController
{
    protected $NewsService;
    public function __construct(NewsService $NewsService)
    {
        $this->NewsService = $NewsService;
    }
    public function index()
    {
        $response = $this->NewsService->index();
        return $this->respondWithPagination($response, '', 200);
    }


    public function store(NewsRequest $request)
    {
        $response = $this->NewsService->create($request->all());
        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : $this->respondMessage(__('general.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->NewsService->show($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(NewsRequest $request, string $id)
    {

        $response = $this->NewsService->update($request->all(), $id);

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
        $response = $this->NewsService->destroy($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.deleted'));
    }

    public function changeFeatured(string $id)
    {
        $response = $this->NewsService->changeFeatured($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.updated'));
    }


    public function changeStatus(ChangeStatusRequest $request, string $id)
    {
        $response = $this->NewsService->changeStatus($request, $id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.updated'));
    }

    public function getFeatured()
    {
        $response = $this->NewsService->getFeatured();
        return $this->respondWithPagination($response, '', 200);
    }


    public function get_news_status()
    {
        $response = $this->NewsService->get_news_status();
        return $this->respondData($response);
    }
}
