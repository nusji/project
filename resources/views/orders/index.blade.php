<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบสั่งซื้อวัตถุดิบ', 'url' => route('orders.index')], ['label' => '']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบสั่งซื้อวัตถุดิบ</h2>
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col h-full">
            <div style="display: flex;">
                <!-- ส่วนของกราฟและตัวเลือก -->
                <div>
                    <select id="timePeriod" onchange="updateChart()">
                        <option value="monthly">รายเดือน</option>
                        <option value="weekly">รายสัปดาห์</option>
                        <option value="yearly">รายปี</option>
                    </select>
                </div>
                <canvas id="orderChart" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('orders.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    บันทึกรายการสั่งซื้อใหม่
                </a>
            </div>
            <form action="#" method="GET" class="flex-grow md:max-w-md">
                <div class="relative">
                    <input type="text" name="search" placeholder="ค้นหา..." value="{{ request('search') }}"
                        class="w-full px-4 py-2 rounded-md border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit"
                        class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-white-800 border-l border-gray-300 rounded-r-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
    <!-- ส่วนของตาราง-->
    <div class="p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">ประวัติการสั่งซื้อวัตถุดิบ</h1>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            วันที่สั่งซื้อ
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            รายละเอียด
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จำนวนรายการ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ราคารวม
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ผู้สั่งซื้อ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            การดำเนินการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($orders->isEmpty())
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                ไม่มีข้อมูลการสั่งซื้อวัตถุดิบ
                            </td>
                        </tr>
                    @else
                        @foreach ($orders as $order)
                            @php
                                $ingredientCount = $order->orderDetails->count('ingredient_id');
                                $totalPrice = $order->orderDetails->sum('price');
                                $ingredients = $order->orderDetails->pluck('ingredient.ingredient_name');
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->order_date->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap">
                                    {{ $ingredients->take(3)->implode(', ') }}{{ $ingredients->count() > 3 ? '...' : '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $ingredientCount }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($totalPrice, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->employee->name }}
                                    @if ($order->employee->deleted_at)
                                        <span style="color: red;">(ลาออก)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 text-center">

                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        ดูรายละเอียด
                                    </a>
                                    @if (auth()->user()->role === 'owner')
                                        <a href="{{ route('orders.edit', $order->id) }}"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            แก้ไข
                                        </a>

                                        <form id="delete-form-{{ $order->id }}"
                                            action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                onclick="confirmDelete({{ $order->id }})">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                ลบ
                                            </button>
                                            <script>
                                                function confirmDelete(orderId) {
                                                    Swal.fire({
                                                        title: 'คุณแน่ใจหรือไม่?',
                                                        html: 'การลบรายการสั่งซื้อจะไม่สามารถกู้คืนได้!<br><br>' +
                                                            '<span style="color: red; font-weight: bold;">จำนวนวัตถุดิบ จะถูกลบออกตามด้วย</span>',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'ใช่, ลบเลย!',
                                                        cancelButtonText: 'ยกเลิก'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            // หากผู้ใช้ยืนยันการลบ, ส่งแบบฟอร์ม
                                                            document.getElementById('delete-form-' + orderId).submit();
                                                        }
                                                    });
                                                }
                                            </script>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        @endif
                </tbody>
            </table>


        </div>
        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <script>
        let chart;

        // ฟังก์ชันสำหรับโหลดข้อมูลเริ่มต้น
        window.onload = function() {
            fetchChartData('monthly');
        };

        // ฟังก์ชันสำหรับอัพเดตกราฟ
        function updateChart() {
            const period = document.getElementById('timePeriod').value;
            fetchChartData(period);
        }

        // ฟังก์ชันสำหรับดึงข้อมูลจากเซิร์ฟเวอร์
        function fetchChartData(period) {
            fetch(`/api/order-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('orderChart').getContext('2d');

                    // ถ้ามีกราฟอยู่แล้ว ให้ทำลายก่อน
                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'ยอดการสั่งซื้อ',
                                data: data.totals,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }
    </script>
@endsection
