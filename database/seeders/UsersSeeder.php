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
                'first_name' => 'Abdullah',
                'last_name' => 'Admin',
                'slug' => Str::slug('abdullah-admin'),
                'x_link' => 'x_link',
                'email_verified_at' => now(),
                'linkedIn_link' => null,
                'phone' => '201012589732',
                'gender' => 1,
                'image' => "/storage/uploads/photos/maalwriters/2025-02/11.jpg",
                'date_of_birth' => '1997-10-16',
                'email' => 'abdullahhamdy29@gmail.com',
                'password' => 'stECjY*)M5LGrnEAWF0q)j6N',
                'user_name' => 'Abdullah_Admin',
            ],
            [
                'first_name' => 'Mohammed',
                'last_name' => 'SamirAr',
                'slug' => Str::slug('mohammed-samir-ar'),
                'x_link' => 'x_link',
                'email_verified_at' => now(),
                'linkedIn_link' => null,
                'phone' => '009659058872',
                'gender' => 1,
                'image' => "/storage/uploads/photos/maalwriters/2025-02/11.jpg",
                'date_of_birth' => '1997-10-16',
                'email' => 'mohammed_samir_ar@gmail.com',
                'password' => 12345678,
                'user_name' => 'mohammed_samir_ar',
                'language' => 'ar',
            ],
            [
                'first_name' => 'Mohammed',
                'last_name' => 'SamirEn',
                'slug' => Str::slug('mohammed-samir-en'),
                'x_link' => 'x_link',
                'email_verified_at' => now(),
                'linkedIn_link' => null,
                'phone' => '009659058873',
                'gender' => 1,
                'image' => "/storage/uploads/photos/maalwriters/2025-02/11.jpg",
                'date_of_birth' => '1997-10-16',
                'email' => 'mohammed_samir_en@gmail.com',
                'password' => 12345678,
                'user_name' => 'mohammed_samir_en',
                'language' => 'en',
            ],
        ];


            foreach ($users as $userData) {
                 User::create($userData);
            }

    }

    private function getImageFileName()
    {
        $number = rand(1, 10); // اختيار رقم عشوائي بين 1 و 10

        // تحديد الامتداد بناءً على الرقم
        $extension = match ($number) {
            1, 6 => 'png',
            2, 4, 8, 10 => 'jpeg',
            3, 5, 7, 9 => 'jpg',
            default => 'jpg', // باقي الأرقام تكون JPG
        };

        return "$number.$extension";
    }
}
