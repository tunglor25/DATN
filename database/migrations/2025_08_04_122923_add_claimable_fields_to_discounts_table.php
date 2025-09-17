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
        Schema::table('discounts', function (Blueprint $table) {
            $table->boolean('is_claimable')->default(false)->after('expires_at'); // Có thể nhận không
            $table->integer('claim_limit')->nullable()->after('is_claimable'); // Giới hạn số người nhận
            $table->integer('claimed_count')->default(0)->after('claim_limit'); // Số người đã nhận
            $table->text('description')->nullable()->after('claimed_count'); // Mô tả mã giảm giá
            $table->string('image')->nullable()->after('description'); // Hình ảnh mã giảm giá
            $table->boolean('is_active')->default(true)->after('image'); // Trạng thái hoạt động
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['is_claimable', 'claim_limit', 'claimed_count', 'description', 'image', 'is_active']);
        });
    }
};
