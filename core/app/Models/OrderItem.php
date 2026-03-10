<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'product_id',
        'product_detail_id',
        'price',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function productDetail(){
        return $this->belongsTo(ProductDetail::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
