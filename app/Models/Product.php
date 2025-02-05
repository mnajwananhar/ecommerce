<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'price', 'weight', 'seller_id', 'category_id', 'stock'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    // Relasi ke User (Penjual)
    public function sellerRequest()
    {
        return $this->hasOne(SellerRequest::class, 'user_id', 'seller_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id'); // Pastikan kolom seller_id ada di tabel products
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'seller_id', 'seller_id');
    }

    public function getFirstImageUrl()
    {
        return $this->images()->first() ? Storage::url($this->images()->first()->image_path) : asset('images/fallback.png');
    }
}
