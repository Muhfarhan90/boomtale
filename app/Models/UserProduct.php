<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProduct extends Model
{
    use HasFactory;

    protected $table = 'user_products';
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'order_item_id',
        'purchase_price',
        'purchased_at',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function orderItem() {
        return $this->belongsTo(OrderItem::class);
    }

}
