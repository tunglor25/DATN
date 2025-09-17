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
        Schema::create('user_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'used', 'expired'])->default('active');
            $table->timestamp('claimed_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            
            // Đảm bảo mỗi user chỉ nhận mỗi discount 1 lần
            $table->unique(['user_id', 'discount_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_discounts');
    }
};
