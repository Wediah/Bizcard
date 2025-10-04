<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use Sluggable;
    public $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'business_name'
            ]
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $casts = [
        'social_links' => 'array',
        'theme_colors' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Reviews::class)->where('is_approved', true);
    }

    public function averageRating()
    {
        return $this->approvedReviews()->avg('rating');
    }
}
