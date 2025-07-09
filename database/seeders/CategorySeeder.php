<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::truncate();

        $categories = [
            [
                'en' => [
                    'name' => 'Economic News',
                    'description' => 'Top economic and investment news',
                ],
                'ar' => [
                    'name' => 'الأخبار الاقتصادية',
                    'description' => 'أهم الأخبار الاقتصادية والاستثمارات',
                ],
            ],
            [
                'en' => [
                    'name' => 'Mal Think Tank',
                    'description' => 'Description of Mal Think Tank',
                ],
                'ar' => [
                    'name' => 'مال ثانك تانك',
                    'description' => 'وصف مال ثانك تانك',
                ],
            ],
            [
                'en' => [
                    'name' => 'Real Estate',
                    'description' => 'Latest real estate news and prices',
                ],
                'ar' => [
                    'name' => 'عقار',
                    'description' => 'أحدث أخبار وأسعار العقارات',
                ],
            ],
            [
                'en' => [
                    'name' => 'Business Tech',
                    'description' => 'Business and technology news',
                ],
                'ar' => [
                    'name' => 'أعمال تك',
                    'description' => 'أخبار الأعمال والتكنولوجيا',
                ],
            ],
            [
                'en' => [
                    'name' => 'General News',
                    'description' => 'News from various fields',
                ],
                'ar' => [
                    'name' => 'أخبار عامة',
                    'description' => 'أخبار متنوعة من جميع المجالات',
                ],
            ],
            [
                'en' => [
                    'name' => 'Business Community',
                    'description' => 'Entrepreneur and company news',
                ],
                'ar' => [
                    'name' => 'مجتمع الأعمال',
                    'description' => 'أخبار رواد الأعمال والشركات',
                ],
            ],
            [
                'en' => [
                    'name' => 'Around the World',
                    'description' => 'Global news from all fields',
                ],
                'ar' => [
                    'name' => 'حول العالم',
                    'description' => 'أخبار عالمية في جميع المجالات',
                ],
            ],
            [
                'en' => [
                    'name' => 'Services',
                    'description' => 'Useful information and services',
                ],
                'ar' => [
                    'name' => 'خدمات',
                    'description' => 'معلومات وخدمات تهم الجميع',
                ],
            ],
            [
                'en' => [
                    'name' => 'Mal City',
                    'description' => 'Mal Capital description',
                ],
                'ar' => [
                    'name' => 'مال المدينة',
                    'description' => 'وصف مال كابيتال',
                ],
            ],
            [
                'en' => [
                    'name' => 'Video',
                    'description' => 'Mal Capital description',
                ],
                'ar' => [
                    'name' => 'فيديو',
                    'description' => 'وصف مال كابيتال',
                ],
            ],
            [
                'en' => [
                    'name' => 'Infographic',
                    'description' => 'Mal Capital description',
                ],
                'ar' => [
                    'name' => 'انفوجرافيك',
                    'description' => 'وصف مال كابيتال',
                ],
            ],
            [
                'en' => [
                    'name' => 'Special Editions',
                    'description' => 'Mal Capital description',
                ],
                'ar' => [
                    'name' => 'اصدارات خاصه',
                    'description' => 'وصف مال كابيتال',
                ],
            ],
        ];

        foreach ($categories as $data) {
            $category = new Category([
                'user_id' => 2,
                'parent_id' => null,
            ]);

            foreach (['en', 'ar'] as $locale) {
                $category->translateOrNew($locale)->name = $data[$locale]['name'];
                $category->translateOrNew($locale)->slug = $data[$locale]['name'];
                $category->translateOrNew($locale)->description = $data[$locale]['description'];
            }

            $category->save();
        }
    }
}
