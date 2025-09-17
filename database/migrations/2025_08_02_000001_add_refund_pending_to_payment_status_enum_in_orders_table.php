<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chỉ chạy với MySQL
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refund_pending', 'refunded') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Quay lại enum cũ
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending'");
    }
};