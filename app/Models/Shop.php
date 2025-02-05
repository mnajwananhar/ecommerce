<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'shop_name',
        'shop_address',
        'shop_address_label',
        'description',
        'shop_logo',
    ];

    public function getRouteKeyName()
    {
        return 'shop_name';
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'seller_id');
    }
}
