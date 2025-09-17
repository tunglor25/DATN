<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelExpiredVnPayOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired-vnpay {--minutes=10 : Số phút timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hủy các đơn hàng VNPay có payment cancelled nhưng order vẫn pending và đã hết hạn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = $this->option('minutes');
        $cutoffTime = now()->subMinutes($minutes);

        $this->info("Đang tìm kiếm các đơn hàng VNPay đã hết hạn sau {$minutes} phút...");

        // Tìm các đơn hàng VNPay có payment cancelled nhưng order vẫn pending và đã hết hạn
        $expiredOrders = Order::where('payment_method', 'vnpay')
            ->where('payment_status', 'pending')
            ->where('status', 'pending')
            ->whereHas('payments', function($query) use ($cutoffTime) {
                $query->where('payment_gateway', 'VNPAY')
                      ->where('status', 'cancelled')
                      ->where(function($q) use ($cutoffTime) {
                          $q->where('expires_at', '<', now()) // Ưu tiên sử dụng expires_at
                            ->orWhere(function($subQ) use ($cutoffTime) {
                                $subQ->whereNull('expires_at') // Fallback cho payment cũ không có expires_at
                                     ->where('created_at', '<', $cutoffTime);
                            });
                      });
            })
            ->with(['payments' => function($query) {
                $query->where('payment_gateway', 'VNPAY')
                      ->orderBy('created_at', 'desc');
            }])
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Không có đơn hàng nào cần hủy.');
            return 0;
        }

        $this->info("Tìm thấy {$expiredOrders->count()} đơn hàng cần hủy.");

        $cancelledCount = 0;
        $errors = [];

        foreach ($expiredOrders as $order) {
            try {
                // Lấy payment gần nhất
                $latestPayment = $order->payments->first();
                
                // Cập nhật order - hủy đơn hàng
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed',
                    'notes' => ($order->notes ? $order->notes . "\n" : "") . 
                        "Tự động hủy đơn hàng do thanh toán hết hạn ({$minutes} phút). " . now()->format('d/m/Y H:i')
                ]);

                $cancelledCount++;
                
                $this->line("✓ Đã hủy đơn hàng #{$order->order_number} (Payment ID: {$latestPayment->id})");
                
                // Log để theo dõi
                Log::info("Auto cancelled expired VNPay order", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'payment_id' => $latestPayment->id,
                    'payment_status' => $latestPayment->status,
                    'response_code' => $latestPayment->response_code,
                    'created_at' => $order->created_at,
                    'expires_at' => $latestPayment->expires_at,
                    'cancelled_at' => now()
                ]);

            } catch (\Exception $e) {
                $errors[] = "Lỗi khi hủy đơn hàng #{$order->order_number}: " . $e->getMessage();
                $this->error("✗ Lỗi khi hủy đơn hàng #{$order->order_number}: " . $e->getMessage());
            }
        }

        $this->info("Hoàn thành! Đã hủy {$cancelledCount} đơn hàng.");

        if (!empty($errors)) {
            $this->warn("Có " . count($errors) . " lỗi xảy ra:");
            foreach ($errors as $error) {
                $this->line("- {$error}");
            }
        }

        return 0;
    }
}
