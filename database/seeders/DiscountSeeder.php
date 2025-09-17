<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        // Tạo các mã giảm giá có thể nhận
        Discount::create([
            'code' => 'WELCOME2024',
            'type' => 'fixed',
            'value' => 50000,
            'min_order_value' => 200000,
            'usage_limit' => 100,
            'used' => 0,
            'starts_at' => Carbon::now()->subDays(7),
            'expires_at' => Carbon::now()->addDays(30),
            'is_claimable' => true,
            'claim_limit' => 50,
            'claimed_count' => 0,
            'description' => 'Mã giảm giá chào mừng khách hàng mới',
            'is_active' => true,
        ]);

        Discount::create([
            'code' => 'SALE20',
            'type' => 'percent',
            'value' => 20,
            'min_order_value' => 300000,
            'usage_limit' => 200,
            'used' => 0,
            'starts_at' => Carbon::now()->subDays(3),
            'expires_at' => Carbon::now()->addDays(15),
            'is_claimable' => true,
            'claim_limit' => 100,
            'claimed_count' => 0,
            'description' => 'Giảm 20% cho đơn hàng từ 300k',
            'is_active' => true,
        ]);

        Discount::create([
            'code' => 'FREESHIP',
            'type' => 'fixed',
            'value' => 30000,
            'min_order_value' => 0,
            'usage_limit' => 500,
            'used' => 0,
            'starts_at' => Carbon::now()->subDays(1),
            'expires_at' => Carbon::now()->addDays(7),
            'is_claimable' => true,
            'claim_limit' => 300,
            'claimed_count' => 0,
            'description' => 'Miễn phí vận chuyển cho mọi đơn hàng',
            'is_active' => true,
        ]);

        // Tạo một mã giảm giá không thể nhận (để test)
        Discount::create([
            'code' => 'EXPIRED',
            'type' => 'fixed',
            'value' => 10000,
            'min_order_value' => 100000,
            'usage_limit' => 50,
            'used' => 0,
            'starts_at' => Carbon::now()->subDays(10),
            'expires_at' => Carbon::now()->subDays(1), // Đã hết hạn
            'is_claimable' => true,
            'claim_limit' => 25,
            'claimed_count' => 0,
            'description' => 'Mã giảm giá đã hết hạn',
            'is_active' => true,
        ]);

        $this->command->info('Discount seeder completed!');
    }
}
