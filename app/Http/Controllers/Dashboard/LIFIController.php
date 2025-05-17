<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\ChangeStatusRequest;
use App\Http\Requests\Dashboard\LIFIRequest;
use App\Services\Dashboard\LIFIService;

class LIFIController extends BaseController
{
    protected $lifiService;
    public function __construct(LIFIService $lifiService)
    {
        $this->lifiService = $lifiService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->lifiService->index();
        return $this->respondWithPagination($response, '', 200);
    }


    public function store(LIFIRequest $request)
    {
        $response = $this->lifiService->create($request->all());
        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : $this->respondMessage(__('general.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->lifiService->show($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(LIFIRequest $request, string $id)
    {

        $response = $this->lifiService->update($request->all(), $id);

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
        $response = $this->lifiService->destroy($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.deleted'));
    }

    public function changeFeatured(string $id)
    {
        $response = $this->lifiService->changeFeatured($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.updated'));
    }


    public function changeStatus(ChangeStatusRequest $request, string $id)
    {
        $response = $this->lifiService->changeStatus($request, $id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.updated'));
    }




    public function get_news_status()
    {
        $response = $this->lifiService->get_news_status();
        return $this->respondData($response);
    }
}
