@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- Breadcrumb -->
    <nav class="text-gray-500 mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-700">ระบบรายงานและสถิติ</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">เมนูขายดี</li>
        </ol>
    </nav>

    <!-- หัวเรื่อง -->
    <h1 class="text-3xl font-bold mb-6 text-center">รายงานเมนูขายดี</h1>

    <!-- ฟอร์มเลือกช่วงวันที่ -->
    <form method="GET" action="{{ route('reports.bestSellingMenus') }}" class="mb-6 flex flex-col md:flex-row items-center justify-center">
        <div class="flex items-center mb-4 md:mb-0">
            <label for="start_date" class="mr-2">เริ่มวันที่:</label>
            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="border rounded p-2">
        </div>
        <div class="flex items-center md:ml-4">
            <label for="end_date" class="mr-2">ถึงวันที่:</label>
            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="border rounded p-2">
        </div>
        <button type="submit" class="md:ml-4 mt-4 md:mt-0 bg-blue-500 hover:bg-blue-700 text-white p-2 rounded">ดูรายงาน</button>
    </form>

    <!-- กราฟแสดงเมนูขายดี -->
    <div class="my-8">
        <canvas id="bestSellingChart"></canvas>
    </div>

    <!-- ตารางแสดงเมนูขายดี -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-6 text-left">อันดับ</th>
                    <th class="py-3 px-6 text-left">ชื่อเมนู</th>
                    <th class="py-3 px-6 text-right">จำนวนที่ขายได้</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bestSellingMenus as $index => $menu)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-6">{{ $index + 1 }}</td>
                    <td class="py-3 px-6">{{ $menu->menu->menu_name }}</td>
                    <td class="py-3 px-6 text-right">{{ $menu->total_quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    var ctx = document.getElementById('bestSellingChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($menuNames) !!},
            datasets: [{
                label: 'จำนวนที่ขายได้',
                data: {!! json_encode($quantities) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
