@extends('layouts.pos')

@section('content')
<div class="container mx-auto px-8 py-8">
    <x-breadcrumb :paths="[
        ['label' => 'ระบบจัดการขาย', 'url' => route('sales.index')],
        ['label' => '']
    ]" />

    <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบจัดการขาย</h2>

    <div class="flex flex-wrap gap-6 mb-8">
        <!-- Sales Entry Button -->
        <div class="flex items-center justify-center w-40 h-40 bg-orange-400 rounded-xl shadow-md cursor-pointer hover:bg-orange-500 transition-shadow border-2 p-4">
            <a href="{{ route('sales.create') }}" class="text-center">
                <div class="flex justify-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-md font-medium text-gray-50">บันทึกการขาย</span>
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="flex-1 bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-700 mb-4">ช่องทางรับเงิน</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach ($todaySalesByPaymentMethod as $payment)
                    <div>
                        <p class="text-sm text-emerald-600">{{ $payment->payment_type }}</p>
                        <p class="text-2xl font-semibold">{{ number_format($payment->total_revenue, 2) }} บาท</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex-1 bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-700 mb-4">สรุปการขาย</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-emerald-600">รายรับวันนี้ (บาท)</p>
                    <p class="text-2xl font-semibold">{{ number_format($todaySalesData->total_revenue_today, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-emerald-600">จำนวนการขาย (ครั้ง)</p>
                    <p class="text-2xl font-semibold">{{ $todaySalesData->total_sales_today }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales History Section -->
    <div class="p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">ประวัติการขาย</h1>
        
        <!-- Sorting Form -->
        <form method="GET" action="{{ route('sales.index') }}" class="mb-4">
            <div class="flex items-center space-x-4">
                <div>
                    <label for="sort_by" class="block text-sm font-medium text-gray-700">เรียงลำดับตาม</label>
                    <select name="sort_by" id="sort_by" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="sale_date" {{ request('sort_by') == 'sale_date' ? 'selected' : '' }}>วันที่ขาย</option>
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">ทิศทางการเรียงลำดับ</label>
                    <select name="sort_order" id="sort_order" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>น้อยไปมาก</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>มากไปน้อย</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="mt-1 px-4 py-2 bg-blue-500 text-white rounded-md">เรียงลำดับ</button>
                </div>
            </div>
        </form>

        <!-- Sales Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @php
                            $headers = [
                                'id' => 'รหัสการขาย',
                                'sale_date' => 'วันที่เวลาขาย',
                                'employee_id' => 'พนักงานขาย',
                                'payment_type' => 'จ่ายด้วย',
                                'total_amount' => 'ยอดรวม'
                            ];
                        @endphp
                        @foreach ($headers as $key => $label)
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider {{ $sortBy == $key ? 'text-indigo-600' : 'text-gray-500' }}">
                                <a href="{{ route('sales.index', array_merge(request()->all(), ['sort_by' => $key, 'sort_order' => $sortBy == $key && $sortOrder == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                    {{ $label }}
                                    @if ($sortBy == $key)
                                        <svg class="h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="{{ $sortOrder == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $sale->employee->name }}
                                @if ($sale->employee->deleted_at)
                                    <span class="text-red-500 text-sm">(ลาออก)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($sale->payment_type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $sale->saleDetails->sum(function ($detail) {
                                    return $detail->menu ? $detail->menu->menu_price * $detail->quantity : 0;
                                }) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 text-center">
                                <a href="{{ route('sales.show', $sale) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    ดูรายละเอียด
                                </a>
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" id="delete-form-{{ $sale->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $sale->id }})" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    @empty
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="6">
                                ไม่พบประวัติการขาย
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $sales->links() }}
        </div>
    </div>
</div>

@endsection

