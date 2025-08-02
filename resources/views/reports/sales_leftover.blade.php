@extends('layouts.app')
@section('content')
<div class="container mt-0">
    <h1>รายงานยอดขายและของเหลือ</h1>
    <form method="GET" action="{{ route('reports.sales_leftover') }}" class="form-inline mb-4">
        <div class="form-group mr-2">
            <label for="start_date" class="mr-2">วันที่เริ่มต้น:</label>
            <input type="date" id="start_date" name="start_date" value="{{ $start_date }}" class="form-control">
        </div>
        <div class="form-group mr-2">
            <label for="end_date" class="mr-2">วันที่สิ้นสุด:</label>
            <input type="date" id="end_date" name="end_date" value="{{ $end_date }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">แสดงรายงาน</button>
    </form>

    @if($report->isEmpty())
        <div class="alert alert-info">ไม่มีข้อมูลเมนูที่ขายหมดในช่วงวันที่เลือก</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ชื่อเมนู</th>
                    <th>ผลิตทั้งหมด</th>
                    <th>ขายได้ (บาท)</th>
                    <th>จำนวนคงเหลือ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report as $item)
                <tr @if($item->remaining_amount <= 0) class="table-danger" @endif>
                    <td>{{ $item->menu_name }}</td>
                    <td>{{$item->created_a}}</td>
                    <td>{{ number_format($item->total_produced, 2) }}</td>
                    <td>{{ number_format($item->total_sold, 2) }}</td>
                    <td>{{ number_format($item->remaining_amount, 1) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- ตัวอย่างการแสดงกราฟ -->
        <canvas id="salesLeftoverChart" width="400" height="200"></canvas>
    @endif
</div>

@if(!$report->isEmpty())
<script>
    // เตรียมข้อมูลสำหรับกราฟ
    const labels = @json($report->pluck('menu_name'));
    const dataSold = @json($report->pluck('total_sold'));
    const dataRemaining = @json($report->pluck('remaining_amount'));

    const ctx = document.getElementById('salesLeftoverChart').getContext('2d');
    const salesLeftoverChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'ขายได้ (บาท)',
                    data: dataSold,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'จำนวนคงเหลือ',
                    data: dataRemaining,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255,99,132,1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { 
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'จำนวน / บาท'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'เมนู'
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
