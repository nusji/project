@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
                     <!-- เรียกใช้ breadcrumb component -->
                     <x-breadcrumb :paths="[['label' => 'ระบบรายงานและสถิติ', 'url' => route('reports.index')], ['label' => '']]" />
    <h1 class="text-2xl font-bold mb-4">รายงานต้นทุนและกำไรของเมนู</h1>

    <!-- ตารางแสดงต้นทุนและกำไร -->
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ชื่อเมนู</th>
                <th class="py-2 px-4 border-b">รายได้รวม (บาท)</th>
                <th class="py-2 px-4 border-b">ต้นทุนรวม (บาท)</th>
                <th class="py-2 px-4 border-b">กำไร (บาท)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profitData as $data)
            <tr class="text-center">
                <td class="py-2 px-4 border-b">{{ $data['menu_name'] }}</td>
                <td class="py-2 px-4 border-b">{{ number_format($data['total_revenue'], 2) }}</td>
                <td class="py-2 px-4 border-b">{{ number_format($data['total_cost'], 2) }}</td>
                <td class="py-2 px-4 border-b">{{ number_format($data['profit'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
