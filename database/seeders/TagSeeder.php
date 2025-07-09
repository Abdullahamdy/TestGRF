<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class TagSeeder extends Seeder
{

       public function run()
    {
        $tags = [
            [
                'en' => [
                    'name' => 'Politics',
                    'slug' => 'Politics',
                    'description' => 'Political news',
                ],
                'ar' => [
                    'name' => 'سياسة',
                    'slug' => 'سياسة',
                    'description' => 'أخبار السياسة',
                ],
            ],
            [
                'en' => [
                    'name' => 'World',
                    'slug' => 'World',
                    'description' => 'International news',
                ],
                'ar' => [
                    'name' => 'عالم',
                    'slug' => 'عالم',
                    'description' => 'الأخبار الدولية',
                ],
            ],
            [
                'en' => [
                    'name' => 'Economy',
                    'slug' => 'Economy',
                    'description' => 'Economic trends',
                ],
                'ar' => [
                    'name' => 'اقتصاد',
                    'slug' => 'اقتصاد',
                    'description' => 'اتجاهات الاقتصاد',
                ],
            ],
            [
                'en' => [
                    'name' => 'Sports',
                    'slug' => 'Sports',
                    'description' => 'Sports news',
                ],
                'ar' => [
                    'name' => 'رياضة',
                    'slug' => 'رياضة',
                    'description' => 'أخبار الرياضة',
                ],
            ],
            [
                'en' => [
                    'name' => 'Entertainment',
                    'slug' => 'Entertainment',
                    'description' => 'Celebrity and media news',
                ],
                'ar' => [
                    'name' => 'ترفيه',
                    'slug' => 'ترفيه',
                    'description' => 'أخبار المشاهير',
                ],
            ],
        ];

        foreach ($tags as $tagData) {
            $tag = new Tag(['user_id' => 1]);

            foreach (['en', 'ar'] as $locale) {
                $tag->translateOrNew($locale)->name = $tagData[$locale]['name'];
                $tag->translateOrNew($locale)->slug = $tagData[$locale]['slug'];
                $tag->translateOrNew($locale)->description = $tagData[$locale]['description'];
            }

            $tag->save();
        }
    }

}
