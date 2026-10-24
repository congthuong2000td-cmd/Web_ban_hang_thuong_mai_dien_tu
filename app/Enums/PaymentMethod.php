<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD = 'cod';
    case VNPAY = 'vnpay';

    public function label(): string
    {
        return match ($this) {
            self::COD => 'Thanh toán khi nhận hàng (COD)',
            self::VNPAY => 'VNPAY Sandbox (Thẻ ATM/QR/Quốc tế)',
        };
    }
}
