<?php

namespace App\Services\Dashboard;

use App\Http\Resources\Dashboard\UserResource;
use App\Mail\UserWelcomeMail;
use App\Models\User;
use App\Traits\MediaTrait;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserService
{
     const imageFolder =  'photos/users';
    use MediaTrait;


    public function index()
    {


        $users = User::orderBy('created_at', 'desc')
            ->filter()->paginate(request()->has('per_page') ? request()->per_page : 10);
        return UserResource::collection($users);
    }

    // public function editorialBoard()
    // {

    //     $users =    $users = User::whereHas('roles', function ($query) {
    //         $query->where('name', 'Editorial Board');
    //     })->filter()->paginate(request()->has('per_page') ? request()->per_page : 10);
    //     return EditorialBoardResource::collection($users);
    // }
    // public function geteditorialBoard()
    // {
    //     $this->authorize('viewAny', User::class);

    //     $users =    $users = User::whereHas('roles', function ($query) {
    //     $query->where('name', 'Editorial Board');
    //     })->select('id', 'first_name', 'last_name')
    //     ->filter()->paginate(request()->has('per_page') ? request()->per_page : 10);
    //     return $users;
    // }
    public function store($data)
    {
        // if (!isset($data['wirters'])) {
        //     $this->authorize('create', User::class);
        // } else {
        //     $this->authorize('create', MaaalWriter::class);
        // }

        try {
            $data['language'] = $data['language'] ?? auth()->user()->language;
            $data['image'] = isset($data['image']) ? $this->handleImage($data['image']) : null;

            if (isset($data['wirters']) && $data['wirters'] == 1) {
                $first = Str::slug($data['first_name'] ?? 'user');
                $last = Str::slug($data['last_name'] ?? 'writer');
                $base = $first . '.' . $last;
                $data['slug'] = $base;

                // user_name
                if (empty($data['user_name'])) {
                    $username = $base;
                    $counter = 1;
                    while (User::where('user_name', $username)->exists()) {
                        $username = $base . $counter;
                        $counter++;
                    }
                    $data['user_name'] = $username;
                }

                // email
                if (empty($data['email'])) {
                    $emailBase = $base . '@example.com';
                    $email = $emailBase;
                    $counter = 1;
                    while (User::where('email', $email)->exists()) {
                        $email = $base . $counter . '@example.com';
                        $counter++;
                    }
                    $data['email'] = $email;
                }

                // password
                if (empty($data['password'])) {
                    $data['password'] = Str::random(10);
                    $data['password_confirmation'] = $data['password'];
                }
            }

            // if (isset($data['role']) && $data['role'] == 'admin' && !auth()->user()->hasRole('admin')) {
            //     unset($data['role']);
            // }

            $password = $data['password'];
            $user = User::create($data);

            // if (isset($data['role']) && !isset($data['wirters'])) {
            //     $user->assignRole($data['role']);
            // }

            // if (isset($data['wirters']) && $data['wirters'] == 1) {
            //     $role = auth()->user()->hasRole('admin')
            //         ? ($data['language'] == 'ar' ? 'Arabic Writer' : 'English Writer')
            //         : (auth()->user()->language == 'ar' ? 'Arabic Writer' : 'English Writer');

            //     $user->assignRole($role);
            // }

            // if ($data['is_notify']) {
            //     Mail::to($user->email)->send(new UserWelcomeMail($user->user_name, $password));
            // }

            return new UserResource($user);
        } catch (Exception $e) {
            dd($e);
            return 'error';
        }
    }


    public function update($data, $id)
    {
        $user = User::find($id);

        try {
            $data = $data->all();
            $data['language'] = $user->language;

            if (isset($data['wirters']) && $data['wirters'] == 1) {
                $first = Str::slug($data['first_name'] ?? $user->first_name ?? 'user');
                $last = Str::slug($data['last_name'] ?? $user->last_name ?? 'writer');
                $base = $first . '.' . $last;

                if (empty($data['user_name'])) {
                    $username = $base;
                    $counter = 1;
                    while (User::where('user_name', $username)->where('id', '!=', $id)->exists()) {
                        $username = $base . $counter;
                        $counter++;
                    }
                    $data['user_name'] = $username;
                }

                if (empty($data['email'])) {
                    $emailBase = $base . '@example.com';
                    $email = $emailBase;
                    $counter = 1;
                    while (User::where('email', $email)->where('id', '!=', $id)->exists()) {
                        $email = $base . $counter . '@example.com';
                        $counter++;
                    }
                    $data['email'] = $email;
                }

                if (empty($data['password'])) {
                    $generatedPassword = Str::random(10);
                    $data['password'] = $generatedPassword;
                    $data['password_confirmation'] = $generatedPassword;
                }
            }

            if (isset($data['image']) && is_file($data['image'])) {
                if ($user->image) {
                    $this->removeImage($user->getRawOriginal('image'));
                }
                $data['image'] = $this->handleImage($data['image']);
            }

            // if (isset($data['role']) && $data['role'] == 'admin' && !auth()->user()->hasRole('admin')) {
            //     unset($data['role']);
            // }

            $user->update($data);

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
