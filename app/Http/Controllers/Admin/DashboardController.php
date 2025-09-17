<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tham số lọc thời gian từ request
        $period = $request->get('period', 'all_time'); // Mặc định tất cả thời gian
        $startDate = $this->getStartDate($period);
        $endDate = $this->getEndDate($period);

        // Chart data
        $totalSalesChart = $this->getTotalSalesChart($period);
        $monthlyStatistics = $this->getMonthlyStatistics($startDate, $endDate);
        $inventoryByCategory = $this->getInventoryByCategory();

        // Recent data
        $recentUsers = $this->getRecentUsers();
        $recentProducts = $this->getRecentProducts();
        $topSellingProducts = $this->getTopSellingProducts();
        $topCategories = $this->getTopCategoriesWithProducts();
        $pendingOrders = $this->getPendingOrders();
        $lowStockProducts = $this->getLowStockProducts();

        // Key Metrics
        $totalOrders = $this->getTotalOrders($startDate, $endDate);
        $totalRevenue = $this->getTotalRevenue($startDate, $endDate);
        $totalRefunds = $this->getTotalRefunds($startDate, $endDate);
        $activeUsers = $this->getActiveUsers($startDate, $endDate);

        // Growth percentages
        $ordersGrowth = $this->getOrdersGrowth($startDate, $endDate);
        $revenueGrowth = $this->getRevenueGrowth($startDate, $endDate);
        $refundsGrowth = $this->getRefundsGrowth($startDate, $endDate);
        $usersGrowth = $this->getUsersGrowth($startDate, $endDate);

        // Lấy danh sách các tháng có dữ liệu để hiển thị trong dropdown
        $availableMonths = $this->getAvailableMonths();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalRefunds', 'activeUsers',
            'ordersGrowth', 'revenueGrowth', 'refundsGrowth', 'usersGrowth',
            'totalSalesChart', 'monthlyStatistics', 'inventoryByCategory',
            'recentUsers', 'recentProducts', 'topSellingProducts', 'topCategories',
            'pendingOrders', 'lowStockProducts', 'period', 'availableMonths'
        ));
    }

    // ==================== Helper Methods ====================
    private function getStartDate($period)
    {
        switch ($period) {
            case '7':
                return Carbon::now()->subDays(7);
            case 'all_time':
                return Carbon::createFromDate(2020, 1, 1); // Từ năm 2020
            case 'current_month':
                return Carbon::now()->startOfMonth();
            case 'last_month':
                return Carbon::now()->subMonth()->startOfMonth();
            case 'two_months_ago':
                return Carbon::now()->subMonths(2)->startOfMonth();
            case 'three_months_ago':
                return Carbon::now()->subMonths(3)->startOfMonth();
            case 'four_months_ago':
                return Carbon::now()->subMonths(4)->startOfMonth();
            case 'five_months_ago':
                return Carbon::now()->subMonths(5)->startOfMonth();
            case 'six_months_ago':
                return Carbon::now()->subMonths(6)->startOfMonth();
            default:
                // Nếu period là format YYYY-MM (ví dụ: 2024-01)
                if (preg_match('/^\d{4}-\d{2}$/', $period)) {
                    return Carbon::createFromFormat('Y-m', $period)->startOfMonth();
                }
                return Carbon::now()->startOfMonth();
        }
    }

    private function getEndDate($period)
    {
        switch ($period) {
            case '7':
                return Carbon::now();
            case 'all_time':
                return Carbon::now();
            case 'current_month':
                return Carbon::now();
            case 'last_month':
                return Carbon::now()->subMonth()->endOfMonth();
            case 'two_months_ago':
                return Carbon::now()->subMonths(2)->endOfMonth();
            case 'three_months_ago':
                return Carbon::now()->subMonths(3)->endOfMonth();
            case 'four_months_ago':
                return Carbon::now()->subMonths(4)->endOfMonth();
            case 'five_months_ago':
                return Carbon::now()->subMonths(5)->endOfMonth();
            case 'six_months_ago':
                return Carbon::now()->subMonths(6)->endOfMonth();
            default:
                // Nếu period là format YYYY-MM (ví dụ: 2024-01)
                if (preg_match('/^\d{4}-\d{2}$/', $period)) {
                    return Carbon::createFromFormat('Y-m', $period)->endOfMonth();
                }
                return Carbon::now();
        }
    }

    // ==================== Available Months ====================
    private function getAvailableMonths()
    {
        $months = [];
        $currentDate = Carbon::now();
        
        // Tên tháng tiếng Việt
        $vietnameseMonths = [
            1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
            5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
            9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
        ];
        
        // Thêm 6 tháng gần nhất
        for ($i = 0; $i < 6; $i++) {
            $date = $currentDate->copy()->subMonths($i);
            $monthNumber = (int)$date->format('n');
            $year = $date->format('Y');
            
            $months[] = [
                'value' => $date->format('Y-m'),
                'label' => $date->format('m/Y'),
                'full_label' => $vietnameseMonths[$monthNumber] . ' ' . $year
            ];
        }
        
        return $months;
    }

    // ==================== Key Metrics ====================
    private function getTotalOrders($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])->count();
    }

    private function getTotalRevenue($startDate, $endDate)
    {
        // Tổng doanh thu = tính các đơn hàng đã thanh toán và chưa hoàn tiền
        // Bao gồm: completed, processing, pending (đã thanh toán)
        // Loại trừ: cancelled, refunded
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])
            ->where('payment_status', '!=', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        
        return $totalRevenue;
    }

    private function getTotalRefunds($startDate, $endDate)
    {
        // Số tiền đã hoàn = tổng total_amount của đơn hàng có payment_status = refunded
        return Order::where('payment_status', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }

    private function getActiveUsers($startDate, $endDate): int
    {
        return User::whereHas('orders', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->orWhereHas('reviews', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->count();
    }

    // ==================== Growth Calculations ====================
    private function getOrdersGrowth($startDate, $endDate)
    {
        $currentPeriod = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $previousStartDate = Carbon::parse($startDate)->subDays($endDate->diffInDays($startDate) + 1);
        $previousEndDate = Carbon::parse($startDate)->subDay();
        $previousPeriod = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();
        
        if ($previousPeriod == 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }
        
        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }

    private function getRevenueGrowth($startDate, $endDate)
    {
        $currentPeriod = $this->getTotalRevenue($startDate, $endDate);
        
        $previousStartDate = Carbon::parse($startDate)->subDays($endDate->diffInDays($startDate) + 1);
        $previousEndDate = Carbon::parse($startDate)->subDay();
        $previousPeriod = $this->getTotalRevenue($previousStartDate, $previousEndDate);
        
        if ($previousPeriod == 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }
        
        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }

    private function getRefundsGrowth($startDate, $endDate)
    {
        $currentPeriod = $this->getTotalRefunds($startDate, $endDate);
        
        $previousStartDate = Carbon::parse($startDate)->subDays($endDate->diffInDays($startDate) + 1);
        $previousEndDate = Carbon::parse($startDate)->subDay();
        $previousPeriod = $this->getTotalRefunds($previousStartDate, $previousEndDate);
        
        if ($previousPeriod == 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }
        
        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }

    private function getUsersGrowth($startDate, $endDate): float
    {
        $currentPeriod = $this->getActiveUsers($startDate, $endDate);
        
        $periodDays = $startDate->diffInDays($endDate);
        $previousStartDate = $startDate->copy()->subDays($periodDays);
        $previousEndDate = $startDate->copy();
        $previousPeriod = $this->getActiveUsers($previousStartDate, $previousEndDate);
        
        if ($previousPeriod == 0) return 0;
        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 2);
    }

    // ==================== Chart Data ====================
    private function getTotalSalesChart($period = 'all_time')
    {
        $data = [];
        $endDate = Carbon::now();
        $startDate = $this->getStartDate($period);
        
        if ($period == '7') {
            // 7 ngày - hiển thị theo ngày, đầy đủ 7 ngày
            for ($i = 6; $i >= 0; $i--) {
                $date = $endDate->copy()->subDays($i);
                $revenue = $this->getDailyRevenue($date);
                $data[] = [
                    'label' => $date->format('d/m'),
                    'revenue' => $revenue
                ];
            }
        } elseif ($period == 'all_time') {
            // Tất cả thời gian - hiển thị theo năm
            $vietnameseMonths = [
                1 => 'T1', 2 => 'T2', 3 => 'T3', 4 => 'T4',
                5 => 'T5', 6 => 'T6', 7 => 'T7', 8 => 'T8',
                9 => 'T9', 10 => 'T10', 11 => 'T11', 12 => 'T12'
            ];
            
            // Lấy dữ liệu 12 tháng gần nhất
            for ($i = 11; $i >= 0; $i--) {
                $monthStart = $endDate->copy()->subMonths($i)->startOfMonth();
                $monthEnd = $endDate->copy()->subMonths($i)->endOfMonth();
                $revenue = $this->getMonthlyRevenue($monthStart, $monthEnd);
                
                $monthNumber = (int)$monthStart->format('n');
                $year = $monthStart->format('Y');
                
                $data[] = [
                    'label' => $vietnameseMonths[$monthNumber] . '/' . $year,
                    'revenue' => $revenue
                ];
            }
        } else {
            // Hiển thị theo tháng - lấy dữ liệu của 6 tháng gần nhất để so sánh
            $vietnameseMonths = [
                1 => 'T1', 2 => 'T2', 3 => 'T3', 4 => 'T4',
                5 => 'T5', 6 => 'T6', 7 => 'T7', 8 => 'T8',
                9 => 'T9', 10 => 'T10', 11 => 'T11', 12 => 'T12'
            ];
            
            for ($i = 5; $i >= 0; $i--) {
                $monthStart = $endDate->copy()->subMonths($i)->startOfMonth();
                $monthEnd = $endDate->copy()->subMonths($i)->endOfMonth();
                $revenue = $this->getMonthlyRevenue($monthStart, $monthEnd);
                
                $monthNumber = (int)$monthStart->format('n');
                $year = $monthStart->format('Y');
                
                $data[] = [
                    'label' => $vietnameseMonths[$monthNumber] . '/' . $year,
                    'revenue' => $revenue
                ];
            }
        }
        
        return $data;
    }
    
    private function getDailyRevenue($date)
    {
        return Order::whereNotIn('status', ['cancelled'])
            ->where('payment_status', '!=', 'refunded')
            ->whereDate('created_at', $date)
            ->sum('total_amount');
    }
    
    private function getWeeklyRevenue($startDate, $endDate)
    {
        return Order::whereNotIn('status', ['cancelled'])
            ->where('payment_status', '!=', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }
    
    private function getMonthlyRevenue($startDate, $endDate)
    {
        return Order::whereNotIn('status', ['cancelled'])
            ->where('payment_status', '!=', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }
    
    private function getQuarterlyRevenue($startDate, $endDate)
    {
        return Order::whereNotIn('status', ['cancelled'])
            ->where('payment_status', '!=', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
    }

    private function getMonthlyStatistics($startDate, $endDate)
    {
        // Tổng doanh thu = tính các đơn hàng đã thanh toán và chưa hoàn tiền
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])
            ->where('payment_status', '!=', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        
        // Số tiền đã hoàn = tổng total_amount của đơn hàng có payment_status = refunded
        $totalRefunds = Order::where('payment_status', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        
        // Lợi nhuận = Doanh thu - Chi phí (ước tính 70% chi phí)
        // Giả sử chi phí bao gồm: giá vốn hàng bán, chi phí vận chuyển, chi phí quản lý
        $costOfGoods = $totalRevenue * 0.6; // 60% giá vốn hàng bán
        $operatingCosts = $totalRevenue * 0.1; // 10% chi phí vận hành
        $profit = $totalRevenue - $costOfGoods - $operatingCosts;
        
        return [
            'total_revenue' => $totalRevenue,
            'profit' => max(0, $profit), // Đảm bảo lợi nhuận không âm
            'refunds' => $totalRefunds
        ];
    }

    private function getInventoryByCategory()
    {
        return Category::with('products')
            ->get()
            ->map(function ($category) {
                $totalStock = $category->products->sum('stock');
                return [
                    'category_name' => $category->name,
                    'total_stock' => $totalStock,
                    'product_count' => $category->products->count()
                ];
            })
            ->filter(function ($item) {
                return $item['total_stock'] > 0; // Chỉ hiển thị danh mục có tồn kho
            })
            ->sortByDesc('total_stock')
            ->take(8) // Giới hạn 8 danh mục chính
            ->values();
    }

    // ==================== Recent Data Methods ====================
    private function getRecentUsers()
    {
        return User::select('id', 'name', 'email', 'created_at', 'email_verified_at as activated_at')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    private function getRecentProducts()
    {
        return Product::select('id', 'name', 'price', 'product_image', 'created_at')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
    }

    private function getTopSellingProducts()
    {
        // Version đơn giản - chỉ lấy sản phẩm có product_id trực tiếp
        $results = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.product_image',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('MAX(products.created_at) as created_at')
            )
            ->whereIn('orders.status', ['delivered', 'shipped', 'processing', 'pending'])
            ->where('orders.payment_status', 'paid')
            ->whereNotNull('order_items.product_id')
            ->groupBy('products.id', 'products.name', 'products.price', 'products.product_image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return $results;
    }

    private function getTopCategoriesWithProducts()
    {
        // Query cho sản phẩm có variant
        $categoriesWithVariants = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->whereIn('orders.status', ['delivered', 'shipped', 'processing', 'pending'])
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name');

        // Query cho sản phẩm không có variant
        $categoriesWithoutVariants = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_quantity')
            )
            ->whereIn('orders.status', ['delivered', 'shipped', 'processing', 'pending'])
            ->where('orders.payment_status', 'paid')
            ->whereNotNull('order_items.product_id')
            ->groupBy('categories.id', 'categories.name');

        // Union và tổng hợp kết quả
        $combinedCategories = $categoriesWithVariants->union($categoriesWithoutVariants);
        
        $categories = DB::query()
            ->fromSub($combinedCategories, 'combined')
            ->select(
                'id',
                'name',
                DB::raw('SUM(total_quantity) as total_quantity')
            )
            ->groupBy('id', 'name')
            ->orderByDesc('total_quantity')
            ->limit(4)
            ->get();

        // Lấy sản phẩm bán chạy cho mỗi danh mục
        foreach ($categories as $cat) {
            // Query cho sản phẩm có variant trong danh mục
            $productsWithVariants = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                ->join('products', 'product_variants.product_id', '=', 'products.id')
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('SUM(order_items.quantity) as quantity')
                )
                ->whereIn('orders.status', ['delivered', 'shipped', 'processing', 'pending'])
                ->where('orders.payment_status', 'paid')
                ->where('products.category_id', $cat->id)
                ->groupBy('products.id', 'products.name');

            // Query cho sản phẩm không có variant trong danh mục
            $productsWithoutVariants = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('SUM(order_items.quantity) as quantity')
                )
                ->whereIn('orders.status', ['delivered', 'shipped', 'processing', 'pending'])
                ->where('orders.payment_status', 'paid')
                ->where('products.category_id', $cat->id)
                ->whereNotNull('order_items.product_id')
                ->groupBy('products.id', 'products.name');

            // Union và tổng hợp kết quả
            $combinedProducts = $productsWithVariants->union($productsWithoutVariants);
            
            $cat->products = DB::query()
                ->fromSub($combinedProducts, 'combined')
                ->select(
                    'id',
                    'name',
                    DB::raw('SUM(quantity) as quantity')
                )
                ->groupBy('id', 'name')
                ->orderByDesc('quantity')
                ->get();
        }

        return $categories;
    }

    // ==================== Additional Metrics ====================
    private function getPendingOrders()
    {
        return Order::where('status', 'pending')->count();
    }

    private function getLowStockProducts()
    {
        return Product::whereHas('variants', function($query) {
            $query->where('stock', '<', 10);
        })->count();
    }

    private function getTotalSoldQuantity(): int
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['delivered', 'shipped', 'processing', 'pending'])
            ->where('orders.payment_status', 'paid')
            ->sum('order_items.quantity');
    }
}