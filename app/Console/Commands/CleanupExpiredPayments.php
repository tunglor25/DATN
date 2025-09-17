<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CleanupExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired VNPay payments and restore items to cart';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired VNPay payments...');

        // Tìm tất cả payment VNPay đã hết hạn và chưa được xử lý
        $expiredPayments = Payment::where('payment_gateway', 'VNPAY')
            ->where('status', 'pending')
            ->where('expires_at', '<', now())
            ->with(['order.user.cart'])
            ->get();

        $this->info("Found {$expiredPayments->count()} expired payments to process.");

        $processedCount = 0;
        $errorCount = 0;

        foreach ($expiredPayments as $payment) {
            try {
                $this->processExpiredPayment($payment);
                $processedCount++;
                $this->info("Processed payment ID: {$payment->id} for order: {$payment->order->order_number}");
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Error processing payment ID: {$payment->id} - {$e->getMessage()}");
                Log::error("Error processing expired payment", [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Cleanup completed. Processed: {$processedCount}, Errors: {$errorCount}");
    }

    /**
     * Xử lý một payment hết hạn
     */
    private function processExpiredPayment(Payment $payment)
    {
        $order = $payment->order;

        // Cập nhật trạng thái payment
        $payment->update([
            'status' => 'expired',
            'response_message' => 'Payment expired automatically'
        ]);

        // Cập nhật trạng thái đơn hàng nếu cần
        if ($order->payment_status === 'pending') {
            $order->update(['payment_status' => 'failed']);
        }

        // Không cần khôi phục sản phẩm nữa vì đã xóa từ trước
        // $this->restoreItemsToCart($order);

        // Ghi log
        Log::info("Expired payment processed", [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'user_id' => $order->user_id
        ]);
    }


}
