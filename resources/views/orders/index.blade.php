<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')

@section('title', 'จัดการวัตถุดิบ')

@section('content')

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6 bg-[#ffe070] border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">รายการสั่งซื้อวัตถุดิบ</h2>
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('orders.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                                </path>
                            </svg>
                            สร้างรายการสั่งซื้อใหม่
                        </a>
                    </div>
                    <form action="{{ route('ingredients.index') }}" method="GET" class="flex-grow md:max-w-md">
                        <div class="relative">
                            <input type="text" name="search" placeholder="ค้นหาวัตถุดิบ..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 rounded-md border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="submit"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-gray-100 border-l border-gray-300 rounded-r-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
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
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    วันที่สั่งซื้อ
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                            @foreach ($orderSummaries as $summary)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $summary['order']->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $summary['order']->orderDetails->pluck('ingredient.ingredient_name')->implode(', ') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $summary['ingredientCount'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($summary['totalPrice'], 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $summary['order']->employee->first_name }} <!-- ตรวจสอบว่าต้องใช้ field อะไร -->
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('orders.show', $summary['order']->id) }}"
                                            class="text-blue-500 hover:text-blue-700 mr-2">ดู</a>
                                        <a href="{{ route('orders.edit', $summary['order']->id) }}"
                                            class="text-green-500 hover:text-green-700 mr-2">แก้ไข</a>
                                        <form id="delete-form-{{ $summary['order']->id }}"
                                            action="{{ route('orders.destroy', $summary['order']->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $summary['order']->id }})"
                                                class="text-red-500 hover:text-red-700">ลบ</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(orderId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'การลบรายการจะไม่สามารถกู้คืนได้!',
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
@endsection
