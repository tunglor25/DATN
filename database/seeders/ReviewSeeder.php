<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->take(10)->get();
        $products = Product::take(15)->get();
        $orders = Order::where('status', 'completed')->take(20)->get();

        $comments = [
            'Sản phẩm rất đẹp, chất lượng tốt!',
            'Giao hàng nhanh, đóng gói cẩn thận.',
            'Sản phẩm đúng như mô tả, rất hài lòng.',
            'Chất liệu tốt, giá cả hợp lý.',
            'Sản phẩm đẹp nhưng hơi nhỏ so với mong đợi.',
            'Rất thích sản phẩm này, sẽ mua thêm.',
            'Chất lượng tốt, đáng để mua.',
            'Sản phẩm đẹp, phù hợp với giá tiền.',
            'Giao hàng chậm một chút nhưng sản phẩm tốt.',
            'Rất hài lòng với sản phẩm này.',
            'Chất liệu bền, thiết kế đẹp.',
            'Sản phẩm tốt, giá cả phải chăng.',
            'Đóng gói đẹp, sản phẩm chất lượng.',
            'Rất thích, sẽ giới thiệu cho bạn bè.',
            'Sản phẩm đẹp, phù hợp với mọi lứa tuổi.',
        ];

        foreach ($users as $user) {
            // Tạo 1-3 reviews cho mỗi user
            $numReviews = rand(1, 3);
            
            for ($i = 0; $i < $numReviews; $i++) {
                $product = $products->random();
                $order = $orders->random();
                $rating = rand(3, 5); // Chỉ tạo reviews tốt để test
                $comment = $comments[array_rand($comments)];
                
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'images' => null, // Không có hình ảnh cho mẫu
                    'is_verified' => rand(0, 1), // Random trạng thái xác thực
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}

