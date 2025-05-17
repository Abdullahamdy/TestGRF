<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UserRequest;
use App\Services\Dashboard\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{


    protected  $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->userService->index();
        return $this->respondWithPagination($response, '', 200);
    }
    // public function getWriters()
    // {
    //     $response = $this->userService->getWriters();
    //     return $this->respondWithPagination($response, '', 200);
    // }
    // public function editorialBoard()
    // {

    //     $response = $this->userService->editorialBoard();
    //     return $this->respondWithPagination($response, '', 200);
    // }
    // public function geteditorialBoard()
    // {

    //     $response = $this->userService->geteditorialBoard();
    //     return $this->respondWithPagination($response, '', 200);
    // }
    public function usernameSuggestions(Request $request)
    {

        $response = $this->userService->usernameSuggestions($request->user_name);
        return $this->respondData($response, '', 200);
    }
    public function userPermissions(Request $request)
    {

        $response = $this->userService->userPermissions($request->user_name);
        return $this->respondData($response, '', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {

        $response = $this->userService->store($request->validated());
        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : $this->respondData($response, __('general.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->userService->show($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response);
    }
    public function getProfile()
    {
        $response = $this->userService->getProfile();
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response);
    }
    public function getOfficials()
    {
        $response = $this->userService->getOfficials();
        return $this->respondWithPagination($response, '', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {

        $response = $this->userService->update($request, $id);

        return $response == 'error'
            ? $this->respondError(__('general.something_wrong'))
            : ($response == 'not_found'
                ? $this->respondMessage(__('general.not_found'), 404)
                : $this->respondData($response, __('general.updated')));
    }

    public function changeRole(Request $request)
    {

        $response = $this->userService->changeRole($request);

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

        $response = $this->userService->destroy($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.deleted'));
    }

    // public function export()
    // {
    //     return  $this->userService->export();
    // }

    public function toggleStatus(string $id)
    {
        $response = $this->userService->toggleStatus($id);
        return $response == 'not_found'
            ? $this->respondMessage(__('general.not_found'), 404)
            : $this->respondData($response, __('general.updated'));
    }

}
