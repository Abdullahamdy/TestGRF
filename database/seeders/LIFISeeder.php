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
        $categories = Category::pluck('id')->toArray();
        $tags = Tag::pluck('id')->toArray();

        $newsData = [
            [
                'ar' => [
                    'title' => "إصدار (لام للأبحاث والدراسات) التابع لشركة (مال الإعلامية الدولية). السعودية تستقطب الشركات العالمية",
                ],
                'en' => [
                    'title' => "LIF Studies Release: Saudi Arabia Attracts Global Companies",
                ],
                'file' => "/storage/uploads/photos/specialNews/2025-05/مقارالشركات.pdf",
                'image' => "/storage/uploads/photos/specialNews/2025-05/غلاف-مقارالشركات.jpg",
            ],
            [
                'ar' => [
                    'title' => "تصفح إصدار (مال): ترمب العائد بالتعريفات الجمركية والوقود الأحفوري والعملات المشفرة",
                ],
                'en' => [
                    'title' => "Maal Special: Trump Returns With Tariffs, Fossil Fuels, Crypto",
                ],
                'file' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.pdf",
                'image' => "/storage/uploads/photos/specialNews/2025-05/ترامب-العائد.jpg",
            ],
            // ... (repeat for remaining entries)
        ];

        foreach ($categories as $categoryId) {
            foreach ($newsData as $data) {
                $isPublished = rand(0, 1);
                [$scheduleDate, $scheduleTime] = $this->getScheduleDateTime($isPublished);

                $news = new News([
                    'sub_title' => $data['ar']['title'],
                    'type' => 'special',
                    'source' => 'صحيفة مال',
                    'publisher_id' => "صحيفه مال",
                    'order_featured' => 0,
                    'show_in_slider' => 0,
                    'order_slider' => 0,
                    'file' => $data['file'],
                    'main_image' => $data['image'],
                    'image_description' => "صورة لخبر إصدار خاص",
                    'is_published' => $isPublished,
                    'status' => $isPublished ? 1 : 2,
                    'schudle_date' => $isPublished ? null : $scheduleDate,
                    'schudle_time' => $isPublished ? null : $scheduleTime,
                    'category_id' => $categoryId,
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),
                ]);

                foreach (['ar', 'en'] as $locale) {
                    $title = $data[$locale]['title'];
                    $news->translateOrNew($locale)->title = $title;
                    $news->translateOrNew($locale)->slug = $title . '-' . Str::random(4);
                    $news->translateOrNew($locale)->description = $title;
                    $news->translateOrNew($locale)->meta_title = $title;
                    $news->translateOrNew($locale)->meta_description = $title;
                }

                $news->save();

                $news->tags()->attach($this->getRandomTags($tags));
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

    public function getScheduleDateTime($isPublished)
    {
        if ($isPublished) {
            return [null, null];
        } else {
            return [
                Carbon::now()->addDays(rand(1, 30))->toDateString(),
                Carbon::now()->addHours(rand(1, 24))->toTimeString(),
            ];
        }
    }
}
