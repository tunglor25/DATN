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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('response_code')->nullable()->after('status');
            $table->text('response_message')->nullable()->after('response_code');
            $table->string('bank_code')->nullable()->after('response_message');
            $table->json('payment_data')->nullable()->after('bank_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['response_code', 'response_message', 'bank_code', 'payment_data']);
        });
    }
};
