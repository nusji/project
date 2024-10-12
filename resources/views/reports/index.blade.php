@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">รายงานและสถิติ</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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

        <!-- Daily Sales -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายรายวัน</h2>
            <canvas id="dailySalesChart"></canvas>
        </div>
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

    // Top Selling Menus Chart
    new Chart(document.getElementById('topSellingMenusChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topSellingMenus->pluck('menu.menu_name')) !!},
            datasets: [{
                label: 'Total Sold',
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
                label: 'Total Sold',
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
                label: 'Total Used',
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
                label: 'Total Sales',
                data: {!! json_encode($dailySales->pluck('daily_sales')) !!},
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