<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $casts = [
        'expired_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Get the status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'waiting_payment' => 'info',
            'processing' => 'primary',
            'shipped' => 'secondary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Check if order is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expired_at && Carbon::now()->isAfter($this->expired_at);
    }

    /**
     * Check if order can be cancelled
     */
    public function getCanBeCancelledAttribute()
    {
        return in_array($this->status, ['pending', 'waiting_payment']);
    }

    /**
     * Check if order can be paid
     */
    public function getCanBePaidAttribute()
    {
        return $this->status === 'waiting_payment' && !$this->is_expired;
    }

    /**
     * Get order items count
     */
    public function getItemsCountAttribute()
    {
        return $this->orderItems->sum('quantity');
    }
}
