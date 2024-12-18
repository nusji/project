@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-700">ระบบรายงานและสถิติ</a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">แนวโน้มความนิยมของเมนู</li>
            </ol>
        </nav>

        <!-- หัวเรื่อง -->
        <h1 class="text-3xl font-bold mb-6 text-center">รายงานแนวโน้มความนิยมของเมนู</h1>
        <!-- ฟอร์มเลือกช่วงวันที่ -->
        <form method="GET" action="{{ route('reports.menuTrends') }}"
            class="mb-6 flex flex-col md:flex-row items-center justify-center">
            <div class="flex items-center mb-4 md:mb-0">
                <label for="start_date" class="mr-2">เริ่มวันที่:</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="border rounded p-2">
            </div>
            <div class="flex items-center md:ml-4">
                <label for="end_date" class="mr-2">ถึงวันที่:</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="border rounded p-2">
            </div>
            <button type="submit"
                class="md:ml-4 mt-4 md:mt-0 bg-blue-500 hover:bg-blue-700 text-white p-2 rounded">ดูรายงาน</button>
        </form>

        <!-- แสดงกราฟสำหรับแต่ละเมนู -->
        @foreach ($trendData as $data)
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-2">{{ $data['menu_name'] }}</h2>
                <canvas id="chart-{{ Str::slug($data['menu_name']) }}"></canvas>
            </div>
        @endforeach
    </div>
    <script>
        @foreach ($trendData as $data)
            var ctx = document.getElementById('chart-{{ Str::slug($data['menu_name']) }}').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($data['dates']) !!},
                    datasets: [{
                        label: 'ยอดขาย',
                        data: {!! json_encode($data['sales_data']) !!},
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                parser: 'YYYY-MM-DD',
                                unit: 'day',
                                displayFormats: {
                                    day: 'DD/MM/YYYY'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                }
            });
        @endforeach
    </script>
@endsection
