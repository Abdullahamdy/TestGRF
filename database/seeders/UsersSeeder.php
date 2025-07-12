<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'en' => [
                    'first_name' => 'Abdullah',
                    'last_name'  => 'Admin',
                    'slug'       => Str::slug('abdullah-admin'),
                ],
                'ar' => [
                    'first_name' => 'عبدالله',
                    'last_name'  => 'مشرف',
                    'slug'       => Str::slug('عبدالله-مشرف'),
                ],
                'email'     => 'abdullahhamdy29@gmail.com',
                'user_name' => 'Abdullah_Admin',
                'role' => 'Admin',
                'password'  => 'stECjY*)M5LGrnEAWF0q)j6N',
            ],
            [
                'en' => [
                    'first_name' => 'Mohammed',
                    'last_name'  => 'Samir',
                    'slug'       => Str::slug('mohammed-samir'),
                ],
                'ar' => [
                    'first_name' => 'محمد',
                    'last_name'  => 'سمير',
                    'slug'       => Str::slug('محمد-سمير'),
                ],
                'email'     => 'mohammed_samir@gmail.com',
                'user_name' => 'mohammed_samir',
                'role'      => 'Admin',
                'password'  => '12345678',
            ],
            //  officials

            [
                'en' => [
                    'first_name' => 'Omar',
                    'last_name'  => 'Yousef',
                    'slug'       => Str::slug('omar-yousef'),
                ],
                'ar' => [
                    'first_name' => 'عمر',
                    'last_name'  => 'يوسف',
                    'slug'       => Str::slug('عمر-يوسف'),
                ],
                'email'     => 'omar.yousef@example.com',
                'user_name' => 'omar_yousef',
                'role'      => 'Official',
                'password'  => 'official1234',
            ],
            [
                'en' => [
                    'first_name' => 'Laila',
                    'last_name'  => 'Hassan',
                    'slug'       => Str::slug('laila-hassan'),
                ],
                'ar' => [
                    'first_name' => 'ليلى',
                    'last_name'  => 'حسن',
                    'slug'       => Str::slug('ليلى-حسن'),
                ],
                'email'     => 'laila.hassan@example.com',
                'user_name' => 'laila_hassan',
                'role'      => 'Official',
                'password'  => 'official5678',
            ],

            //end officials


            //team

            [
                'en' => [
                    'first_name' => 'Yasmine',
                    'last_name'  => 'Khaled',
                    'slug'       => Str::slug('yasmine-khaled'),
                ],
                'ar' => [
                    'first_name' => 'ياسمين',
                    'last_name'  => 'خالد',
                    'slug'       => Str::slug('ياسمين-خالد'),
                ],
                'email'     => 'yasmine.khaled@example.com',
                'user_name' => 'yasmine_khaled',
                'role'      => 'Team Work',
                'password'  => 'teamwork123',
            ],
            [
                'en' => [
                    'first_name' => 'Ahmed',
                    'last_name'  => 'Tariq',
                    'slug'       => Str::slug('ahmed-tariq'),
                ],
                'ar' => [
                    'first_name' => 'أحمد',
                    'last_name'  => 'طارق',
                    'slug'       => Str::slug('أحمد-طارق'),
                ],
                'email'     => 'ahmed.tariq@example.com',
                'user_name' => 'ahmed_tariq',
                'role'      => 'Team Work',
                'password'  => 'teamwork456',
            ],

            //end team
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            $en = $userData['en'];
            $ar = $userData['ar'];

            unset($userData['en'], $userData['ar']);

            $user = User::create([
                ...$userData,
                'x_link'            => 'x_link',
                'linkedIn_link'     => null,
                'phone'             => '201012589732',
                'gender'            => 1,
                'image'             => "/storage/uploads/photos/maalwriters/2025-02/11.jpg",
                'date_of_birth'     => '1997-10-16',
                'email_verified_at' => now(),
                'password'          => $userData['password'],
            ]);

            $user->translateOrNew('en')->fill($en);
            $user->translateOrNew('ar')->fill($ar);
            $user->save();
            $user->assignRole(Role::findByName($role, 'sanctum'));
        }
    }
}
