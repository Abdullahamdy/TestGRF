<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LIFISeeder extends Seeder
{
    public function run()
    {
        $arabicCategories = Category::where('language', 'ar')->pluck('id')->toArray();
        $englishCategories = Category::where('language', 'en')->pluck('id')->toArray();

        $arabicTags = Tag::where('language', 'ar')->pluck('id')->toArray();
        $englishTags = Tag::where('language', 'en')->pluck('id')->toArray();

        foreach ($arabicCategories as $categoryId) {
            $newsData = [
                [
                    'title' => "إصدار (لام للأبحاث والدراسات) التابع لشركة (مال الإعلامية الدولية). السعودية تستقطب الشركات العالمية",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/مقارالشركات.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/غلاف-مقارالشركات.jpg",
                ],
                [
                    'title' => "تصفح إصدار (مال): ترمب العائد بالتعريفات الجمركية والوقود الأحفوري والعملات المشفرة",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.jpg",

                ],
                [
                    'title' => "(مال) ترصد الأحداث الاقتصادية في المملكة 2024",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.jpg",

                ],
                [
                    'title' => "مترو الرياض قطار للتغيير(إصدار خاص بمناسبة تدشين مترو الرياض)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.jpg",

                ],
                [
                    'title' => "تصفح إصدار (مال) الخاص بميزانية السعودية 2025 (استقطاب الاستثمار وتعزيز نمو الأنشطة غير النفطية)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.jpg",

                ],
                //repeat

                [
                    'title' => "إصدار (لام للأبحاث والدراسات) التابع لشركة (مال الإعلامية الدولية). السعودية تستقطب الشركات العالمية",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/مقارالشركات.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/غلاف-مقارالشركات.jpg",
                ],
                [
                    'title' => "تصفح إصدار (مال): ترمب العائد بالتعريفات الجمركية والوقود الأحفوري والعملات المشفرة",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.jpg",

                ],
                [
                    'title' => "(مال) ترصد الأحداث الاقتصادية في المملكة 2024",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.jpg",

                ],
                [
                    'title' => "مترو الرياض قطار للتغيير(إصدار خاص بمناسبة تدشين مترو الرياض)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.jpg",

                ],
                [
                    'title' => "تصفح إصدار (مال) الخاص بميزانية السعودية 2025 (استقطاب الاستثمار وتعزيز نمو الأنشطة غير النفطية)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.jpg",

                ],
            ];

            foreach ($newsData as $data) {

                $isPublished = rand(0, 1);
                [$scheduleDate, $scheduleTime] = $this->getScheduleDateTime($isPublished);

                $news = News::create([
                    'title' => $data['title'],
                    'sub_title' => $data['title'],
                    'type' => 'special',
                    'source' => 'صحيفة مال',
                    'language' => 'ar',
                    'publisher_id' => "صحيفه مال",
                    'order_featured' => 0,
                    'slug' => Str::slug("special-edition-ar-" . Str::random(5)),
                    'show_in_slider' => 0,
                    'order_slider' => 0,
                    'file' => $data['file'],
                    'description' => null,
                    'main_image' => $data['image'],
                    'image_description' => "صورة لخبر إصدار خاص",
                    'is_published' => $isPublished,
                    'status' => $isPublished == 1 ? 1 : 2,
                    'schudle_date' => $isPublished == 0 ? $scheduleDate : null,
                    'schudle_time' => $isPublished == 0 ? $scheduleTime : null,
                    'category_id' => $categoryId,
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),
                ]);


                $news->tags()->attach($this->getRandomTags($arabicTags));

            }
        }

        foreach ($englishCategories as $categoryId) {


            $isPublished = rand(0, 1);
            $newsData = [
                [
                    'title' => "Lem Research & Studies (Affiliated with Maal International Media): Saudi Arabia Attracts Global Companies",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/مقارالشركات.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/غلاف-مقارالشركات.jpg",
                ],
                [
                    'title' => "Browse Maal's Special Edition: Trump’s Return with Tariffs, Fossil Fuels, and Cryptocurrencies",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.jpg",
                ],
                [
                    'title' => "Maal Monitors Economic Events in Saudi Arabia 2024",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.jpg",
                ],
                [
                    'title' => "Riyadh Metro: A Train for Change (Special Edition on the Launch of Riyadh Metro)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.jpg",
                ],
                [
                    'title' => "Browse Maal’s Special Edition on Saudi Arabia's 2025 Budget (Attracting Investment & Boosting Non-Oil Growth)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.jpg",
                ],
                // Repeat
                [
                    'title' => "Lem Research & Studies (Affiliated with Maal International Media): Saudi Arabia Attracts Global Companies",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/مقارالشركات.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/غلاف-مقارالشركات.jpg",
                ],
                [
                    'title' => "Browse Maal's Special Edition: Trump’s Return with Tariffs, Fossil Fuels, and Cryptocurrencies",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.jpg",
                ],
                [
                    'title' => "Maal Monitors Economic Events in Saudi Arabia 2024",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/محركات-اقتصادية.jpg",
                ],
                [
                    'title' => "Riyadh Metro: A Train for Change (Special Edition on the Launch of Riyadh Metro)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/متروالرياض.jpg",
                ],
                [
                    'title' => "Browse Maal’s Special Edition on Saudi Arabia's 2025 Budget (Attracting Investment & Boosting Non-Oil Growth)",
                    'file' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.pdf",
                    'image' => "/storage/uploads/photos/specialNews/2025-05/إصدارة-الميزانية.jpg",
                ],
            ];

            foreach ($newsData as $data) {
                [$scheduleDate, $scheduleTime] = $this->getScheduleDateTime($isPublished);

                $news = News::create([
                    'title' => $data['title'],
                    'sub_title' => $data['title'],
                    'type' => 'special',
                    'source' => 'Maal Newspaper',
                    'language' => 'en',
                    'slug' => Str::slug("special-edition-en-" . Str::random(5)),
                    'show_in_slider' => 0,
                    'order_slider' => 0,
                    'file' => $data['file'],
                    'order_featured' => 0,
                    'description' => null,
                    'main_image' => $data['image'],
                    'image_description' => "Image of a special edition news report",
                    'is_published' => $isPublished,
                    'status' => $isPublished == 1 ? 1 : 2,
                    'schudle_date' => $isPublished == 0 ? $scheduleDate : null,
                    'schudle_time' => $isPublished == 0 ? $scheduleTime : null,
                    'category_id' => $categoryId,
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),

                ]);

                $news->tags()->attach($this->getRandomTags($englishTags));


            }
        }
    }



    public function getRandomTags($tagsArray)
    {
        if (empty($tagsArray)) {
            return [];
        }
        return collect($tagsArray)->random(min(rand(1, 3), count($tagsArray)))->toArray();
    }

    public function getRandomImage()
    {
        $number = rand(1, 10);
        $extension = 'jpg';
        return "/storage/uploads/photos/newstest/2025-05/$number.$extension";
    }

    public function getScheduleDateTime($isPublished)
    {
        if ($isPublished) {
            return [null, null];
        } else {
            return [
                Carbon::now()->addDays(rand(1, 30))->toDateString(),
                Carbon::now()->addHours(rand(1, 24))->toTimeString()
            ];
        }
    }
}
