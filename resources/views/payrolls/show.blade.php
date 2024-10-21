@extends('layouts.app')

@section('title', 'รายละเอียดการจ่ายเงินเดือน')

@section('content')
<div class="container mx-auto px-4 py-0 max-w-4xl">
    <x-breadcrumb :paths="[['label' => 'ระบบเงินเดือน', 'url' => route('payrolls.index')], ['label' => 'ข้อมูลการจ่าย']]" />

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">รายละเอียดการจ่ายเงินเดือน</h1>
        <span class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
            {{ \Carbon\Carbon::parse($payroll->payment_date)->format('d/m/Y') }}
        </span>
    </div>
    
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- ข้อมูลพื้นฐาน -->
        <div class="border-b border-gray-200">
            <div class="px-6 py-4">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center">
                        <span class="text-xl font-bold text-gray-600">
                            {{ substr($payroll->employee->name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $payroll->employee->name }}</h2>
                        <p class="text-sm text-gray-600">รหัสพนักงาน: #{{ $payroll->employee->id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- รายละเอียดการจ่ายเงิน -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- ฐานเงินเดือน -->
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="text-sm font-medium text-gray-600">ฐานเงินเดือน</label>
                <p class="mt-1 text-lg font-semibold text-gray-900">
                    ฿{{ number_format($payroll->employee->salary, 2) }}
                </p>
            </div>

            <!-- โบนัส -->
            <div class="bg-green-50 rounded-lg p-4">
                <label class="text-sm font-medium text-green-600">โบนัส</label>
                <p class="mt-1 text-lg font-semibold text-green-900">
                    ฿{{ number_format($payroll->bonus, 2) }}
                </p>
            </div>

            <!-- หักค่าใช้จ่าย -->
            <div class="bg-red-50 rounded-lg p-4">
                <label class="text-sm font-medium text-red-600">หักค่าใช้จ่าย</label>
                <p class="mt-1 text-lg font-semibold text-red-900">
                    ฿{{ number_format($payroll->deductions, 2) }}
                </p>
            </div>

            <!-- ยอดเงินจ่ายสุทธิ -->
            <div class="bg-blue-50 rounded-lg p-4">
                <label class="text-sm font-medium text-blue-600">ยอดเงินจ่ายสุทธิ</label>
                <p class="mt-1 text-lg font-semibold text-blue-900">
                    ฿{{ number_format($payroll->net_salary, 2) }}
                </p>
            </div>
        </div>

        <!-- รายละเอียดการชำระเงิน -->
        <div class="border-t border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <label class="text-sm font-medium text-gray-600">วิธีการชำระเงิน</label>
                    <p class="mt-1 text-gray-900">{{ $payroll->payment_method }}</p>
                </div>

                <!-- สลิปเงินเดือน -->
                @if($payroll->slip_image)
                    <a href="{{ asset('storage/' . $payroll->slip_image) }}" 
                       target="_blank" 
                       class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" />
                        </svg>
                        <span>ดูสลิปเงินเดือน</span>
                    </a>
                @else
                    <span class="text-gray-500 text-sm">ไม่มีสลิปเงินเดือน</span>
                @endif
            </div>
        </div>

        <!-- ปุ่มดำเนินการ -->
        @if(auth()->user()->role === 'owner')
            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex justify-end space-x-4">
                <a href="{{ route('payrolls.edit', $payroll) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    แก้ไข
                </a>
                
                <form action="{{ route('payrolls.destroy', $payroll) }}" 
                      method="POST" 
                      class="inline" 
                      onsubmit="return confirmDelete();">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        ลบ
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@if(auth()->user()->role === 'owner')
    <script>
        function confirmDelete() {
            return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลการจ่ายเงินเดือนนี้?');
        }
    </script>
@endif
@endsection