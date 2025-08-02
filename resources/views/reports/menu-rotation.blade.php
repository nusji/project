@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- Breadcrumb -->
    <nav class="text-gray-500 mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-700">ระบบรายงานและสถิติ</a></li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500">การหมุนเวียนเมนู</li>
        </ol>
    </nav>

    <!-- หัวเรื่อง -->
    <h1 class="text-3xl font-bold mb-6 text-center">รายงานการหมุนเวียนเมนู</h1>

    <!-- ฟอร์มเลือกช่วงวันที่ -->
    <form method="GET" action="{{ route('reports.menuRotation') }}" class="mb-6 flex flex-col md:flex-row items-center justify-center">
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

    <!-- กราฟแสดงการหมุนเวียนเมนู -->
    <div class="my-8">
        <canvas id="menuRotationChart"></canvas>
    </div>

    <!-- ตารางแสดงการหมุนเวียนเมนู -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-6 text-left">อันดับ</th>
                    <th class="py-3 px-6 text-left">ชื่อเมนู</th>
                    <th class="py-3 px-6 text-right">จำนวนวันที่ผลิตเมนูนี้</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menuAllocations as $index => $menu)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-6">{{ $index + 1 }}</td>
                    <td class="py-3 px-6">{{ $menu->menu->menu_name }}</td>
                    <td class="py-3 px-6 text-right">{{ $menu->allocation_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    var ctx = document.getElementById('menuRotationChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($menuNames) !!},
            datasets: [{
                label: 'จำนวนวันที่ผลิตเมนูนี้',
                data: {!! json_encode($allocationCounts) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, precision: 0 }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' วัน';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
