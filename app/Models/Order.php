<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'total_amount',
        'status',
        'expired_at',
    ];

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction() {
        return $this->hasOne(Transaction::class);
    }

}
