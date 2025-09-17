<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Mã đơn hàng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Bắt buộc có user
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 12, 2); // Tổng tiền hàng (chưa có discount)
            $table->decimal('tax_amount', 12, 2)->default(0); // Thuế
            $table->decimal('shipping_fee', 12, 2)->default(0); // Phí vận chuyển
            $table->decimal('discount_amount', 12, 2)->default(0); // Giảm giá
            $table->decimal('total_amount', 12, 2); // Tổng cộng
            $table->enum('payment_method', ['cod', 'bank_transfer', 'credit_card', 'momo', 'vnpay'])->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->text('shipping_address'); // Địa chỉ giao hàng
            $table->string('shipping_phone', 20);
            $table->string('shipping_name', 100); // Tên người nhận
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            // Indexes cho hiệu suất
            $table->index(['user_id', 'status']);
            $table->index('order_number');
            $table->index(['status']);
            $table->index(['payment_status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
