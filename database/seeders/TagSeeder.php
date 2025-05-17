<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            // News (type = 2) - English
            ['name' => 'Politics', 'slug' => Str::slug('Politics'), 'description' => 'Political news', 'language' => 'en', 'user_id' => 1],
            ['name' => 'World', 'slug' => Str::slug('World'), 'description' => 'International news', 'language' => 'en', 'user_id' => 1],
            ['name' => 'Economy', 'slug' => Str::slug('Economy'), 'description' => 'Economic trends', 'language' => 'en', 'user_id' => 1],
            ['name' => 'Sports', 'slug' => Str::slug('Sports'), 'description' => 'Sports news', 'language' => 'en', 'user_id' => 1],
            ['name' => 'Entertainment', 'slug' => Str::slug('Entertainment'), 'description' => 'Celebrity and media news', 'language' => 'en', 'user_id' => 1],

            // News (type = 2) - Arabic
            ['name' => 'سياسة', 'slug' => Str::slug('سياسة'), 'description' => 'أخبار السياسة', 'language' => 'ar', 'user_id' => 1],
            ['name' => 'عالم', 'slug' => Str::slug('عالم'), 'description' => 'الأخبار الدولية', 'language' => 'ar', 'user_id' => 1],
            ['name' => 'اقتصاد', 'slug' => Str::slug('اقتصاد'), 'description' => 'اتجاهات الاقتصاد', 'language' => 'ar', 'user_id' => 1],
            ['name' => 'رياضة', 'slug' => Str::slug('رياضة'), 'description' => 'أخبار الرياضة', 'language' => 'ar', 'user_id' => 1],
            ['name' => 'ترفيه', 'slug' => Str::slug('ترفيه'), 'description' => 'أخبار المشاهير', 'language' => 'ar', 'user_id' => 1],
        ];

        \DB::table('tags')->insert($tags);
    }
}
