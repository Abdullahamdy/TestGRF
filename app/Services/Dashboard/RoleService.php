<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\RoleResource;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{

    public function index(): Collection
    {
        return Role::withCount('users')
            ->when(request()->has('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request()->has('search'), function ($query) {
                $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->when(request()->has('created_at'), function ($query) {
                $query->whereDate('created_at', '>=', request('created_at'));
            })
            ->when(request()->has('role_id'), function ($query) {
                $query->where('name', 'LIKE', '%' . request('role_id') . '%');
            })
            ->when(request()->has('language'), function ($query) {
                $query->where('language',  request('language') );
            })
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name']);
    }



    public function getRoles(): Collection
    {
        return Role::whereNotIn('name',['English Writer','Arabic Writer'])->get(['id', 'name']);
    }
    //create mode role and permissions
    public function store($request)
    {
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
                    $query->where('name', 'LIKE', "%{$item}")
                        ->orWhere('name', 'LIKE', "%{$item}_" . app()->getLocale());
                })
                ->where('guard_name', 'sanctum')
                ->where(function ($query) {
                    $query->where('language', app()->getLocale())
                        ->orWhereNull('language');
                })
                ->orderBy('for')
                ->select('display_name')
                ->get()
                ->pluck('display_name');
        }

        return $permissions;
    }

    public function show($role_id)
    {
        $role =   Role::find($role_id);
        if (!$role)
            return 'not_found';
        return new RoleResource($role);
    }
    public function assignPermissionToRole($role, $displaypermissions)
    {
        $language = auth()->user()->hasRole('admin') && request('language') ? request('language') : app()->getLocale();
        $permissions = array_map(function ($item) use ($language) {
            $all_permission = Permission::pluck('name')->toArray();
            if (in_array($item . "_" . $language, $all_permission)) {
                return  $item . "_" . $language;
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
        $role =   Role::find($role_id);
        if (!$role)
            return 'not_found';
        try {
            $role->name = $request->name;
            $role->save();
            $this->assignPermissionToRole($role, $request->permissions);
            return 'success';
        } catch (\Exception $exception) {
            dd($exception);
            errorLog(__FILE__, __LINE__, $exception->getMessage(), $exception);
            return 'server_error';
        }
    }

    public function destroy(int $role_id): string
    {
        $role =   Role::find($role_id);
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
