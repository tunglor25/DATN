<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class VNPayContinuePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_continue_vnpay_payment_for_valid_order()
    {
        // Tạo user
        $user = User::factory()->create();
        
        // Tạo đơn hàng VNPay pending
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_method' => 'vnpay',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        // Tạo payment VNPay chưa hết hạn
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_gateway' => 'VNPAY',
            'status' => 'pending',
            'expires_at' => now()->addMinutes(3),
        ]);

        $response = $this->actingAs($user)
            ->postJson('/vnpay/continue', [
                'order_id' => $order->id
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'payment_url',
                'payment_id',
                'expires_at',
                'remaining_minutes'
            ]);
    }

    public function test_user_cannot_continue_payment_for_expired_payment()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_method' => 'vnpay',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        // Tạo payment đã hết hạn
        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_gateway' => 'VNPAY',
            'status' => 'pending',
            'expires_at' => now()->subMinutes(1),
        ]);

        $response = $this->actingAs($user)
            ->postJson('/vnpay/continue', [
                'order_id' => $order->id
            ]);

        // Nên tạo payment mới thay vì báo lỗi
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_user_cannot_continue_payment_for_non_vnpay_order()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->postJson('/vnpay/continue', [
                'order_id' => $order->id
            ]);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Đơn hàng này không thể tiếp tục thanh toán VNPay']);
    }

    public function test_user_cannot_continue_payment_for_paid_order()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_method' => 'vnpay',
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)
            ->postJson('/vnpay/continue', [
                'order_id' => $order->id
            ]);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Đơn hàng này không thể tiếp tục thanh toán VNPay']);
    }

    public function test_user_cannot_continue_payment_for_other_user_order()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user1->id,
            'payment_method' => 'vnpay',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user2)
            ->postJson('/vnpay/continue', [
                'order_id' => $order->id
            ]);

        $response->assertStatus(403)
            ->assertJson(['error' => 'Không có quyền truy cập đơn hàng này']);
    }

    public function test_user_can_buy_again_for_delivered_order()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'delivered',
        ]);

        $response = $this->actingAs($user)
            ->post('/orders/' . $order->id . '/buy-again');

        $response->assertRedirect(route('checkout.index'))
            ->assertSessionHas('success', 'Đã tải lại đơn hàng! Bạn có thể tiếp tục thanh toán.');
    }

    public function test_user_can_buy_again_for_cancelled_order()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($user)
            ->post('/orders/' . $order->id . '/buy-again');

        $response->assertRedirect(route('checkout.index'))
            ->assertSessionHas('success', 'Đã tải lại đơn hàng! Bạn có thể tiếp tục thanh toán.');
    }

    public function test_user_cannot_buy_again_for_pending_order()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->post('/orders/' . $order->id . '/buy-again');

        $response->assertRedirect()
            ->assertSessionHas('error', 'Chỉ có thể mua lại đơn hàng đã giao hoặc đã hủy!');
    }

    public function test_user_cannot_buy_again_for_processing_order()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'processing',
        ]);

        $response = $this->actingAs($user)
            ->post('/orders/' . $order->id . '/buy-again');

        $response->assertRedirect()
            ->assertSessionHas('error', 'Chỉ có thể mua lại đơn hàng đã giao hoặc đã hủy!');
    }

    public function test_orders_page_has_no_cache_headers()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/orders');

        $response->assertStatus(200)
            ->assertHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0');
    }

    public function test_vnpay_continue_endpoint_returns_correct_response_structure()
    {
        $user = User::factory()->create();
        
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_method' => 'vnpay',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'payment_gateway' => 'VNPAY',
            'status' => 'pending',
            'expires_at' => now()->addMinutes(3),
        ]);

        $response = $this->actingAs($user)
            ->postJson('/vnpay/continue', [
                'order_id' => $order->id
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'payment_url',
                'payment_id',
                'expires_at',
                'remaining_minutes'
            ])
            ->assertJson(['success' => true]);
    }

    public function test_orders_page_handles_empty_orders_gracefully()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/orders');

        $response->assertStatus(200)
            ->assertSee('Không có đơn hàng nào');
    }

    public function test_orders_page_handles_null_order_data_gracefully()
    {
        $user = User::factory()->create();
        
        // Tạo một số đơn hàng hợp lệ
        Order::factory()->count(3)->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->get('/orders');

        $response->assertStatus(200)
            ->assertDontSee('Attempt to read property');
    }
}
