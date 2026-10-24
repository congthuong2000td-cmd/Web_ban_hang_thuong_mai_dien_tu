<?php

namespace App\Enums;

enum UserRole: string
{
    case CUSTOMER = 'customer';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::CUSTOMER => 'Khách hàng',
            self::ADMIN => 'Quản trị viên',
        };
    }
}
