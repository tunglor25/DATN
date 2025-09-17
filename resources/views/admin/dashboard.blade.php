@extends('layouts.app')

@section('content')
<div class="dashboard-container">


    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-left">
            <h1 class="dashboard-title">Bảng điều khiển</h1>
            <p class="dashboard-subtitle">Chào mừng trở lại! Đây là những gì đang diễn ra với cửa hàng của bạn hôm nay.</p>
        </div>
        <div class="header-right">
            <div class="date-display">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ now()->format('l, j F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards - 4 blocks trong 1 hàng -->
    <div class="metrics-grid">
        <!-- Total Orders -->
        <div class="metric-card">
            <div class="metric-icon bg-primary">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ number_format($totalOrders) }}</div>
                <div class="metric-label">Tổng đơn hàng</div>
                @if($totalOrders > 0)
                <div class="metric-growth {{ $ordersGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $ordersGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $ordersGrowth >= 0 ? '+' : '' }}{{ $ordersGrowth }}%</span>
                </div>
                @else
                <div class="metric-growth neutral">
                    <i class="fas fa-minus"></i>
                    <span>Chưa có dữ liệu</span>
                </div>
                @endif
            </div>
            <div class="metric-link">
                <a href="{{ route('admin.orders.index') }}">Xem tất cả đơn hàng</a>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="metric-card">
            <div class="metric-icon bg-success">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ number_format($totalRevenue / 1000, 1) }}k ₫</div>
                <div class="metric-label">Tổng doanh thu</div>
                @if($totalRevenue > 0)
                <div class="metric-growth {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}%</span>
                </div>
                @else
                <div class="metric-growth neutral">
                    <i class="fas fa-minus"></i>
                    <span>Chưa có dữ liệu</span>
                </div>
                @endif
            </div>
            <div class="metric-link">
                <a href="{{ route('admin.orders.index') }}">Xem thu nhập ròng</a>
            </div>
        </div>

        <!-- Total Refunds -->
        <div class="metric-card">
            <div class="metric-icon bg-danger">
                <i class="fas fa-undo-alt"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ number_format($totalRefunds / 1000, 1) }}k ₫</div>
                <div class="metric-label">Số tiền đã hoàn</div>
                @if($totalRefunds > 0)
                <div class="metric-growth {{ $refundsGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $refundsGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $refundsGrowth >= 0 ? '+' : '' }}{{ $refundsGrowth }}%</span>
                </div>
                @else
                <div class="metric-growth neutral">
                    <i class="fas fa-minus"></i>
                    <span>Chưa có dữ liệu</span>
                </div>
                @endif
            </div>
            <div class="metric-link">
                <a href="{{ route('admin.orders.index') }}">Xem chi tiết hoàn tiền</a>
            </div>
        </div>

        <!-- Active Users -->
        <div class="metric-card">
            <div class="metric-icon bg-warning">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <div class="metric-value">{{ number_format($activeUsers) }}</div>
                <div class="metric-label">Người dùng đang hoạt động</div>
                @if($activeUsers > 0)
                <div class="metric-growth {{ $usersGrowth >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ $usersGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $usersGrowth >= 0 ? '+' : '' }}{{ $usersGrowth }}%</span>
                </div>
                @else
                <div class="metric-growth neutral">
                    <i class="fas fa-minus"></i>
                    <span>Chưa có dữ liệu</span>
                </div>
                @endif
            </div>
            <div class="metric-link">
                <a href="{{ route('admin.user.index') }}">Xem chi tiết</a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <!-- Total Sales Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>So sánh doanh thu theo tháng (Đơn vị: triệu VNĐ)</h3>
                <div class="chart-controls">
                    <button class="time-btn" data-period="7">7 Ngày</button>
                    <div class="chart-dropdown">
                        <select class="period-select" id="salesPeriod">
                            <option value="all_time" {{ $period == 'all_time' ? 'selected' : '' }}>Tất cả thời gian</option>
                            <option value="current_month" {{ $period == 'current_month' ? 'selected' : '' }}>Tháng hiện tại</option>
                            @foreach($availableMonths as $month)
                                <option value="{{ $month['value'] }}" {{ $period == $month['value'] ? 'selected' : '' }}>
                                    {{ $month['full_label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="totalSalesChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Monthly Statistics Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Thống kê tài chính (Đơn vị: triệu VNĐ)</h3>
                <div class="chart-dropdown">
                    <select class="period-select" id="statsPeriod">
                        <option value="7">7 ngày qua</option>
                        <option value="all_time" {{ $period == 'all_time' ? 'selected' : '' }}>Tất cả thời gian</option>
                        <option value="current_month" {{ $period == 'current_month' ? 'selected' : '' }}>Tháng hiện tại</option>
                        @foreach($availableMonths as $month)
                            <option value="{{ $month['value'] }}" {{ $period == $month['value'] ? 'selected' : '' }}>
                                {{ $month['full_label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="monthlyStatsChart" width="400" height="200"></canvas>
            </div>
        </div>

    </div>

    <!-- Recent Data Tables -->
    <div class="data-tables">
        <!-- Recent Users -->
        <div class="table-card">
            <div class="table-header">
                <h4><i class="fas fa-users me-2"></i>Người dùng gần đây</h4>
                <a href="{{ route('admin.user.index') }}" class="view-all">Xem tất cả</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Trạng thái</th>
                            <th>Tham gia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->activated_at)
                                    <span class="active">Đã kích hoạt</span>
                                @else
                                    <span class="pending">Chờ xác thực</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="table-card">
            <div class="table-header">
                <h4><i class="fas fa-box me-2"></i>Sản phẩm gần đây</h4>
                <a href="{{ route('admin.products.index') }}" class="view-all">Xem tất cả</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Thêm vào</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentProducts as $product)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <div class="product-image">
                                        @if($product->product_image)
                                            <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                                        @else
                                            <i class="fas fa-image"></i>
                                        @endif
                                    </div>
                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>
                            <td>{{ number_format($product->price) }} ₫</td>
                            <td>
                                <span class=" active">Hoạt động</span>
                            </td>
                            <td>{{ $product->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Products and Inventory Chart Row -->
        <div class="table-chart-row">
            <!-- Top Selling Products -->
            <div class="table-card">
                <div class="table-header">
                    <h4><i class="fas fa-star me-2"></i>Sản phẩm bán chạy</h4>
                    <a href="{{ route('admin.products.index') }}" class="view-all">Xem tất cả</a>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Đã bán</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($topSellingProducts->count() > 0)
                                @foreach($topSellingProducts as $product)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-image">
                                                @if($product->product_image)
                                                    <img src="{{ asset('storage/' . $product->product_image) }}" alt="{{ $product->name }}">
                                                @else
                                                    <i class="fas fa-image"></i>
                                                @endif
                                            </div>
                                            <span>{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ number_format($product->price) }} ₫</td>
                                    <td>{{ $product->total_sold ?? 0 }}</td>
                                    <td>{{ number_format(($product->price * ($product->total_sold ?? 0))) }} ₫</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Chưa có dữ liệu sản phẩm bán chạy
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Inventory Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h4><i class="fas fa-chart-pie me-2"></i>Thống kê tồn kho theo danh mục</h4>
                </div>
                <div class="chart-container">
                    <canvas id="inventoryChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Total Sales Chart
    const salesCtx = document.getElementById('totalSalesChart').getContext('2d');
    const salesData = @json($totalSalesChart);
    
    // Kiểm tra xem có dữ liệu không
    const hasData = salesData.length > 0;
    
    if (hasData) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.map(item => item.label),
                datasets: [{
                    label: 'Doanh thu (triệu VNĐ)',
                    data: salesData.map(item => item.revenue / 1000000),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                return `Doanh thu: ${value.toFixed(1)} triệu VNĐ`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + 'M';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    } else {
        // Hiển thị thông báo khi không có dữ liệu
        salesCtx.font = '16px Arial';
        salesCtx.fillStyle = '#6b7280';
        salesCtx.textAlign = 'center';
        salesCtx.fillText('Chưa có dữ liệu doanh thu cho tháng này', salesCtx.canvas.width / 2, salesCtx.canvas.height / 2);
    }

    // Monthly Statistics Chart
    const statsCtx = document.getElementById('monthlyStatsChart').getContext('2d');
    const statsData = @json($monthlyStatistics);
    
    // Kiểm tra xem có dữ liệu không (luôn hiển thị nếu có ít nhất một giá trị > 0)
    const hasStatsData = statsData.total_revenue > 0 || statsData.profit > 0 || statsData.refunds >= 0;
    
    if (hasStatsData) {
        // Tính toán giá trị tối đa để thiết lập thang đo phù hợp
        const maxValue = Math.max(
            statsData.total_revenue / 1000000,
            statsData.profit / 1000000,
            statsData.refunds / 1000000
        );
        
        // Điều chỉnh thang đo phù hợp với dữ liệu thực tế
        let yAxisMax, yAxisMin, stepSize;
        
        if (maxValue <= 0.1) {
            // Dữ liệu rất nhỏ (dưới 0.1M) - hiển thị theo nghìn VNĐ
            yAxisMax = Math.max(0.01, Math.ceil(maxValue * 100) / 100);
            yAxisMin = 0;
            stepSize = Math.max(0.01, Math.ceil(yAxisMax * 10) / 100);
        } else if (maxValue <= 1) {
            // Dữ liệu nhỏ (dưới 1M) - hiển thị theo trăm nghìn VNĐ
            yAxisMax = Math.max(0.1, Math.ceil(maxValue * 10) / 10);
            yAxisMin = 0;
            stepSize = Math.max(0.1, Math.ceil(yAxisMax * 10) / 100);
        } else if (maxValue <= 10) {
            // Dữ liệu trung bình (1-10M) - hiển thị theo triệu VNĐ
            yAxisMax = Math.max(1, Math.ceil(maxValue + 1));
            yAxisMin = 0;
            stepSize = Math.max(0.5, Math.ceil(yAxisMax / 10));
        } else {
            // Dữ liệu lớn (trên 10M) - hiển thị theo triệu VNĐ
            yAxisMax = Math.max(10, Math.ceil(maxValue + 5));
            yAxisMin = 0;
            stepSize = Math.max(1, Math.ceil(yAxisMax / 10));
        }
        
        new Chart(statsCtx, {
            type: 'bar',
            data: {
                labels: ['Tổng doanh thu', 'Lợi nhuận', 'Hoàn tiền'],
                datasets: [{
                    data: [
                        statsData.total_revenue / 1000000,
                        statsData.profit / 1000000,
                        statsData.refunds / 1000000
                    ],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: yAxisMin,
                        max: yAxisMax,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(1) + 'M';
                            },
                            stepSize: stepSize
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    } else {
        // Hiển thị thông báo khi không có dữ liệu
        statsCtx.font = '16px Arial';
        statsCtx.fillStyle = '#6b7280';
        statsCtx.textAlign = 'center';
        statsCtx.fillText('Chưa có dữ liệu thống kê cho tháng này', statsCtx.canvas.width / 2, statsCtx.canvas.height / 2);
    }

    // Time period buttons for sales chart
    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Reload page with new period
            const period = this.getAttribute('data-period');
            window.location.href = '{{ route("admin.dashboard") }}?period=' + period;
        });
    });

    // Sales period dropdown
    document.getElementById('salesPeriod').addEventListener('change', function() {
        const period = this.value;
        window.location.href = '{{ route("admin.dashboard") }}?period=' + period;
    });

    // Stats period dropdown
    document.getElementById('statsPeriod').addEventListener('change', function() {
        const period = this.value;
        window.location.href = '{{ route("admin.dashboard") }}?period=' + period;
    });

    // Set active period button
    const currentPeriod = '{{ $period }}';
    document.querySelectorAll('.time-btn').forEach(btn => {
        if (btn.getAttribute('data-period') === currentPeriod) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Set selected period in dropdowns
    document.getElementById('salesPeriod').value = currentPeriod;
    document.getElementById('statsPeriod').value = currentPeriod;

    // Inventory by Category Chart (Pie Chart)
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    const inventoryData = @json($inventoryByCategory);
    
    // Kiểm tra xem có dữ liệu không
    const hasInventoryData = inventoryData.length > 0;
    
    if (hasInventoryData) {
        // Tạo màu sắc cho các danh mục
        const colors = [
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
            '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'
        ];
        
        new Chart(inventoryCtx, {
            type: 'doughnut',
            data: {
                labels: inventoryData.map(item => item.category_name),
                datasets: [{
                    data: inventoryData.map(item => item.total_stock),
                    backgroundColor: colors.slice(0, inventoryData.length),
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value.toLocaleString()} sản phẩm (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    } else {
        // Hiển thị thông báo khi không có dữ liệu
        inventoryCtx.font = '16px Arial';
        inventoryCtx.fillStyle = '#6b7280';
        inventoryCtx.textAlign = 'center';
        inventoryCtx.fillText('Chưa có dữ liệu tồn kho', inventoryCtx.canvas.width / 2, inventoryCtx.canvas.height / 2);
    }
});
</script>

<style>
.dashboard-container {
    padding: 24px;
    background: #f8fafc;
    min-height: 100vh;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.dashboard-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.dashboard-subtitle {
    color: #64748b;
    margin: 8px 0 0 0;
    font-size: 16px;
}

.date-display {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #64748b;
    font-size: 14px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 32px;
}

.metric-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    transition: all 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.metric-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    color: white;
    font-size: 16px;
}

.bg-success { background: #10b981; }
.bg-danger { background: #ef4444; }
.bg-primary { background: #3b82f6; }
.bg-warning { background: #f59e0b; }

.metric-value {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.metric-label {
    color: #64748b;
    font-size: 13px;
    margin-bottom: 8px;
    line-height: 1.3;
}

.metric-growth {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 12px;
}

.metric-growth.positive {
    color: #10b981;
}

.metric-growth.negative {
    color: #ef4444;
}

.metric-growth.neutral {
    color: #6b7280;
}

.metric-link a {
    color: #3b82f6;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
}

.metric-link a:hover {
    text-decoration: underline;
}

.charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.inventory-section {
    margin-bottom: 32px;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.chart-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.chart-controls {
    display: flex;
    gap: 6px;
}

.time-btn {
    padding: 6px 12px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.time-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.period-select {
    padding: 6px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    background: white;
}

.chart-container {
    height: 300px;
    position: relative;
}

.data-tables {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.table-chart-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    grid-column: 1 / -1;
}

.table-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
}

.table-header h4 {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.view-all {
    color: #3b82f6;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
}

.view-all:hover {
    text-decoration: underline;
}

.table-container {
    max-height: 400px;
    overflow-y: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f8fafc;
    padding: 12px 24px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
}

.data-table td {
    padding: 12px 24px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
}

.user-info, .product-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar, .product-image {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 14px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

@media (max-width: 1200px) {
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 1024px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .data-tables {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 16px;
    }
    
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .chart-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .chart-controls {
        flex-wrap: wrap;
    }
}
</style>
@endsection

