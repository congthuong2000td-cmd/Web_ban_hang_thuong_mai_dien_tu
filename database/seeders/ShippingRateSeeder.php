<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        // 34 Tỉnh/Thành phố + phí vận chuyển chuẩn hóa
        $rates = [
            // Đặc biệt (Nội thành)
            ['province_name' => 'Hà Nội', 'fee' => 20000, 'estimated_days' => 1],
            ['province_name' => 'Hồ Chí Minh', 'fee' => 20000, 'estimated_days' => 1],
            // Khu vực lân cận
            ['province_name' => 'Bình Dương', 'fee' => 25000, 'estimated_days' => 1],
            ['province_name' => 'Đồng Nai', 'fee' => 25000, 'estimated_days' => 1],
            ['province_name' => 'Long An', 'fee' => 25000, 'estimated_days' => 2],
            ['province_name' => 'Tiền Giang', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Vĩnh Long', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Bà Rịa - Vũng Tàu', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Hưng Yên', 'fee' => 25000, 'estimated_days' => 1],
            ['province_name' => 'Hải Phòng', 'fee' => 30000, 'estimated_days' => 2],
            // Miền Bắc
            ['province_name' => 'Hải Dương', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Bắc Ninh', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Vĩnh Phúc', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Thái Nguyên', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Phú Thọ', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Tuyên Quang', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Yên Bái', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Lào Cai', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Sơn La', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Điện Biên', 'fee' => 50000, 'estimated_days' => 4],
            ['province_name' => 'Lai Châu', 'fee' => 50000, 'estimated_days' => 4],
            ['province_name' => 'Cao Bằng', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Lạng Sơn', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Bắc Giang', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Quảng Ninh', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Hòa Bình', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Nam Định', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Thái Bình', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Ninh Bình', 'fee' => 35000, 'estimated_days' => 2],
            // Miền Trung
            ['province_name' => 'Thanh Hóa', 'fee' => 35000, 'estimated_days' => 3],
            ['province_name' => 'Nghệ An', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Hà Tĩnh', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Quảng Bình', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Quảng Trị', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Thừa Thiên Huế', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Đà Nẵng', 'fee' => 35000, 'estimated_days' => 2],
            ['province_name' => 'Quảng Nam', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Quảng Ngãi', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Bình Định', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Phú Yên', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Khánh Hòa', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Ninh Thuận', 'fee' => 35000, 'estimated_days' => 3],
            ['province_name' => 'Bình Thuận', 'fee' => 35000, 'estimated_days' => 2],
            // Tây Nguyên
            ['province_name' => 'Kon Tum', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Gia Lai', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Đắk Lắk', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Đắk Nông', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Lâm Đồng', 'fee' => 40000, 'estimated_days' => 3],
            // Miền Nam
            ['province_name' => 'Bình Phước', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Tây Ninh', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Bến Tre', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Trà Vinh', 'fee' => 35000, 'estimated_days' => 3],
            ['province_name' => 'Sóc Trăng', 'fee' => 35000, 'estimated_days' => 3],
            ['province_name' => 'Bạc Liêu', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'Cà Mau', 'fee' => 45000, 'estimated_days' => 3],
            ['province_name' => 'Cần Thơ', 'fee' => 30000, 'estimated_days' => 2],
            ['province_name' => 'Hậu Giang', 'fee' => 35000, 'estimated_days' => 3],
            ['province_name' => 'Kiên Giang', 'fee' => 40000, 'estimated_days' => 3],
            ['province_name' => 'An Giang', 'fee' => 35000, 'estimated_days' => 3],
            ['province_name' => 'Đồng Tháp', 'fee' => 35000, 'estimated_days' => 3],
        ];

        foreach ($rates as $rate) {
            ShippingRate::updateOrCreate(
                ['province_name' => $rate['province_name']],
                $rate
            );
        }
    }
}
