<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::pluck('id', 'slug');

        $products = [
            // ───── Điện thoại & Phụ kiện ─────
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name'          => 'Samsung Galaxy A55 5G 8GB/128GB',
                'price'         => 9490000,
                'original_price'=> 10490000,
                'stock_quantity'=> 50,
                'is_featured'   => true,
                'description'   => 'Điện thoại Samsung Galaxy A55 5G với màn hình Super AMOLED 6.6 inch, camera 50MP, pin 5000mAh, hỗ trợ mạng 5G tốc độ cao.',
                'sku'           => 'SAM-A55-5G-8-128',
            ],
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name'          => 'iPhone 15 128GB',
                'price'         => 22990000,
                'original_price'=> 24990000,
                'stock_quantity'=> 30,
                'is_featured'   => true,
                'description'   => 'iPhone 15 với chip A16 Bionic, màn hình Super Retina XDR 6.1 inch, hệ thống camera kép 48MP, thiết kế Dynamic Island.',
                'sku'           => 'IPH-15-128GB',
            ],
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name'          => 'Xiaomi Redmi Note 13 Pro 8GB/256GB',
                'price'         => 7190000,
                'original_price'=> 7990000,
                'stock_quantity'=> 45,
                'is_featured'   => false,
                'description'   => 'Redmi Note 13 Pro với camera 200MP, màn hình AMOLED 6.67 inch 120Hz, pin 5100mAh, sạc nhanh 67W.',
                'sku'           => 'XIA-RDNT13P-8-256',
            ],
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name'          => 'Tai nghe Bluetooth Sony WH-1000XM5',
                'price'         => 6990000,
                'original_price'=> 8490000,
                'stock_quantity'=> 25,
                'is_featured'   => true,
                'description'   => 'Tai nghe chống ồn chủ động hàng đầu thế giới, âm thanh Hi-Res, pin 30 giờ, kết nối multipoint.',
                'sku'           => 'SONY-WH1000XM5',
            ],
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name'          => 'Sạc nhanh Baseus 65W GaN',
                'price'         => 450000,
                'original_price'=> 599000,
                'stock_quantity'=> 100,
                'is_featured'   => false,
                'description'   => 'Củ sạc GaN 65W 3 cổng (2 USB-C + 1 USB-A), hỗ trợ PD, QC 3.0, nhỏ gọn hơn 40% so với sạc thông thường.',
                'sku'           => 'BAS-GAN65W',
            ],
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name'          => 'Ốp lưng chống sốc UAG Pathfinder iPhone 15',
                'price'         => 890000,
                'original_price'=> 1090000,
                'stock_quantity'=> 60,
                'is_featured'   => false,
                'description'   => 'Ốp lưng chống sốc quân đội MIL-STD-810G cho iPhone 15, thiết kế gọn nhẹ, chống rơi từ 1.5m.',
                'sku'           => 'UAG-PATH-IPH15',
            ],
            // ───── Thời trang ─────
            [
                'category_slug' => 'thoi-trang',
                'name'          => 'Áo thun Unisex Premium Cotton',
                'price'         => 199000,
                'original_price'=> 299000,
                'stock_quantity'=> 200,
                'is_featured'   => true,
                'description'   => 'Áo thun chất liệu cotton 100% cao cấp, form rộng unisex, nhiều màu sắc, co giãn tốt, thấm hút mồ hôi hiệu quả.',
                'sku'           => 'AT-UNISEX-PREM-CT',
            ],
            [
                'category_slug' => 'thoi-trang',
                'name'          => 'Quần Jean Nam Slim Fit',
                'price'         => 459000,
                'original_price'=> 599000,
                'stock_quantity'=> 80,
                'is_featured'   => false,
                'description'   => 'Quần jean nam denim cao cấp, form slim fit ôm vừa, chất liệu co giãn 4 chiều, màu xanh đậm cơ bản.',
                'sku'           => 'QJ-NAM-SLIM-001',
            ],
            [
                'category_slug' => 'thoi-trang',
                'name'          => 'Giày Sneaker Nữ Trắng Basic',
                'price'         => 359000,
                'original_price'=> 499000,
                'stock_quantity'=> 60,
                'is_featured'   => true,
                'description'   => 'Giày sneaker nữ màu trắng cơ bản, đế cao su chống trượt, chất liệu da PU mềm, phù hợp nhiều phong cách.',
                'sku'           => 'GN-NU-TRANG-BASIC',
            ],
            [
                'category_slug' => 'thoi-trang',
                'name'          => 'Túi Tote Canvas Unisex CT mart',
                'price'         => 149000,
                'original_price'=> 199000,
                'stock_quantity'=> 150,
                'is_featured'   => false,
                'description'   => 'Túi tote canvas cotton dày dặn, dung tích lớn, phù hợp đi học, đi làm, mua sắm. Có nhiều màu.',
                'sku'           => 'TT-CANVAS-CTM',
            ],
            [
                'category_slug' => 'thoi-trang',
                'name'          => 'Váy Liền Maxi Hoa Nhí Nữ',
                'price'         => 389000,
                'original_price'=> 499000,
                'stock_quantity'=> 40,
                'is_featured'   => false,
                'description'   => 'Váy maxi họa tiết hoa nhí dịu dàng, chất liệu lụa mềm mát, thiết kế cổ V thanh lịch, phù hợp mùa hè.',
                'sku'           => 'VM-MAXI-HOA-001',
            ],
            // ───── Đồ gia dụng ─────
            [
                'category_slug' => 'do-gia-dung',
                'name'          => 'Nồi Chiên Không Dầu Philips 4.1L HD9252',
                'price'         => 2890000,
                'original_price'=> 3590000,
                'stock_quantity'=> 30,
                'is_featured'   => true,
                'description'   => 'Nồi chiên không dầu Philips công suất 1400W, dung tích 4.1L, hẹn giờ 30 phút, điều chỉnh nhiệt độ 80-200°C.',
                'sku'           => 'PHIL-HD9252',
            ],
            [
                'category_slug' => 'do-gia-dung',
                'name'          => 'Máy Xay Sinh Tố Sunhouse SHB5122',
                'price'         => 590000,
                'original_price'=> 790000,
                'stock_quantity'=> 50,
                'is_featured'   => false,
                'description'   => 'Máy xay sinh tố Sunhouse công suất 600W, 3 cấp độ xay, bình thủy tinh 1.5L, an toàn BPA-free.',
                'sku'           => 'SHB-SHB5122',
            ],
            [
                'category_slug' => 'do-gia-dung',
                'name'          => 'Chảo chống dính Elmich 28cm',
                'price'         => 390000,
                'original_price'=> 490000,
                'stock_quantity'=> 70,
                'is_featured'   => false,
                'description'   => 'Chảo chống dính Elmich đường kính 28cm, lớp phủ ceramic không độc hại, tay cầm chịu nhiệt, dùng được tất cả bếp.',
                'sku'           => 'ELM-CHAO-28CM',
            ],
            [
                'category_slug' => 'do-gia-dung',
                'name'          => 'Bộ Dao Inox 5 Món Classic',
                'price'         => 290000,
                'original_price'=> 390000,
                'stock_quantity'=> 80,
                'is_featured'   => false,
                'description'   => 'Bộ dao inox 5 món gồm dao thái, dao chặt, dao gọt, kéo và thanh mài dao. Lưỡi inox không gỉ, sắc bén.',
                'sku'           => 'DAO-INOX-5M-CL',
            ],
            [
                'category_slug' => 'do-gia-dung',
                'name'          => 'Máy lọc không khí Xiaomi Mi 4 Lite',
                'price'         => 2190000,
                'original_price'=> 2690000,
                'stock_quantity'=> 20,
                'is_featured'   => true,
                'description'   => 'Máy lọc không khí Xiaomi diện tích 20m², lọc bụi mịn PM2.5, HEPA H13, kết nối app Mihome, chế độ auto.',
                'sku'           => 'XIA-MI4LITE-AIR',
            ],
            // ───── Sách & Văn phòng phẩm ─────
            [
                'category_slug' => 'sach-van-phong-pham',
                'name'          => 'Đắc Nhân Tâm - Dale Carnegie',
                'price'         => 68000,
                'original_price'=> 86000,
                'stock_quantity'=> 200,
                'is_featured'   => true,
                'description'   => 'Cuốn sách kinh điển về nghệ thuật giao tiếp và đắc nhân tâm, đã được dịch ra hơn 50 thứ tiếng trên thế giới.',
                'sku'           => 'SACH-DACNANTAM',
            ],
            [
                'category_slug' => 'sach-van-phong-pham',
                'name'          => 'Nhà Giả Kim - Paulo Coelho',
                'price'         => 79000,
                'original_price'=> 99000,
                'stock_quantity'=> 150,
                'is_featured'   => false,
                'description'   => 'Tác phẩm nổi tiếng nhất của Paulo Coelho về hành trình tìm kiếm ước mơ và vận mệnh bản thân.',
                'sku'           => 'SACH-NHAGIAKIM',
            ],
            [
                'category_slug' => 'sach-van-phong-pham',
                'name'          => 'Bộ Bút Gel Thiên Long TL-027 12 cây',
                'price'         => 38000,
                'original_price'=> 48000,
                'stock_quantity'=> 300,
                'is_featured'   => false,
                'description'   => 'Bộ bút gel Thiên Long 12 màu, mực gel chảy đều, không lem, đầu bút 0.5mm, phù hợp học sinh, sinh viên.',
                'sku'           => 'TL-GEL027-12M',
            ],
            [
                'category_slug' => 'sach-van-phong-pham',
                'name'          => 'Vở Kẻ Ngang Bìa Cứng A4 200 Trang',
                'price'         => 45000,
                'original_price'=> 55000,
                'stock_quantity'=> 500,
                'is_featured'   => false,
                'description'   => 'Vở kẻ ngang bìa cứng chất lượng cao, 200 trang, giấy trắng 70gsm không thấm mực, khổ A4.',
                'sku'           => 'VO-A4-BC-200T',
            ],
            [
                'category_slug' => 'sach-van-phong-pham',
                'name'          => 'Tư Duy Nhanh Và Chậm - Daniel Kahneman',
                'price'         => 118000,
                'original_price'=> 148000,
                'stock_quantity'=> 80,
                'is_featured'   => true,
                'description'   => 'Cuốn sách kinh điển về tâm lý học hành vi và nhận thức của người đoạt giải Nobel kinh tế Daniel Kahneman.',
                'sku'           => 'SACH-TUDUY-NHANH',
            ],
            // ───── Mỹ phẩm & Sức khỏe ─────
            [
                'category_slug' => 'my-pham-suc-khoe',
                'name'          => 'Kem Chống Nắng Anessa Perfect UV SPF50+ PA++++',
                'price'         => 395000,
                'original_price'=> 495000,
                'stock_quantity'=> 60,
                'is_featured'   => true,
                'description'   => 'Kem chống nắng Anessa SPF50+/PA++++ bền màu ngay cả khi đổ mồ hôi hoặc tiếp xúc nước, phổ rộng UVA/UVB.',
                'sku'           => 'ANS-PERFT-UV50PA',
            ],
            [
                'category_slug' => 'my-pham-suc-khoe',
                'name'          => 'Sữa Rửa Mặt CeraVe Hydrating 473ml',
                'price'         => 298000,
                'original_price'=> 369000,
                'stock_quantity'=> 80,
                'is_featured'   => false,
                'description'   => 'Sữa rửa mặt CeraVe dành cho da khô/thường, giàu ceramide và hyaluronic acid, dịu nhẹ, pH cân bằng.',
                'sku'           => 'CRV-HYD-SRM-473',
            ],
            [
                'category_slug' => 'my-pham-suc-khoe',
                'name'          => 'Vitamin C 1000mg Nature Made 100 viên',
                'price'         => 290000,
                'original_price'=> 350000,
                'stock_quantity'=> 100,
                'is_featured'   => false,
                'description'   => 'Viên uống Vitamin C 1000mg Nature Made hỗ trợ tăng cường đề kháng, chống oxy hóa, bảo vệ tế bào.',
                'sku'           => 'NM-VITC1000-100V',
            ],
            [
                'category_slug' => 'my-pham-suc-khoe',
                'name'          => 'Dầu Gội Đầu Clear Men Deep Cleanse 650ml',
                'price'         => 85000,
                'original_price'=> 108000,
                'stock_quantity'=> 200,
                'is_featured'   => false,
                'description'   => 'Dầu gội Clear Men Deep Cleanse loại bỏ gàu, bã nhờn sâu bên trong, hương thơm mát, dung tích 650ml.',
                'sku'           => 'CLR-MEN-DEEP-650',
            ],
            [
                'category_slug' => 'my-pham-suc-khoe',
                'name'          => 'Máy Đo Huyết Áp Omron HEM-7156',
                'price'         => 1290000,
                'original_price'=> 1590000,
                'stock_quantity'=> 25,
                'is_featured'   => true,
                'description'   => 'Máy đo huyết áp bắp tay Omron HEM-7156 tự động, lưu 60 kết quả, cảnh báo rung tim, màn hình LED lớn.',
                'sku'           => 'OMR-HEM7156',
            ],
        ];

        foreach ($products as $data) {
            $categoryId = $cats[$data['category_slug']] ?? null;
            if (! $categoryId) continue;

            $slug = Str::slug($data['name']);

            Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id'    => $categoryId,
                    'name'           => $data['name'],
                    'slug'           => $slug,
                    'description'    => $data['description'],
                    'price'          => $data['price'],
                    'original_price' => $data['original_price'],
                    'stock_quantity' => $data['stock_quantity'],
                    'sku'            => $data['sku'],
                    'is_active'      => true,
                    'is_featured'    => $data['is_featured'],
                ]
            );
        }
    }
}
