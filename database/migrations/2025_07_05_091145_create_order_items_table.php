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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('product_name'); // Tên sản phẩm tại thời điểm mua
            $table->string('product_image')->nullable(); // Ảnh sản phẩm
            $table->string('variant_sku')->nullable(); // SKU biến thể
            $table->decimal('price', 10, 2); // Giá tại thời điểm mua
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2); // Tổng tiền item
            $table->json('variant_attributes')->nullable(); // Thuộc tính biến thể
            $table->timestamps();

            // Indexes cho hiệu suất
            $table->index(['order_id']);
            $table->index(['product_variant_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
