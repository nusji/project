@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- หัวเรื่อง -->
                 <!-- เรียกใช้ breadcrumb component -->
                 <x-breadcrumb :paths="[['label' => 'ระบบรายงานและสถิติ', 'url' => route('reports.index')], ['label' => '']]" />
    <h1 class="text-3xl font-bold mb-6 text-center">รายงานความพึงพอใจของลูกค้า</h1>

    <!-- ตารางแสดงคะแนนและความคิดเห็น -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-6 text-left">ชื่อเมนู</th>
                    <th class="py-3 px-6 text-right">คะแนนเฉลี่ย</th>
                    <th class="py-3 px-6 text-right">จำนวนความคิดเห็น</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbackData as $data)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-6">{{ $data['menu_name'] }}</td>
                    <td class="py-3 px-6 text-right">{{ number_format($data['average_rating'], 2) }}</td>
                    <td class="py-3 px-6 text-right">{{ $data['total_feedbacks'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- สคริปต์สำหรับกราฟ (ถ้ามี) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // เพิ่มสคริปต์ของคุณที่นี่ (ถ้ามี)
    </script>
</div>
@endsection
