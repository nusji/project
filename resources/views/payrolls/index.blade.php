<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')

@section('title', 'จัดการวัตถุดิบ')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบเงินเดือน', 'url' => route('payrolls.index')], ['label' => '']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-2">ระบบจัดการเงินเดือน</h2>

        <div class="text-sm font-md text-gray-800 mb-2">
            <form method="GET" action="{{ route('payrolls.index') }}" class="text-right">
                <label for="month" class="font-medium text-gray-700">เดือน</label>
                <select name="month">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == $currentMonth ? 'selected' : '' }}>{{ $i }}
                        </option>
                    @endfor
                </select>

                <label for="year" class="font-medium text-gray-700">ปี</label>
                <select name="year">
                    @for ($year = now()->year - 5; $year <= now()->year; $year++)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                            {{ $year }}</option>
                    @endfor
                </select>

                <button type="submit"
                    class="px-4 py-1 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">ค้นหา</button>
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <!-- จำนวนพนักงานทั้งหมด -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold text-gray-700">พนักงานทั้งหมด</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $totalEmployees }}</p>
            </div>

            <!-- จำนวนรายการจ่ายเงินเดือนทั้งหมด -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">พนักงานที่ยังไม่ได้รับเงินเดือนเดือนนี้</h2>
                @if ($unpaidEmployees->isEmpty())
                    <p class="text-green-500">พนักงานทุกคนได้รับเงินเดือนแล้วในเดือนนี้</p>
                @else
                    <ul class="list-disc pl-6 text-red-500">
                        @foreach ($unpaidEmployees as $employee)
                            <li>{{ $employee->name }} - ตำแหน่ง: {{ $employee->employment_type }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>


            <!-- ยอดจ่ายเงินเดือนทั้งหมด -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold text-gray-700">ยอดจ่ายเงินเดือน เดือน</h2>
                <p class="text-lg">{{ number_format($totalPaidMonth, 2) }} บาท</p>
            </div>

        </div>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('payrolls.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    สร้างรายการจ่ายเงินเดือนใหม่
                </a>
                <a href="{{ route('salaries.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    จัดการฐานเงินเดือน
                </a>
            </div>
            <form action="#" method="GET" class="flex-grow md:max-w-md">
                <div class="relative">
                    <input type="text" name="search" placeholder="ค้นหาวัตถุดิบ..." value="{{ request('search') }}"
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
        <h1 class="text-xl font-bold text-gray-800 mb-4">รายการจ่ายเงินเดือนล่าสุด</h1>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            วันที่ชำระ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ชื่อพนักงาน
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ฐานเงินเดือน
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            โบนัส
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            หักค่าใช้จ่าย
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ยอดเงินจ่ายสุทธิ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            การดำเนินการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($payrolls->isEmpty())
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                ไม่มีข้อมูลพนักงานในระบบ
                            </td>
                        </tr>
                    @else
                        @foreach ($payrolls as $payroll)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $payroll->payment_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $payroll->employee->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $payroll->employee->salary }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($payroll->bonus, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $payroll->deductions }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $payroll->net_salary }}
                                </td>
                                <td>
                                    @if (auth()->user()->role === 'owner')
                                        <a href="{{ route('payrolls.edit', $payroll) }}"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            แก้ไขข้อมูล
                                        </a>
                                        <form action="{{ route('payrolls.destroy', $payroll->id) }}" method="POST"
                                            class="inline" id="delete-form-{{ $payroll->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $payroll->id }})"
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
                                            function confirmDelete(payrollId) {
                                                Swal.fire({
                                                    title: 'คุณแน่ใจหรือไม่?',
                                                    text: "กรุณาพิมพ์คำว่า 'ลบพนักงาน' เพื่อยืนยันการลบข้อมูล!",
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
                                                        document.getElementById(`delete-form-${payrollId}`).submit();
                                                    }
                                                });
                                            }
                                        </script>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            {{ $payrolls->links() }}
        </div>
    </div>
@endsection
