<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'publisher' => $this->publisher?->name,
            'is_scheduled' => (bool) !$this->is_published,
            'sub_title' => $this->sub_title,
            'slug' => $this->slug,
            'is_featured' => $this->is_featured,
            'image' => $this->main_image ?  gethost() . $this->main_image : null,
            'image_description' => $this->image_description,
            'is_published' => $this->is_published,
            'schedule_date' => $this->schudle_date,
            'schedule_time' => $this->schudle_time,
            'meta_image' => $this->meta_image ?  gethost() . $this->meta_image : null,
            'category_id' => $this->category_id,
            'category' => $this->category?->name,
            'sub_category_id' => $this->sub_category_id,
            'sub_category' => $this->sub_category?->name,
            'status' => $this->status,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,

            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ];
            }),

            'created_at' => $this->created_at,

        ];
    }
}
