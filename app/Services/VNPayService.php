<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class VNPayService
{
    private $tmnCode;
    private $hashSecret;
    private $url;
    private $returnUrl;
    private $ipnUrl;

    public function __construct()
    {
        $this->tmnCode = "4YUP19I4";
        $this->hashSecret = "MDUIFDCRAKLNBPOFIAFNEKFRNMFBYEPX";
        $this->url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $this->returnUrl = route('vnpay.return');
        $this->ipnUrl = route('vnpay.ipn');
    }

    /**
     * Kiểm tra kết nối đến VNPay
     */
    public function checkConnection()
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 giây timeout
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 giây connect timeout
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            return [
                'success' => $httpCode == 200 && empty($error),
                'http_code' => $httpCode,
                'error' => $error,
                'response' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($orderId, $amount, $orderInfo = '')
    {
        $vnp_TxnRef = $orderId;
        $vnp_OrderInfo = $orderInfo ?: "Thanh toan don hang " . $orderId;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền nhân với 100
        $vnp_Locale = "vn";
        $vnp_IpAddr = request()->ip();
        $vnp_TmnCode = $this->tmnCode;
        $vnp_ReturnUrl = $this->returnUrl;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $this->url . "?" . $query;
        if (isset($this->hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực callback từ VNPay
     */
    public function verifyReturn($inputData)
    {
        if (isset($inputData['vnp_SecureHash'])) {
            $secureHash = $inputData['vnp_SecureHash'];
            unset($inputData['vnp_SecureHash']);
            unset($inputData['vnp_SecureHashType']);
            ksort($inputData);
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            $calculatedHash = hash_hmac('sha512', $hashData, $this->hashSecret);
            return $calculatedHash == $secureHash;
        }
        return false;
    }

    /**
     * Xử lý IPN (Instant Payment Notification)
     */
    public function verifyIPN($inputData)
    {
        return $this->verifyReturn($inputData);
    }

    /**
     * Lấy thông tin giao dịch từ response
     */
    public function getTransactionInfo($inputData)
    {
        return [
            'transaction_id' => $inputData['vnp_TransactionNo'] ?? null,
            'order_id' => $inputData['vnp_TxnRef'] ?? null,
            'amount' => isset($inputData['vnp_Amount']) ? $inputData['vnp_Amount'] / 100 : 0,
            'response_code' => $inputData['vnp_ResponseCode'] ?? null,
            'message' => $inputData['vnp_Message'] ?? null,
            'bank_code' => $inputData['vnp_BankCode'] ?? null,
            'pay_date' => isset($inputData['vnp_PayDate']) ? date('Y-m-d H:i:s', strtotime($inputData['vnp_PayDate'])) : null,
        ];
    }

    /**
     * Kiểm tra trạng thái giao dịch thành công
     */
    public function isSuccess($responseCode)
    {
        return $responseCode == '00';
    }

    /**
     * Kiểm tra xem user có chủ động hủy/quay lại không
     */
    public function isUserCancelled($responseCode)
    {
        // Các mã cho thấy user chủ động hủy/quay lại
        return in_array($responseCode, ['01', '05', '24']);
    }

    /**
     * Kiểm tra xem có phải lỗi hệ thống/thanh toán thất bại không
     */
    public function isSystemError($responseCode)
    {
        // Các mã cho thấy lỗi hệ thống/thanh toán thất bại
        return in_array($responseCode, ['02', '04', '07', '09', '10', '11', '12', '13', '51', '65', '75', '79', '99']);
    }

    /**
     * Lấy message tương ứng với response code
     */
    public function getResponseMessage($responseCode)
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '01' => 'Giao dịch chưa hoàn tất',
            '02' => 'Giao dịch bị lỗi',
            '04' => 'Giao dịch đảo (Khách hàng đã bị trừ tiền tại Ngân hàng nhưng GD chưa thành công ở VNPAY)',
            '05' => 'VNPAY đang xử lý',
            '06' => 'VNPAY đã gửi yêu cầu hoàn tiền sang Ngân hàng',
            '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường)',
            '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng',
            '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch',
            '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
            '13' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch',
            '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
            '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch',
            '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Giao dịch không thành công do: KH nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
            '99' => 'Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)',
        ];

        return $messages[$responseCode] ?? 'Mã lỗi không xác định';
    }
} 