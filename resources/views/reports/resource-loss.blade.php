@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li><a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-700">รายงาน</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">การสูญเสียทรัพยากร</li>
            </ol>
        </nav>

        <!-- หัวเรื่อง -->
        <h1 class="text-3xl font-bold mb-6 text-center">รายงานการสูญเสียทรัพยากร วันที่
            {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h1>

        <!-- ฟอร์มเลือกวันที่ -->
        <form method="GET" action="{{ route('reports.resourceLoss') }}"
            class="mb-6 flex flex-col md:flex-row items-center justify-center">
            <div class="flex items-center mb-4 md:mb-0">
                <label for="date" class="mr-2">เลือกวันที่:</label>
                <input type="date" name="date" id="date" value="{{ $date }}" class="border rounded p-2">
            </div>
            <button type="submit"
                class="md:ml-4 mt-4 md:mt-0 bg-blue-500 hover:bg-blue-700 text-white p-2 rounded">ดูรายงาน</button>
        </form>

        <!-- กราฟแสดงการสูญเสีย -->
        @if (!$lossData->isEmpty())
            <div class="my-8">
                <canvas id="resourceLossChart"></canvas>
            </div>
        @endif

        <!-- ตารางแสดงการสูญเสีย -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-6 text-left">ชื่อเมนู</th>
                        <th class="py-3 px-6 text-right">จำนวนผลิต</th>
                        <th class="py-3 px-6 text-right">จำนวนที่ขายได้</th>
                        <th class="py-3 px-6 text-right">จำนวนเหลือ</th>
                        <th class="py-3 px-6 text-right">จำนวน Portion ที่เหลือ</th>
                        <th class="py-3 px-6 text-right">มูลค่าที่สูญเสีย (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lossData as $data)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $data['menu_name'] }}</td>
                            <td class="py-3 px-6 text-right">{{ $data['production_amount'] }}</td>
                            <td class="py-3 px-6 text-right">{{ $data['sold_amount'] }}</td>
                            <td class="py-3 px-6 text-right">{{ $data['remaining_amount'] }}</td>
                            <td class="py-3 px-6 text-right">{{ number_format($data['number_of_unsold_portions'], 2) }}</td>
                            <td class="py-3 px-6 text-right">{{ number_format($data['loss'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center">ไม่มีข้อมูลการสูญเสียทรัพยากรสำหรับวันที่นี้</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- แจ้งเตือนเมื่อไม่มีข้อมูล -->
        @if ($lossData->isEmpty())
            <p class="text-center text-red-500 mt-6">ไม่มีข้อมูลการสูญเสียทรัพยากรสำหรับวันที่นี้</p>
        @endif

    </div>
    @if (!$lossData->isEmpty())
        <script>
            var ctx = document.getElementById('resourceLossChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($lossData->pluck('menu_name')) !!},
                    datasets: [{
                        label: 'มูลค่าที่สูญเสีย (บาท)',
                        data: {!! json_encode($lossData->pluck('loss')) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString() +
                                    ' บาท';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endif
@endsection
