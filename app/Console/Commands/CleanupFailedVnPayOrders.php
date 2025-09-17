<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class CleanupFailedVnPayOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cleanup-failed-vnpay {--hours=24 : Số giờ chờ trước khi cleanup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup VNPay orders with failed payment status but still pending order status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = now()->subHours($hours);

        $this->info("Đang tìm kiếm các đơn hàng VNPay có payment_status = 'failed' nhưng order status vẫn = 'pending' sau {$hours} giờ...");

        // Tìm các đơn hàng VNPay có payment_status = 'failed' nhưng order status vẫn = 'pending'
        $failedOrders = Order::where('payment_method', 'vnpay')
            ->where('payment_status', 'failed')
            ->where('status', 'pending')
            ->where('created_at', '<', $cutoffTime)
            ->with(['payments' => function($query) {
                $query->where('payment_gateway', 'VNPAY')
                      ->where('status', 'failed');
            }])
            ->get();

        if ($failedOrders->isEmpty()) {
            $this->info('Không có đơn hàng nào cần cleanup.');
            return 0;
        }

        $this->info("Tìm thấy {$failedOrders->count()} đơn hàng cần cleanup.");

        $cleanedCount = 0;
        $errors = [];

        foreach ($failedOrders as $order) {
            try {
                // Cập nhật order status thành cancelled
                $order->update([
                    'status' => 'cancelled',
                    'notes' => ($order->notes ? $order->notes . "\n" : "") . 
                        "Tự động hủy đơn hàng do thanh toán thất bại sau {$hours} giờ. " . now()->format('d/m/Y H:i')
                ]);

                // Cập nhật payment status thành cancelled nếu chưa phải
                foreach ($order->payments as $payment) {
                    if ($payment->status === 'failed') {
                        $payment->update([
                            'status' => 'cancelled',
                            'response_message' => "Auto cancelled after {$hours} hours due to failed payment"
                        ]);
                    }
                }

                $cleanedCount++;
                
                $this->line("✓ Đã cleanup đơn hàng #{$order->order_number}");
                
                // Log để theo dõi
                Log::info("Auto cleaned up failed VNPay order", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at,
                    'cleaned_at' => now(),
                    'waiting_hours' => $hours
                ]);

            } catch (\Exception $e) {
                $errors[] = "Lỗi khi cleanup đơn hàng #{$order->order_number}: " . $e->getMessage();
                $this->error("✗ Lỗi khi cleanup đơn hàng #{$order->order_number}: " . $e->getMessage());
            }
        }

        $this->info("Hoàn thành! Đã cleanup {$cleanedCount} đơn hàng.");

        if (!empty($errors)) {
            $this->warn("Có " . count($errors) . " lỗi xảy ra:");
            foreach ($errors as $error) {
                $this->line("- {$error}");
            }
        }

        return 0;
    }
}
