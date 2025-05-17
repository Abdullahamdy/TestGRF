<?php

namespace App\Http\Resources\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'language'=>$this->language,
            'slug'=>$this->slug,
            'user_created'=>$this->user->user_name,
            'user_id'=>$this->user_id,
            'description'=>$this->description,
            'count_news' => $this->news_count ?? 0,
            'created_at' => $this->created_at,

        ];
    }
}
