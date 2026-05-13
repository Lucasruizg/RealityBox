<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category',
        'rating',
        'reviews',
        'is_featured',
        'is_popular',
    ];

    public function getHasImageAttribute(): bool
    {
        return filled($this->image) && file_exists(public_path('images/products/' . $this->image));
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->has_image ? asset('images/products/' . $this->image) : null;
    }

    public function boxOpenings(): HasMany
    {
        return $this->hasMany(BoxOpening::class);
    }
}
