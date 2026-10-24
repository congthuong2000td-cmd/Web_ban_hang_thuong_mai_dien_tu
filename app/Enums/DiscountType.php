<?php

namespace App\Enums;

enum DiscountType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';

    public function label(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'Giảm theo phần trăm (%)',
            self::FIXED      => 'Giảm tiền cố định (₫)',
        };
    }
}
