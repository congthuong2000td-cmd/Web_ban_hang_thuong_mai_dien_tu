<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_name',
        'fee',
        'estimated_days',
    ];

    protected function casts(): array
    {
        return [
            'fee'            => 'integer',
            'estimated_days' => 'integer',
        ];
    }

    /**
     * Lấy phí vận chuyển theo tên tỉnh/thành.
     */
    public static function getFeeForProvince(?string $provinceName): int
    {
        if ($provinceName) {
            $rate = self::where('province_name', $provinceName)->first();
            if ($rate) {
                return $rate->fee;
            }
        }
        return 30000; // Mặc định 30,000₫
    }
}
