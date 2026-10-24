<?php

namespace App\Models;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_type'    => DiscountType::class,
            'discount_value'   => 'integer',
            'min_order_amount' => 'integer',
            'max_discount'     => 'integer',
            'usage_limit'      => 'integer',
            'used_count'       => 'integer',
            'starts_at'        => 'datetime',
            'expires_at'       => 'datetime',
            'is_active'        => 'boolean',
        ];
    }

    /**
     * Kiểm tra coupon có hợp lệ cho giá trị đơn hàng không.
     */
    public function isValid(int $subtotal): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($subtotal < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Tính số tiền giảm.
     */
    public function calculateDiscount(int $subtotal): int
    {
        if (! $this->isValid($subtotal)) {
            return 0;
        }

        if ($this->discount_type === DiscountType::PERCENTAGE) {
            $discount = (int) round(($subtotal * $this->discount_value) / 100);
            if ($this->max_discount !== null && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return min($discount, $subtotal);
        }

        // FIXED type
        return min($this->discount_value, $subtotal);
    }

    /**
     * Accessor định dạng tiền tệ.
     */
    public function getDiscountLabelAttribute(): string
    {
        return $this->discount_type === DiscountType::PERCENTAGE
            ? "-{$this->discount_value}%"
            : '-' . number_format($this->discount_value, 0, ',', '.') . '₫';
    }
}
