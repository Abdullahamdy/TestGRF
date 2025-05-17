<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();
        $categories  = [
            'ar' => [

                ['language' => 'ar', 'type' => 2, 'name' => 'الأخبار الاقتصادية', 'description' => 'أهم الأخبار الاقتصادية والاستثمارات'],
                ['language' => 'ar', 'type' => 2, 'name' => 'مال ثانك تانك', 'description' => ' وصف مال ثانك تانك' ],
                ['language' => 'ar', 'type' => 2, 'name' => 'عقار', 'description' => 'أحدث أخبار وأسعار العقارات'],
                ['language' => 'ar', 'type' => 2, 'name' => 'أعمال تك', 'description' => 'أخبار الأعمال والتكنولوجيا'],
                ['language' => 'ar', 'type' => 2, 'name' => 'أخبار عامة', 'description' => 'أخبار متنوعة من جميع المجالات'],
                ['language' => 'ar', 'type' => 2, 'name' => 'مجتمع الأعمال', 'description' => 'أخبار رواد الأعمال والشركات'],
                ['language' => 'ar', 'type' => 2, 'name' => 'حول العالم', 'description' => 'أخبار عالمية في جميع المجالات'],
                ['language' => 'ar', 'type' => 2, 'name' => 'خدمات', 'description' => 'معلومات وخدمات تهم الجميع'],
                ['language' => 'ar', 'type' => 2, 'name' => 'مال المدينة', 'description' => 'وصف مال كابيتال'],
                ['language' => 'ar', 'type' => 2, 'name' => 'فيديو', 'description' => 'وصف مال كابيتال'],
                ['language' => 'ar', 'type' => 2, 'name' => 'انفوجرافيك', 'description' => 'وصف مال كابيتال'],
                ['language' => 'ar', 'type' => 2, 'name' => 'اصدارات خاصه', 'description' => 'وصف مال كابيتال'],

            ],

            'en' => [


                ['language' => 'en', 'type' => 2, 'name' => 'Economic News', 'description' => 'Top economic and investment news'],
                ['language' => 'en', 'type' => 2, 'name' => 'Real Estate', 'description' => 'Latest real estate news and prices'],
                ['language' => 'en', 'type' => 2, 'name' => 'Maal Thank Tank', 'description' => 'ThankTank description'],
                ['language' => 'en', 'type' => 2, 'name' => 'Drug Maaal', 'description' => 'Drug Maaal Description'],
                ['language' => 'en', 'type' => 2, 'name' => 'Business Tech', 'description' => 'News on business and technology'],
                ['language' => 'en', 'type' => 2, 'name' => 'General News', 'description' => 'Diverse news from all fields'],
                ['language' => 'en', 'type' => 2, 'name' => 'Business Community', 'description' => 'News about entrepreneurs and companies'],
                ['language' => 'en', 'type' => 2, 'name' => 'Around the World', 'description' => 'Global news in various fields'],
                ['language' => 'en', 'type' => 2, 'name' => 'Services', 'description' => 'Information and services for everyone'],
                ['language' => 'en', 'type' => 2, 'name' => 'Maaal Capital', 'description' => 'Maal Capital description'],
                ['language' => 'en', 'type' => 2, 'name' => 'video', 'description' => 'description Capital Maal'],
                ['language' => 'en', 'type' => 2, 'name' => 'info Graphic', 'description' => 'description'],
                ['language' => 'en', 'type' => 2, 'name' => 'Special Editions', 'description' => 'new description'],
                ['language' => 'en', 'type' => 2, 'name' => 'Exclusive News', 'description' => 'Health tips and wellness advice'],
                ['language' => 'en', 'type' => 2, 'name' => 'Top News', 'description' => 'Health tips and wellness advice'],

            ],
        ];


        foreach ($categories as $language => $categoryList) {
            foreach ($categoryList as $category) {
                Category::create([
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                    'language' => $category['language'],
                    'user_id' => 2,
                    'parent_id' => null,
                    'show_in_dashboard' => true,
                ]);
            }
        }

    
    }
}
