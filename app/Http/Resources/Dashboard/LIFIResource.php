<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LIFIResource extends JsonResource
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
            'type' => $this->type,
            'title' => $this->title,
            'editor_id' => $this->editor_id,
            'is_scheduled' => (bool) !$this->is_published,
            'publisher' =>$this->publisher?->name,
            'sub_title' => $this->sub_title,
            'editor_name' => $this->editor_name,
            'order_slider' => $this->order_slider,
            'order_featured' => $this->order_featured,
            'show_in_slider' => $this->show_in_slider,
            'language' => $this->language,
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
            'source' => $this->source,
            'file' => $this->type == 'special' && $this->file ?  gethost() . $this->file : null,
            'slug' => $this->slug,
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
