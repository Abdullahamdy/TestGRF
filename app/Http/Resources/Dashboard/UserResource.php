<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id'                => $this->id,
            'language'          => $this->language ?? 'ar',
            'slug'              => $this->slug,
            'email'             => $this->email,
            'role'              => $this->getRoleNames()?->first(),
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'user_name'         => $this->user_name,
            'code'             => $this->code,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'token'             => $this->token,
            'linkedIn_link'     => $this->linkedIn_link,
            'x_link'            => $this->x_link,
            'gender'            => $this->gender,
            'date_of_birth'     => $this->date_of_birth,
            'status'            => $this->status,
            'image'             => $this->image ? gethost() . $this->image : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),

        ];
    }
}
