<?php

namespace Database\Seeders;

use App\Enums\DiscountType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code'           => 'WELCOME10',
                'name'           => 'Chào mừng khách mới - giảm 10%',
                'discount_type'  => DiscountType::PERCENTAGE,
                'discount_value' => 10,
                'min_order_amount'=> 200000,
                'max_discount'   => 50000,
                'usage_limit'    => 1000,
                'used_count'     => 0,
                'starts_at'      => now(),
                'expires_at'     => now()->addYear(),
                'is_active'      => true,
            ],
            [
                'code'           => 'FREESHIP',
                'name'           => 'Miễn phí vận chuyển',
                'discount_type'  => DiscountType::FIXED,
                'discount_value' => 50000,
                'min_order_amount'=> 300000,
                'max_discount'   => null,
                'usage_limit'    => 500,
                'used_count'     => 0,
                'starts_at'      => now(),
                'expires_at'     => now()->addMonths(3),
                'is_active'      => true,
            ],
            [
                'code'           => 'SALE20K',
                'name'           => 'Giảm 20.000₫ đơn từ 150.000₫',
                'discount_type'  => DiscountType::FIXED,
                'discount_value' => 20000,
                'min_order_amount'=> 150000,
                'max_discount'   => null,
                'usage_limit'    => 2000,
                'used_count'     => 0,
                'starts_at'      => now(),
                'expires_at'     => now()->addMonths(6),
                'is_active'      => true,
            ],
            [
                'code'           => 'VIP15',
                'name'           => 'Khách VIP - giảm 15%',
                'discount_type'  => DiscountType::PERCENTAGE,
                'discount_value' => 15,
                'min_order_amount'=> 500000,
                'max_discount'   => 150000,
                'usage_limit'    => 100,
                'used_count'     => 0,
                'starts_at'      => now(),
                'expires_at'     => now()->addMonths(2),
                'is_active'      => true,
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
