@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบรายงานและสถิติ', 'url' => route('reports.index')], ['label' => '']]" />
        <h1 class="text-3xl font-bold mb-8 text-center">ระบบรายงานและสถิติ</h1>

        <!-- แสดงการ์ดหรือปุ่มสำหรับเข้าถึงรายงานต่างๆ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <!-- รายงานเมนูขายดี -->
            <a href="{{ route('reports.bestSellingMenus') }}"
                class="block bg-blue-500 hover:bg-blue-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานเมนูขายดี</span>
            </a>

            <!-- รายงานเมนูขายไม่หมด -->
            <a href="{{ route('reports.unsoldMenus') }}"
                class="block bg-red-500 hover:bg-red-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานเมนูขายไม่หมด</span>
            </a>

            <!-- รายงานการหมุนเวียนเมนู -->
            <a href="{{ route('reports.menuRotation') }}"
                class="block bg-green-500 hover:bg-green-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานการหมุนเวียนเมนู</span>
            </a>

            <!-- รายงานคาดการณ์ปริมาณการผลิต -->
            <a href="{{ route('reports.productionForecast') }}"
                class="block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานคาดการณ์การผลิต</span>
            </a>

            <!-- รายงานแนวโน้มความนิยมของเมนู -->
            <a href="{{ route('reports.menuTrends') }}"
                class="block bg-purple-500 hover:bg-purple-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานแนวโน้มความนิยมของเมนู</span>
            </a>

            <!-- รายงานการสูญเสียทรัพยากร -->
            <a href="{{ route('reports.resourceLoss') }}"
                class="block bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานการสูญเสียทรัพยากร</span>
            </a>

            <!-- รายงานความพึงพอใจของลูกค้า -->
            <a href="{{ route('reports.customerFeedback') }}"
                class="block bg-teal-500 hover:bg-teal-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานความพึงพอใจของลูกค้า</span>
            </a>

            <!-- รายงานต้นทุนและกำไรของเมนู -->
            <a href="{{ route('reports.menuProfitability') }}"
                class="block bg-orange-500 hover:bg-orange-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานต้นทุนและกำไรของเมนู</span>
            </a>

            <!-- รายงานปริมาณวัตถุดิบคงเหลือ -->
            <a href="{{ route('reports.ingredientStock') }}"
                class="block bg-gray-500 hover:bg-gray-700 text-white font-bold py-6 px-4 rounded-lg text-center">
                <span class="text-xl">รายงานปริมาณวัตถุดิบคงเหลือ</span>
            </a>
        </div>

        <!-- Profit Analysis -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">การวิเคราะห์กำไรจากยอดขาย (30 วันล่าสุด)</h2>
            <p class="text-xl font-semibold text-gray-700 mb-4">(ยอดขายรวม) - (ราคารวมสั่งซื้อวัตถุดิบ)</p>
            <canvas id="profitAnalysisChart" height="100" width="400"></canvas>
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
    </script>
@endsection
