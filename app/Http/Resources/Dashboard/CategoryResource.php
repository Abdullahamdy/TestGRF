<?php

namespace App\Http\Resources\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'slug' => $this->slug,
            'user_created'=>$this->user?->user_name,
            'parent_id' => $this->parent_id,
            'main_category' => $this->parent_id ? $this->mainCategory?->name : null,
            'news_count' => $this->news_count,
            'created_at' => $this->created_at,
        ];
    }
}
