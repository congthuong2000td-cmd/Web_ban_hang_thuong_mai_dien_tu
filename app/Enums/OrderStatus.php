<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case SHIPPING = 'shipping';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Chờ xác nhận',
            self::SHIPPING => 'Đang giao',
            self::COMPLETED => 'Hoàn thành',
            self::CANCELLED => 'Đã hủy',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning text-dark',
            self::SHIPPING => 'bg-primary text-white',
            self::COMPLETED => 'bg-success text-white',
            self::CANCELLED => 'bg-danger text-white',
        };
    }
}
