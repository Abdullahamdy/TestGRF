<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Filters\UserFilter;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, Notifiable,HasRoles,Filterable;

    protected $filter = UserFilter::class;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [''];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function setPasswordAttribute($value)
    {

        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }


    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function setSlugAttribute($value)
    {
        if (preg_match('/\p{Arabic}/u', $value)) {
            $slug = preg_replace('/\s+/u', '-', trim($value));
            $slug = preg_replace('/[^\p{Arabic}a-zA-Z0-9\-]/u', '', $slug);
        } else {
            $slug = Str::slug($value);
        }

        $this->attributes['slug'] = $slug;
    }
}
