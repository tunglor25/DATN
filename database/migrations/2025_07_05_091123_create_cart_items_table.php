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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')->constrained()->onDelete('cascade');

            // Cột product_id (nullable để phù hợp với sản phẩm không có biến thể)
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');

            // Cột product_variant_id (nullable để hỗ trợ sản phẩm không có biến thể)
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');

            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Giá tại thời điểm thêm vào giỏ
            $table->timestamps();

            // Unique constraint để tránh trùng lặp sản phẩm theo cart + product + variant
            $table->unique(['cart_id', 'product_id', 'product_variant_id'], 'cart_items_unique');

            // Indexes
            $table->index(['cart_id']);
            $table->index(['product_id']);
            $table->index(['product_variant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
