# Hướng dẫn Setup Cron Job cho VNPay Timeout

## Mô tả
Tự động hủy các payment VNPay đã hết hạn sau 10 phút nếu không hoàn tất thanh toán.

## Cài đặt Cron Job

### 1. Mở crontab
```bash
crontab -e
```

### 2. Thêm dòng sau (chạy mỗi phút)
```bash
* * * * * cd /path/to/your/project && php artisan payments:cancel-expired >> /dev/null 2>&1
```

### 3. Hoặc chạy mỗi 2 phút (để giảm tải)
```bash
*/2 * * * * cd /path/to/your/project && php artisan payments:cancel-expired >> /dev/null 2>&1
```

### 4. Cleanup đơn hàng thất bại (chạy mỗi 6 giờ)
```bash
0 */6 * * * cd /path/to/your/project && php artisan orders:cleanup-failed-vnpay >> /dev/null 2>&1
```

### 5. Hủy đơn hàng VNPay hết hạn (chạy mỗi 5 phút)
```bash
*/5 * * * * cd /path/to/your/project && php artisan orders:cancel-expired-vnpay >> /dev/null 2>&1
```

## Test Command

### Chạy thủ công
```bash
php artisan payments:cancel-expired
```

### Chạy với tham số tùy chỉnh
```bash
php artisan payments:cancel-expired --minutes=10
```

### Cleanup đơn hàng thất bại
```bash
php artisan orders:cleanup-failed-vnpay
```

### Cleanup đơn hàng thất bại với tham số tùy chỉnh
```bash
php artisan orders:cleanup-failed-vnpay --hours=12
```

### Hủy đơn hàng VNPay hết hạn
```bash
php artisan orders:cancel-expired-vnpay
```

### Hủy đơn hàng VNPay hết hạn với tham số tùy chỉnh
```bash
php artisan orders:cancel-expired-vnpay --minutes=15
```

### Xem log
```bash
tail -f storage/logs/laravel.log
```

## Luồng hoạt động

### Payment Timeout (10 phút)
1. **Tạo payment**: Khi user chọn VNPay, tạo payment với `expires_at = now() + 10 phút`
2. **Cron job**: Chạy mỗi phút, tìm payment VNPay pending và đã quá 10 phút
3. **Auto cancel**: Cập nhật payment status = 'cancelled' và order payment_status = 'failed'
4. **Log**: Ghi log để theo dõi

### User Cancel vs System Error
1. **User Cancel**: Response codes '01', '05' → Payment record status = 'cancelled', Order payment_status = 'pending' (giữ nguyên)
2. **System Error**: Response codes khác → Payment record status = 'failed', Order payment_status = 'failed'
3. **Cleanup**: Sau 24 giờ, tự động hủy đơn hàng có payment_status = 'failed' nhưng order status vẫn = 'pending'

## API Endpoints

### Tạo payment
```
POST /vnpay/create-payment
Response: {
    "success": true,
    "payment_url": "...",
    "payment_id": 123,
    "timeout_minutes": 10,
    "expires_at": "2025-08-01 15:30:00",
    "message": "Lưu ý: Giao dịch sẽ tự động hủy sau 10 phút..."
}
```

### Kiểm tra trạng thái
```
GET /vnpay/check-status?payment_id=123
Response: {
    "status": "pending",
    "payment_status": "pending",
    "is_expired": false,
    "remaining_minutes": 3,
    "expires_at": "2025-08-01 15:30:00"
}
```

## Monitoring

### Kiểm tra cron job có chạy không
```bash
# Xem cron job đang chạy
ps aux | grep cron

# Xem log cron
tail -f /var/log/cron
```

### Kiểm tra command có hoạt động không
```bash
# Tạo test payment
php artisan tinker
>>> $payment = App\Models\Payment::create(['order_id' => 1, 'payment_gateway' => 'VNPAY', 'amount' => 100000, 'status' => 'pending', 'created_at' => now()->subMinutes(11)]);

# Chạy command
php artisan payments:cancel-expired
```

## Troubleshooting

### Cron job không chạy
1. Kiểm tra cron service: `systemctl status cron`
2. Kiểm tra quyền: `chmod +x /path/to/artisan`
3. Kiểm tra đường dẫn: Đảm bảo đường dẫn chính xác

### Command không tìm thấy payment
1. Kiểm tra database connection
2. Kiểm tra payment_gateway = 'VNPAY'
3. Kiểm tra status = 'pending'
4. Kiểm tra created_at < now() - 5 phút

### Log không ghi
1. Kiểm tra quyền ghi log: `chmod 775 storage/logs`
2. Kiểm tra disk space: `df -h`
3. Kiểm tra log level trong .env 