<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:cancel-expired {--minutes=10 : Số phút timeout}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động hủy các payment VNPay đã hết hạn sau 10 phút';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minutes = $this->option('minutes');
        $cutoffTime = now()->subMinutes($minutes);

        $this->info("Đang tìm kiếm các payment VNPay đã hết hạn sau {$minutes} phút...");

        // Tìm các payment VNPay đang pending và đã hết hạn
        $expiredPayments = Payment::where('payment_gateway', 'VNPAY')
            ->where('status', 'pending')
            ->where(function($query) use ($cutoffTime) {
                $query->where('expires_at', '<', now()) // Ưu tiên sử dụng expires_at
                      ->orWhere(function($q) use ($cutoffTime) {
                          $q->whereNull('expires_at') // Fallback cho payment cũ không có expires_at
                            ->where('created_at', '<', $cutoffTime);
                      });
            })
            ->with('order')
            ->get();

        if ($expiredPayments->isEmpty()) {
            $this->info('Không có payment nào cần hủy.');
            return 0;
        }

        $this->info("Tìm thấy {$expiredPayments->count()} payment cần hủy.");

        $cancelledCount = 0;
        $errors = [];

        foreach ($expiredPayments as $payment) {
            try {
                // Cập nhật payment
                $payment->update([
                    'status' => 'cancelled',
                    'response_code' => 'timeout',
                    'response_message' => "Giao dịch hết thời gian chờ ({$minutes} phút)"
                ]);
                
                // Cập nhật order - hủy toàn bộ đơn hàng
                $payment->order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'failed'
                ]);

                $cancelledCount++;
                
                $this->line("✓ Đã hủy payment #{$payment->id} và đơn hàng #{$payment->order->order_number}");
                
                // Log để theo dõi
                Log::info("Auto cancelled expired payment and order", [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'order_number' => $payment->order->order_number,
                    'amount' => $payment->amount,
                    'created_at' => $payment->created_at,
                    'cancelled_at' => now()
                ]);

            } catch (\Exception $e) {
                $errors[] = "Lỗi khi hủy payment #{$payment->id}: " . $e->getMessage();
                $this->error("✗ Lỗi khi hủy payment #{$payment->id}: " . $e->getMessage());
            }
        }

        $this->info("Hoàn thành! Đã hủy {$cancelledCount} payment và đơn hàng tương ứng.");

        if (!empty($errors)) {
            $this->warn("Có " . count($errors) . " lỗi xảy ra:");
            foreach ($errors as $error) {
                $this->line("- {$error}");
            }
        }

        return 0;
    }
} 