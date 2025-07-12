<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\RoleRequest;
use App\Http\Requests\Dashboard\UpdateRoleStatusRequest;
use App\Services\Dashboard\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends BaseController
{
    private $roleService;
    function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): JsonResponse
    {
        $res = $this->roleService->index();
        return $this->respondWithPagination($res);
    }

    public function getRoles(): JsonResponse
    {
        $res = $this->roleService->getRoles();
        return $this->respondData($res);
    }
    public function permissions(): JsonResponse
    {
        return $this->respondData($this->roleService->permissions());
    }

    public function store(RoleRequest $request)
    {

     $res = $this->roleService->store($request);

         return  $res == 'success'
            ? $this->respondMessage(__('general.created'))
            : $this->respondError(__('general.something_wrong'), 500);
    }
    public function show($role_id)
    {
      $res =  $this->roleService->show($role_id);
       return $res == 'not_found' ?
        $this->respondError(__('general.not_found'), 404):
        $this->respondData($res);
    }

    public function update(RoleRequest $request, int $role): JsonResponse
    {
        $res = $this->roleService->update($request, $role);
        switch ($res) {
            case "not_found":
                return $this->respondError(__('general.not_found'), 404);
                break;
            case "success":
                return $this->respondMessage(__('general.updated'));
                break;
            default:
                return $this->respondError(__('general.something_wrong'), 500);
                break;
        }
    }

    public function destroy($role_id){
        $res = $this->roleService->destroy($role_id);
        switch ($res) {
            case "not_found":
                return $this->respondError(__('general.not_found'), 404);
                break;
            case "assigned":
                return $this->respondError(__('general.assign_role'), 422);
                break;
            case "success":
                return $this->respondMessage(__('general.deleted'));
                break;
            default:
                return $this->respondError(__('general.something_wrong'), 500);
                break;
        }
    }

    public function updateStatus(UpdateRoleStatusRequest $request)
    {
        $res =  $this->roleService->updateStatus($request);
        return $res == 'not_found' ?
        $this->respondError(__('general.not_found'), 404):
        $this->respondData($res);
    }
}
