@extends('layouts.pos')
@section('content')
    <div class="container mx-auto px-8 py-8">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบจัดการขาย', 'url' => route('sales.index')], ['label' => '']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบจัดการขาย</h2>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <!-- Header Section -->
            <div class="flex flex-wrap gap-6 mb-8">
                <!-- Sales Entry Button -->
                <div class=" flex items-center justify-center w-40 h-40 bg-orange-400 rounded-xl shadow-md cursor-pointer hover:bg-orange-500 shadow-md transition-shadow border-2 p-4">
                    <a href="{{ route('sales.create') }}" class="text-center">
                        <div class="flex justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-50" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>

                        </div>
                        <span class="text-md font-medium text-gray-50">บันทึกการขาย</span>
                    </a>
                </div>

                <!-- Summary Card -->
                <div class="flex-1 bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-medium text-gray-700 mb-4">ช่องทางรับเงิน</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-emerald-600">รายรับวันนี้ (บาท)</p>
                            <p class="text-2xl font-semibold">12,741</p>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-600">จำนวนการขาย (ครั้ง)</p>
                            <p class="text-2xl font-semibold">120</p>
                        </div>
                    </div>
                </div>

                <div class="flex-1 bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-medium text-gray-700 mb-4">สรุปการขาย</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-emerald-600">รายรับวันนี้ (บาท)</p>
                            <p class="text-2xl font-semibold">12,741</p>
                        </div>
                        <div>
                            <p class="text-sm text-emerald-600">จำนวนการขาย (ครั้ง)</p>
                            <p class="text-2xl font-semibold">120</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- ส่วนของตาราง-->
        <div class="p-6">
            <h1 class="text-xl font-bold text-gray-800 mb-4">ประวัตการขาย</h1>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <!-- รหัสการขาย -->
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider {{ $sortBy == 'id' ? 'text-indigo-600' : 'text-gray-500' }}">
                                <a href="{{ route('sales.index', array_merge(request()->all(), ['sort_by' => 'id', 'sort_order' => $sortBy == 'id' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    รหัสการขาย
                                    @if ($sortBy == 'id')
                                        @if ($sortOrder == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <!-- วันที่เวลาขาย -->
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider {{ $sortBy == 'sale_date' ? 'text-indigo-600' : 'text-gray-500' }}">
                                <a href="{{ route('sales.index', array_merge(request()->all(), ['sort_by' => 'sale_date', 'sort_order' => $sortBy == 'sale_date' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    วันที่เวลาขาย
                                    @if ($sortBy == 'sale_date')
                                        @if ($sortOrder == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <!-- พนักงานขาย -->
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider {{ $sortBy == 'employee_name' ? 'text-indigo-600' : 'text-gray-500' }}">
                                <a href="{{ route('sales.index', array_merge(request()->all(), ['sort_by' => 'employee_name', 'sort_order' => $sortBy == 'employee_name' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    พนักงานขาย
                                    @if ($sortBy == 'employee_name')
                                        @if ($sortOrder == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <!-- จ่ายด้วย -->
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider {{ $sortBy == 'payment_type' ? 'text-indigo-600' : 'text-gray-500' }}">
                                <a href="{{ route('sales.index', array_merge(request()->all(), ['sort_by' => 'payment_type', 'sort_order' => $sortBy == 'payment_type' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    จ่ายด้วย
                                    @if ($sortBy == 'payment_type')
                                        @if ($sortOrder == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <!-- ยอดรวม -->
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider {{ $sortBy == 'total_amount' ? 'text-indigo-600' : 'text-gray-500' }}">
                                <a href="{{ route('sales.index', array_merge(request()->all(), ['sort_by' => 'total_amount', 'sort_order' => $sortBy == 'total_amount' && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    ยอดรวม
                                    @if ($sortBy == 'total_amount')
                                        @if ($sortOrder == 'asc')
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <!-- การจัดการ -->
                            <th
                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                การจัดการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if ($sales->isEmpty())
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="4">
                                    ไม่พบประวัติการขาย
                                </td>
                            </tr>
                        @else
                            @foreach ($sales as $sale)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $sale->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $sale->employee->name }}
                                        @if ($sale->employee->deleted_at)
                                            <span class="text-red-500 text-sm">(ลาออก)</span>
                                        @endif
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ ucfirst($sale->payment_type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $sale->saleDetails->sum(function ($detail) {
                                            return $detail->menu ? $detail->menu->menu_price * $detail->quantity : 0;
                                        }) }}
                                    </td>

                                    <!-- ส่วนของการจัดการ -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 text-center">

                                        <a href="{{ route('sales.show', $sale) }}"
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
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline"
                                            id="delete-form-{{ $sale->id }}">
                                            @csrf
                                            @method('DELETE') <!-- เพิ่ม METHOD DELETE -->
                                            <button type="button" onclick="confirmDelete({{ $sale->id }})"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                 ลบข้อมูล
                                            </button>
                                        </form>

                                        <script>
                                            function confirmDelete(saleId) {
                                                Swal.fire({
                                                    title: 'คุณแน่ใจหรือไม่?',
                                                    text: "กรุณาพิมพ์คำว่า 'ลบ' เพื่อยืนยันการลบข้อมูล!",
                                                    icon: 'warning',
                                                    input: 'text',
                                                    inputPlaceholder: 'พิมพ์ที่นี่...',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'ใช่, ลบเลย!',
                                                    cancelButtonText: 'ยกเลิก',
                                                    customClass: {
                                                        confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded',
                                                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded'
                                                    },
                                                    preConfirm: (inputValue) => {
                                                        if (inputValue !== 'ลบ') {
                                                            Swal.showValidationMessage('คำที่พิมพ์ไม่ถูกต้อง! กรุณาพิมพ์ "ลบ".');
                                                        }
                                                        return inputValue;
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById(`delete-form-${saleId}`).submit();
                                                    }
                                                });
                                            }
                                        </script>

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    @endsection
