<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_address',
        'courier',
        'shipping_cost',
        'total_price',
        'status',
    ];


    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Produk melalui Pivot
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class)->with(['product.images']);
    }

    // Helper method untuk debug
    public function hasValidDetails()
    {
        return $this->details()
            ->whereHas('product', function ($query) {
                $query->whereHas('images');
            })
            ->exists();
    }
}
