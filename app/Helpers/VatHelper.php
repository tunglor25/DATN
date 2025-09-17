<?php

namespace App\Helpers;

class VatHelper
{
    /**
     * Tính VAT dựa trên giá trị và tỷ lệ VAT
     *
     * @param float $amount Giá trị cần tính VAT
     * @param float|null $rate Tỷ lệ VAT (mặc định lấy từ config)
     * @return float
     */
    public static function calculateVat($amount, $rate = null)
    {
        if ($rate === null) {
            $rate = config('app.vat_rate', 10) / 100;
        }
        
        return $amount * $rate;
    }

    /**
     * Tính tổng tiền bao gồm VAT
     *
     * @param float $amount Giá trị gốc
     * @param float|null $rate Tỷ lệ VAT
     * @return float
     */
    public static function calculateTotalWithVat($amount, $rate = null)
    {
        $vat = self::calculateVat($amount, $rate);
        return $amount + $vat;
    }

    /**
     * Tính giá trị gốc từ tổng tiền bao gồm VAT
     *
     * @param float $totalWithVat Tổng tiền bao gồm VAT
     * @param float|null $rate Tỷ lệ VAT
     * @return float
     */
    public static function calculateOriginalAmount($totalWithVat, $rate = null)
    {
        if ($rate === null) {
            $rate = config('app.vat_rate', 10) / 100;
        }
        
        return $totalWithVat / (1 + $rate);
    }

    /**
     * Format số tiền theo định dạng Việt Nam
     *
     * @param float $amount
     * @return string
     */
    public static function formatCurrency($amount)
    {
        return number_format($amount, 0, ',', '.') . 'đ';
    }
} 