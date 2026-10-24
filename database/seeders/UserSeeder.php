<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@ctmart.vn'],
            [
                'name'      => 'Quản trị viên CT mart',
                'email'     => 'admin@ctmart.vn',
                'password'  => Hash::make('Admin@123456'),
                'phone'     => '0900000001',
                'role'      => UserRole::ADMIN,
                'is_active' => true,
            ]
        );

        // Khách hàng mẫu
        $customers = [
            ['name' => 'Nguyễn Văn An', 'email' => 'an.nguyen@email.com', 'phone' => '0912345678'],
            ['name' => 'Trần Thị Bình', 'email' => 'binh.tran@email.com', 'phone' => '0923456789'],
            ['name' => 'Lê Văn Cường', 'email' => 'cuong.le@email.com', 'phone' => '0934567890'],
            ['name' => 'Phạm Thị Dung', 'email' => 'dung.pham@email.com', 'phone' => '0945678901'],
            ['name' => 'Hoàng Văn Em', 'email' => 'em.hoang@email.com', 'phone' => '0956789012'],
            ['name' => 'Vũ Thị Phương', 'email' => 'phuong.vu@email.com', 'phone' => '0967890123'],
            ['name' => 'Đặng Văn Giang', 'email' => 'giang.dang@email.com', 'phone' => '0978901234'],
            ['name' => 'Ngô Thị Hoa', 'email' => 'hoa.ngo@email.com', 'phone' => '0989012345'],
            ['name' => 'Bùi Văn Hùng', 'email' => 'hung.bui@email.com', 'phone' => '0990123456'],
            ['name' => 'Đinh Thị Lan', 'email' => 'lan.dinh@email.com', 'phone' => '0901234567'],
        ];

        foreach ($customers as $c) {
            User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name'      => $c['name'],
                    'email'     => $c['email'],
                    'password'  => Hash::make('Customer@123'),
                    'phone'     => $c['phone'],
                    'role'      => UserRole::CUSTOMER,
                    'is_active' => true,
                ]
            );
        }
    }
}
