<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'coupon_id',
        'receiver_name',
        'receiver_phone',
        'shipping_address',
        'subtotal',
        'shipping_fee',
        'discount_amount',
        'total_amount',
        'status',
        'note',
        'cancel_reason',
        'cancelled_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'shipping_fee' => 'integer',
            'discount_amount' => 'integer',
            'total_amount' => 'integer',
            'status' => OrderStatus::class,
            'cancelled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', '.') . ' ₫';
    }

    public function canBeCancelledByCustomer(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    public function canBeCancelledByAdmin(): bool
    {
        return in_array($this->status, [OrderStatus::PENDING, OrderStatus::SHIPPING], true);
    }
}
