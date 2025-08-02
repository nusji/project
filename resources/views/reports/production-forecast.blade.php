@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- Breadcrumb -->
    <nav class="text-gray-500 mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-700">ระบบรายงานและสถิติ</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">คาดการณ์การผลิต</li>
        </ol>
    </nav>

    <!-- หัวเรื่อง -->
    <h1 class="text-3xl font-bold mb-6 text-center">รายงานคาดการณ์ปริมาณการผลิตสำหรับวันที่ {{ \Carbon\Carbon::parse($forecastDate)->format('d/m/Y') }}</h1>

    <!-- ฟอร์มเลือกวันที่และช่วงเวลา -->
    <form method="GET" action="{{ route('reports.productionForecast') }}" class="mb-6 flex flex-col md:flex-row items-center justify-center">
        <div class="flex items-center mb-4 md:mb-0">
            <label for="forecast_date" class="mr-2">วันที่คาดการณ์:</label>
            <input type="date" name="forecast_date" id="forecast_date" value="{{ $forecastDate }}" class="border rounded p-2">
        </div>
        <div class="flex items-center md:ml-4">
            <label for="days" class="mr-2">จำนวนวันที่ใช้ในการคำนวณค่าเฉลี่ย:</label>
            <input type="number" name="days" id="days" value="{{ $days }}" min="1" class="border rounded p-2 w-20">
        </div>
        <button type="submit" class="md:ml-4 mt-4 md:mt-0 bg-blue-500 hover:bg-blue-700 text-white p-2 rounded">ดูรายงาน</button>
    </form>

    <!-- กราฟแสดงผลคาดการณ์ -->
    <div class="my-8">
        <canvas id="productionForecastChart"></canvas>
    </div>

    <!-- ตารางแสดงการคาดการณ์ -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-6 text-left">ชื่อเมนู</th>
                    <th class="py-3 px-6 text-right">ปริมาณเฉลี่ยที่ขายได้ ({{ $days }} วันที่ผ่านมา)</th>
                    <th class="py-3 px-6 text-right">ปริมาณที่แนะนำให้ผลิต</th>
                </tr>
            </thead>
            <tbody>
                @foreach($forecastData as $data)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-6">{{ $data->menu->menu_name }}</td>
                    <td class="py-3 px-6 text-right">{{ number_format($data->average_quantity, 2) }}</td>
                    <td class="py-3 px-6 text-right">{{ ceil($data->average_quantity * 1.1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- แจ้งเตือนเมื่อไม่มีข้อมูล -->
    @if($forecastData->isEmpty())
        <p class="text-center text-red-500 mt-6">ไม่มีข้อมูลการขายในช่วงวันที่ที่คุณเลือก</p>
    @endif

</div>
<script>
    var ctx = document.getElementById('productionForecastChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($menuNames) !!},
            datasets: [
                {
                    label: 'ปริมาณเฉลี่ยที่ขายได้',
                    data: {!! json_encode($averageQuantities) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                },
                {
                    label: 'ปริมาณที่แนะนำให้ผลิต',
                    data: {!! json_encode($recommendedQuantities) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, precision: 0 }
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            }
        }
    });
</script>
@endsection
