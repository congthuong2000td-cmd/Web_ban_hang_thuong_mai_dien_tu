<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ thanh toán',
            self::PAID => 'Đã thanh toán',
            self::FAILED => 'Thất bại',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning text-dark',
            self::PAID => 'bg-success text-white',
            self::FAILED => 'bg-danger text-white',
        };
    }
}
