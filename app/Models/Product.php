<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'price', 'stock', 'image', 'is_featured', 'sold', 'discount_price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function isWishlisted()
    {
        return auth()->check() && auth()->user()->wishlistProducts()->where('product_id', $this->id)->exists();
    }

 // Accessor for image_url
    public function getImageUrlAttribute()
    {
        // যদি external URL হয় (http বা https দিয়ে শুরু)
        if ($this->image && preg_match('/^https?:\/\//', $this->image)) {
            return $this->image;
        }

        // যদি local storage এ থাকে
        return $this->image 
            ? asset('storage/' . $this->image) 
            : asset('storage/placeholders/no_image.png');
    }


}
