<?php

namespace App\Services\Dashboard;

use App\Exceptions\UnauthorizedAccessException;
use App\Http\Resources\Dashboard\RoleResource;
use App\Models\Permission;
use App\Models\User;
use App\Models\Role as RoleModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    protected $modelName = RoleModel::class;
    public function index()
    {
        // if (!auth()->user()->can('viewAny', $this->modelName)) {
        //     throw new UnauthorizedAccessException();
        // }
        $users =  RoleModel::orderBy('id','desc')->filter()->withCount('users')
            ->paginate(request('per_page', 10));
        return RoleResource::collection($users);
    }



    public function getRoles(): Collection
    {
        return RoleModel::orderBy('id', 'desc')
            ->active()
            ->get(['id', 'name']);
    }
    //create mode role and permissions
    public function store($request)
    {
        // if (!auth()->user()->can('create', $this->modelName)) {
        //     throw new UnauthorizedAccessException();
        // }
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $role =   Role::create($data);
            if ($role->wasRecentlyCreated) {
                $this->assignPermissionToRole($role, $request->permissions);
            }
            DB::commit();
            return 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            errorLog(__FILE__, __LINE__, $exception->getMessage(), $exception);
            return 'server_error';
        }
    }
    //end create role and permissions mode

    //shoW mode role and permissions
    public function permissions(): array
    {
        $permissions = [];

        foreach (adminDbTablesPermissions() as $item) {
            $permissions[$item] = Permission::query()
                ->where(function ($query) use ($item) {
                    $query->where('name', 'LIKE', "%{$item}");
                })
                ->where('guard_name', 'sanctum')
                ->get()
                ->pluck('name');
        }

        return $permissions;
    }

    public function show($role_id)
    {
        $modelName = RoleModel::find($role_id);
        // if (!auth()->user()->can('view', $modelName)) {
        //     throw new UnauthorizedAccessException();
        // }
        $role =   Role::find($role_id);
        if (!$role)
            return 'not_found';

        return new RoleResource($role);
    }
    public function assignPermissionToRole($role, $displaypermissions)
    {
        $permissions = array_map(function ($item) {
            $all_permission = Permission::pluck('name')->toArray();
            if (in_array($item, $all_permission)) {
                return  $item;
            } else {

                return $item;
            }
        }, $displaypermissions);


        $method = request()->method();
        switch ($method) {
            case "PUT":
            case "PATCH":
                $role->syncPermissions($permissions);
                break;
            case "POST":
                $role->givePermissionTo($permissions);
                break;
            default:
                return false;
                break;
        }
    }

    //end shoW role and permissions mode


    //update mode role and permissions

    public function update($request, int $role_id): string
    {
        $model =   Role::find($role_id);
        $modelName = RoleModel::find($role_id);

        // if (!auth()->user()->can('update', $modelName)) {
        //     throw new UnauthorizedAccessException();
        // }
        if (!$model)
            return 'not_found';
        try {
            $model->name = $request->name;
            $model->save();
            $this->assignPermissionToRole($model, $request->permissions);
            return 'success';
        } catch (\Exception $exception) {
            errorLog(__FILE__, __LINE__, $exception->getMessage(), $exception);
            return 'server_error';
        }
    }

    public function destroy(int $role_id): string
    {
        $modelName = RoleModel::find($role_id);

        // if (!auth()->user()->can('delete', $modelName)) {
        //     throw new UnauthorizedAccessException();
        // }
        $role = Role::query()->find($role_id);
        if (!$role)
            return 'not_found';
        if (!$role->users()->exists()) {
            try {
                $admins = User::query()
                    ->whereHas('roles', function (Builder $query) use ($role) {
                        $query->where('name', $role->name);
                    })->get();
                foreach ($admins as $admin) {
                    $admin->revokePermissionTo($role->permissions);
                }
                $role->revokePermissionTo($role->permissions);
                $role->delete();
                return 'success';
            } catch (\Exception $exception) {
                errorLog(__FILE__, __LINE__, $exception->getMessage(), $exception);
                return 'server_error';
            }
        }
        return 'assigned';
    }

    public function updateStatus($request)
    {
        $role =   Role::find($request->role_id);
        if (!$role) {
            return 'not_found';
        }
        $role->status = $request->status;
        $role->save();
        return  'success';
    }
}
