<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissionNames = [];

        foreach (adminDbTablesPermissions() as $item) {
            foreach (['create', 'edit', 'show', 'delete'] as $action) {
                if ($item == 'notification') {
                    $permissionNames[] = "send notification";
                    $permissionNames[] = "update setting notification";
                }
                if ($item == 'official') {
                    $permissionNames[] = "edit official";
                    $permissionNames[] = "show official";
                    $permissionNames[] = "delete official";
                }
                if ($item == 'team work') {
                    $permissionNames[] = "edit team work";
                    $permissionNames[] = "show team work";
                    $permissionNames[] = "delete team work";
                }

                if ($item != 'ticket'  && $item != 'official' && $item != 'team work') {
                    $permissionNames[] = "{$action} {$item}";
                } else {
                    $permissionNames[] = "replay ticket";
                    $permissionNames[] = "show ticket";
                    $permissionNames[] = "delete ticket";
                }
            }
        }

        foreach ($permissionNames as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'sanctum',
            ]);
        }

        // Create role
        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'sanctum',
        ]);
        $official = Role::firstOrCreate([
            'name' => 'Official',
            'guard_name' => 'sanctum',
        ]);
        $team = Role::firstOrCreate([
            'name' => 'Team Work',
            'guard_name' => 'sanctum',
        ]);


        // Sync all permissions
        $admin->syncPermissions(Permission::where('guard_name', 'sanctum')->pluck('name')->toArray());
        $team->syncPermissions(Permission::where('guard_name', 'sanctum')->whereIn(
            'name',
            [
                'edit team work',
                'show team work',
                'delete team work',
            ]
        )->pluck('name')->toArray());
        $official->syncPermissions(Permission::where('guard_name', 'sanctum')->whereIn(
            'name',
            [
                'edit official',
                'show official',
                'delete official',
            ]
        )->pluck('name')->toArray());
    }
}
