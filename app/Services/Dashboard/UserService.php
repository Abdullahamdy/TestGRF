<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\UserResource;
use App\Models\User;
use App\Traits\HandlesTranslations;
use App\Traits\MediaTrait;
use Exception;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserService
{
    const imageFolder =  'photos/users';
    use MediaTrait, HandlesTranslations;


    public function index()
    {
        $users = User::orderBy('created_at', 'desc')
            ->filter()->paginate(request()->has('per_page') ? request()->per_page : 10);
        return UserResource::collection($users);
    }

    public function store($data)
    {
        try {
            $user = $this->storeWithTranslations($data, User::class, function ($model, $data) {
                $this->handleCommonOperations($model, $data, [
                    'image' => ['field' => 'image'],
                    'role' => true
                ]);
            });

            return new UserResource($user);
        } catch (Exception $e) {
            return 'error';
        }
    }


    public function update($data, $id)
    {
        $user = User::find($id);

        try {
            $data = $data->all();
            $user = $this->updateWithTranslations($data, $user, function ($model, $data) {
                if (isset($data['image']) && is_file($data['image'])) {
                    if ($model->image) {
                        $this->removeImage($model->getRawOriginal('image'));
                    }
                }

                $this->handleCommonOperations($model, $data, [
                    'image' => ['field' => 'image'],
                    'role' => true
                ]);
            });

            return new UserResource($user);
        } catch (Exception $e) {
            return 'error';
        }
    }

    public function show(string $id)
    {
        $user   = User::find($id);
        if (!$user)  return 'not_found';

        return  new UserResource($user);
    }

    public function changeRole($request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->hasAnyRole(Role::all())) {
            $user->syncRoles([$request->role]);
        } else {
            $user->assignRole($request->role);
        }

        return 'success';
    }


    public function getProfile()
    {
        $user   = User::find(auth()?->user()?->id);
        if (!$user)  return 'not_found';

        return  new UserResource($user);
    }

    public function destroy(string $id)
    {
        $user   =  User::find($id);
        if (!$user)  return 'not_found';

        // if (!isset($data['wirters'])) {
        //     $this->authorize('update', $user);
        // } else {
        //     $writer   = MaaalWriter::find($id);
        //     $this->authorize('create', $writer);
        // }
        if ($user->image) {
            $this->removeImage($user->getRawOriginal('image'));
        }


        $user->delete();
        return  'success';
    }
    public function handleImage($file)
    {
        return $file ? $this->uploadFile($file, self::imageFolder) : null;
    }



    // public function export()
    // {
    //     $users = User::where('is_capital', 0)->where('type', 0)->with('roles')->filter()->get();
    //     try {
    //         return Excel::download(new UsersExport($users), 'usersExport.xlsx');
    //     } catch (Exception $exception) {
    //         errorLog(__FILE__, __LINE__, $exception->getMessage(), $exception);
    //         return 'server_error';
    //     }
    // }

    public function getOfficials()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'officials');
        })->filter()->paginate(request()->has('per_page') ? request()->per_page : 10);
        return UserResource::collection($users);
    }

    public function toggleStatus(string $id)
    {
        $user = User::find($id);
        if (!$user) return 'not_found';

        // if (!isset($data['wirters'])) {
        //     $this->authorize('update', $user);
        // } else {
        //     $writer   = MaaalWriter::find($id);
        //     $this->authorize('create', $writer);
        // }
        $user->status = (bool)!$user->status;
        $user->save();

        return 'success';
    }

    public function usernameSuggestions($name)
    {
        $baseUsername = Str::slug($name, '_');
        $suggestions = [];

        if (!DB::table('users')->where('user_name', $baseUsername)->exists()) {
            $suggestions[] = $baseUsername;
        }

        $counter = 1;
        while (count($suggestions) < 3) {
            $newUsername = $baseUsername . '_' . $counter;

            if (!DB::table('users')->where('user_name', $newUsername)->exists()) {
                $suggestions[] = $newUsername;
            }

            $counter++;
        }

        return $suggestions;
    }
    public function userPermissions()
    {
        $user = auth()->user();
        return  $user->getAllPermissions()->pluck('display_name')->toArray();
    }
}
