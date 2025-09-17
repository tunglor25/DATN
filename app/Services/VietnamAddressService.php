<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class VietnamAddressService
{
    private const API_BASE_URL = 'https://vietnamlabs.com/api';
    private const CACHE_TTL = 86400; // 24 giờ

    /**
     * Lấy danh sách tất cả tỉnh/thành phố
     */
    public function getProvinces(): array
    {
        return Cache::remember('vietnam_provinces', self::CACHE_TTL, function () {
            $response = Http::get(self::API_BASE_URL . '/vietnamprovince');
            
            if ($response->successful()) {
                $data = $response->json();
                
                \Log::info('Raw provinces response:', ['data' => $data]);
                
                // Kiểm tra cấu trúc dữ liệu thực tế
                if (isset($data['success']) && $data['success'] && isset($data['data']) && is_array($data['data'])) {
                    return collect($data['data'])->map(function ($province) {
                        return [
                            'code' => $province['id'] ?? '',
                            'name' => $province['province'] ?? '',
                        ];
                    })->toArray();
                }
            }
            
            \Log::error('Failed to get provinces', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return [];
        });
    }

    /**
     * Lấy danh sách xã/phường theo tên tỉnh/thành phố
     */
    public function getWards(string $provinceName): array
    {
        // Tạm thời bỏ cache để debug
        // return Cache::remember("vietnam_wards_{$provinceName}", self::CACHE_TTL, function () use ($provinceName) {
        
        // Test trực tiếp API để xem cấu trúc dữ liệu
        $response = Http::get(self::API_BASE_URL . "/vietnamprovince", [
            'province' => $provinceName
        ]);
        
        \Log::info('API call for ' . $provinceName, [
            'url' => self::API_BASE_URL . "/vietnamprovince",
            'params' => ['province' => $provinceName],
            'status' => $response->status(),
            'raw_body' => $response->body()
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            
            \Log::info('Raw wards response for ' . $provinceName, ['data' => $data]);
            
            // Kiểm tra cấu trúc dữ liệu thực tế
            if (isset($data['success']) && $data['success'] && isset($data['data'])) {
                // API trả về object đơn lẻ, không phải mảng
                $province = $data['data'];
                
                \Log::info('Found province for ' . $provinceName, ['province' => $province]);
                
                if ($province && isset($province['wards']) && is_array($province['wards'])) {
                    $wards = collect($province['wards'])->map(function ($ward, $index) {
                        // Tạo code ngắn từ name, giới hạn 10 ký tự
                        $name = $ward['name'] ?? '';
                        $code = substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 10);
                        
                        // Nếu code rỗng hoặc quá ngắn, tạo code từ index
                        if (empty($code) || strlen($code) < 3) {
                            $code = 'W' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                        }
                        
                        return [
                            'code' => $code,
                            'name' => $name,
                        ];
                    })->toArray();
                    
                    \Log::info('Processed wards for ' . $provinceName, ['wards' => $wards]);
                    return $wards;
                } else {
                    \Log::warning('No wards found for ' . $provinceName, [
                        'found_province' => $province,
                        'has_wards' => $province ? isset($province['wards']) : false
                    ]);
                }
            } else {
                \Log::warning('Invalid data structure for ' . $provinceName, [
                    'has_success' => isset($data['success']),
                    'success_value' => $data['success'] ?? null,
                    'has_data' => isset($data['data']),
                    'data_type' => gettype($data['data'] ?? null)
                ]);
            }
        }
        
        \Log::error('Failed to get wards for ' . $provinceName, [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        return [];
        // });
    }

    /**
     * Lấy danh sách xã/phường theo mã tỉnh/thành phố
     */
    public function getWardsByCode(string $provinceCode): array
    {
        // Trước tiên lấy tên tỉnh từ mã
        $provinces = $this->getProvinces();
        $province = collect($provinces)->firstWhere('code', $provinceCode);
        
        if (!$province) {
            return [];
        }
        
        return $this->getWards($province['name']);
    }
}
