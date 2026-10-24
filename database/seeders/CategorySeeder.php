<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Điện thoại & Phụ kiện',
                'slug'        => 'dien-thoai-phu-kien',
                'description' => 'Điện thoại thông minh, ốp lưng, tai nghe, sạc và các phụ kiện công nghệ.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Thời trang',
                'slug'        => 'thoi-trang',
                'description' => 'Quần áo, giày dép, túi xách nam nữ theo xu hướng mới nhất.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Đồ gia dụng',
                'slug'        => 'do-gia-dung',
                'description' => 'Thiết bị nhà bếp, dụng cụ vệ sinh, đồ dùng sinh hoạt hàng ngày.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Sách & Văn phòng phẩm',
                'slug'        => 'sach-van-phong-pham',
                'description' => 'Sách giáo khoa, sách tham khảo, truyện, bút, vở và đồ dùng học tập.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Mỹ phẩm & Sức khỏe',
                'slug'        => 'my-pham-suc-khoe',
                'description' => 'Sản phẩm chăm sóc da, tóc, thực phẩm chức năng, dụng cụ y tế.',
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
