@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">รายงานและสถิติ</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Average Ratings -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">คะแนนเฉลี่ยของเมนู</h2>
                <canvas id="averageRatingsChart"></canvas>
            </div>

            <!-- Top Selling Menus -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">เมนูขายดีที่สุด</h2>
                <canvas id="topSellingMenusChart"></canvas>
            </div>

            <!-- Least Selling Menus -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">เมนูขายไม่ค่อยดี</h2>
                <canvas id="leastSellingMenusChart"></canvas>
            </div>

            <!-- Most Used Ingredients -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">วัตถุดิบที่ถูกใช้มากที่สุด</h2>
                <canvas id="mostUsedIngredientsChart"></canvas>
            </div>
        </div>

        <!-- Daily Sales -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายรายวัน (7 วันล่าสุด)</h2>
            <canvas id="dailySalesChart" height="100" width="400"></canvas>
        </div>

        <!-- Profit Analysis -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">การวิเคราะห์กำไรจากยอดขาย (30 วันล่าสุด)</h2>
            <p class="text-xl font-semibold text-gray-700 mb-4">(ยอดขายรวม) - (ราคารวมสั่งซื้อวัตถุดิบ)</p>
            <canvas id="profitAnalysisChart" height="100" width="400"></canvas>
        </div>
        <!-- Low Stock Ingredients -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">วัตถุดิบที่มีสต็อกต่ำ</h2>
            <ul class="space-y-2">
                @foreach ($lowStockIngredients as $ingredient)
                    <li class="text-red-600">
                        {{ $ingredient->ingredient_name }}: {{ $ingredient->ingredient_stock }}
                        {{ $ingredient->ingredient_unit }} (ขั้นต่ำ {{ $ingredient->minimum_quantity }}
                        {{ $ingredient->ingredient_unit }})
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Employee Performance -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">ประสิทธิภาพพนักงาน</h2>
            <canvas id="employeePerformanceChart"></canvas>
        </div>

        <!-- Payroll Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">สรุปเงินเดือน</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 text-left">พนักงาน</th>
                            <th class="py-2 px-4 text-left">เงินเดือนสุทธิ</th>
                            <th class="py-2 px-4 text-left">วันที่จ่าย</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrollSummary as $payroll)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $payroll->employee->name }}</td>
                                <td class="py-2 px-4">{{ number_format($payroll->net_salary, 2) }}</td>
                                <td class="py-2 px-4">
                                    {{ \Carbon\Carbon::parse($payroll->payment_date)->format('Y-m-d') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <!-- Order Trends -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">แนวโน้มคำสั่งซื้อ (30 วันล่าสุด)</h2>
                <canvas id="orderTrendsChart"></canvas>
            </div>

            <!-- Sales by Payment Type -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายตามประเภทการชำระเงิน (30 วันล่าสุด)</h2>
                <canvas id="salesByPaymentTypeChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <!-- Ingredient Usage -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">การใช้วัตถุดิบที่ใช้มากที่สุด (30 วันล่าสุด)</h2>
                <canvas id="ingredientUsageChart"></canvas>
            </div>

            ฟฟ
        </div>
    </div>
    <script>
        const chartOptions = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    font: {
                        size: 16
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        // Order Trends Chart
        new Chart(document.getElementById('orderTrendsChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($orderTrends->pluck('date')) !!},
                datasets: [{
                    label: 'ยอดรวมคำสั่งซื้อ',
                    data: {!! json_encode($orderTrends->pluck('total_orders')) !!},
                    backgroundColor: 'rgba(255, 159, 64, 0.5)', // Tailwind orange-500 with opacity
                    borderColor: 'rgb(255, 159, 64)', // Tailwind orange-500
                    borderWidth: 2,
                    fill: true,
                    tension: 0.1
                }]
            },
            options: chartOptions
        });

        // Sales by Payment Type Chart
        new Chart(document.getElementById('salesByPaymentTypeChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($salesByPaymentType->pluck('payment_type')) !!},
                datasets: [{
                    label: 'ยอดขาย',
                    data: {!! json_encode($salesByPaymentType->pluck('total_revenue')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', // Red
                        'rgba(54, 162, 235, 0.5)', // Blue
                        'rgba(255, 206, 86, 0.5)', // Yellow
                        'rgba(75, 192, 192, 0.5)', // Green
                        'rgba(153, 102, 255, 0.5)', // Purple
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)', // Red
                        'rgb(54, 162, 235)', // Blue
                        'rgb(255, 206, 86)', // Yellow
                        'rgb(75, 192, 192)', // Green
                        'rgb(153, 102, 255)', // Purple
                    ],
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // Profit Analysis Chart
        new Chart(document.getElementById('profitAnalysisChart'), {
            type: 'line', // ใช้ประเภทแผนภูมิเส้นเพื่อแสดงแนวโน้มกำไร
            data: {
                labels: {!! json_encode($profitAnalysis->pluck('date')) !!},
                datasets: [{
                        label: 'ยอดขายรวม',
                        data: {!! json_encode($profitAnalysis->pluck('total_sales')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.2)', // Tailwind blue-500 with opacity
                        borderColor: 'rgb(59, 130, 246)', // Tailwind blue-500
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'ราคาสั่งซื้อรวม',
                        data: {!! json_encode($profitAnalysis->pluck('total_purchase')) !!},
                        backgroundColor: 'rgba(239, 68, 68, 0.2)', // Tailwind red-500 with opacity
                        borderColor: 'rgb(239, 68, 68)', // Tailwind red-500
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'กำไร',
                        data: {!! json_encode($profitAnalysis->pluck('profit')) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.2)', // Tailwind green-500 with opacity
                        borderColor: 'rgb(34, 197, 94)', // Tailwind green-500
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: {
                        ...chartOptions.plugins.title,
                        text: 'การวิเคราะห์กำไรจากยอดขาย (30 วันล่าสุด)'
                    }
                }
            }
        });

        // Ingredient Usage Chart
        new Chart(document.getElementById('ingredientUsageChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($ingredientUsage->pluck('ingredient_name')) !!},
                datasets: [{
                    label: 'ปริมาณที่ใช้รวม',
                    data: {!! json_encode($ingredientUsage->pluck('total_used')) !!},
                    backgroundColor: 'rgba(67, 56, 202, 0.5)', // Tailwind indigo-600 with opacity
                    borderColor: 'rgb(67, 56, 202)', // Tailwind indigo-600
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // Employee Performance Chart
        new Chart(document.getElementById('employeePerformanceChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($employeePerformance->pluck('name')) !!},
                datasets: [{
                    label: 'ออเดอร์ขาย',
                    data: {!! json_encode($employeePerformance->pluck('total_sales')) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.5)', // Tailwind green-500 with opacity
                    borderColor: 'rgb(34, 197, 94)', // Tailwind green-500
                    borderWidth: 1
                }, {
                    label: 'ยอดขายรวม',
                    data: {!! json_encode($employeePerformance->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)', // Tailwind blue-500 with opacity
                    borderColor: 'rgb(59, 130, 246)', // Tailwind blue-500
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // Average Ratings Chart
        new Chart(document.getElementById('averageRatingsChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($feedbackAnalysis->pluck('menu_name')) !!},
                datasets: [{
                    label: 'รีวิวเฉลี่ย',
                    data: {!! json_encode($feedbackAnalysis->pluck('average_rating')) !!},
                    backgroundColor: 'rgba(255, 193, 7, 0.5)', // Tailwind yellow-500 with opacity
                    borderColor: 'rgb(255, 193, 7)', // Tailwind yellow-500
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });

        // Top Selling Menus Chart
        new Chart(document.getElementById('topSellingMenusChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($topSellingMenus->pluck('menu.menu_name')) !!},
                datasets: [{
                    label: 'จำนวนที่ขาย',
                    data: {!! json_encode($topSellingMenus->pluck('total_sold')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)', // Tailwind blue-500 with opacity
                    borderColor: 'rgb(59, 130, 246)', // Tailwind blue-500
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: {
                        ...chartOptions.plugins.title,
                        text: 'Top Selling Menus'
                    }
                }
            }
        });

        // Least Selling Menus Chart
        new Chart(document.getElementById('leastSellingMenusChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($leastSellingMenus->pluck('menu.menu_name')) !!},
                datasets: [{
                    label: 'จำนวนที่ขาย',
                    data: {!! json_encode($leastSellingMenus->pluck('total_sold')) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.5)', // Tailwind red-500 with opacity
                    borderColor: 'rgb(239, 68, 68)', // Tailwind red-500
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: {
                        ...chartOptions.plugins.title,
                        text: 'Least Selling Menus'
                    }
                }
            }
        });

        // Most Used Ingredients Chart
        new Chart(document.getElementById('mostUsedIngredientsChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($mostUsedIngredients->pluck('ingredient_name')) !!},
                datasets: [{
                    label: 'จำนวนที่ใช้',
                    data: {!! json_encode($mostUsedIngredients->pluck('total_used')) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.5)', // Tailwind green-500 with opacity
                    borderColor: 'rgb(16, 185, 129)', // Tailwind green-500
                    borderWidth: 1
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: {
                        ...chartOptions.plugins.title,
                        text: 'Most Used Ingredients'
                    }
                }
            }
        });

        // Daily Sales Chart
        new Chart(document.getElementById('dailySalesChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($dailySales->pluck('date')) !!},
                datasets: [{
                    label: 'ยอดขายรวม',
                    data: {!! json_encode($dailySales->pluck('total_revenue')) !!},
                    backgroundColor: 'rgba(139, 92, 246, 0.5)', // Tailwind purple-500 with opacity
                    borderColor: 'rgb(139, 92, 246)', // Tailwind purple-500
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    title: {
                        ...chartOptions.plugins.title,
                        text: 'Daily Sales'
                    }
                }
            }
        });
    </script>
@endsection
