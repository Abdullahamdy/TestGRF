<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'password'  => '12345678',
            ],
        ];

        foreach ($users as $userData) {
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
        }
    }
}
