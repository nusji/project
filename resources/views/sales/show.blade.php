@extends('layouts.pos')
@section('content')
    <div class="container mx-auto px-4 py-8 ml-5">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบจัดการขาย', 'url' => route('sales.index')], ['label' => 'แสดงรายการ']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">รายละเอียดการขาย</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg m-5">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">รายละเอียดการขาย</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">รหัสการขาย: #{{ $sale->id }}</p>
                </div>
                <a href="{{ route('sales.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    กลับไปหน้ารายการขาย
                </a>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">วันที่ขาย</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">ชำระด้วย</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $sale->payment_type }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">พนักงานทำรายการขาย</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $sale->employee->name }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">รายการสินค้า</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @forelse($sale->saleDetails as $detail)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <span class="ml-2 flex-1 w-0 truncate">
                                                @if ($detail->menu)
                                                    {{ $detail->menu->menu_name }}
                                                @else
                                                    <span class="text-red-500">รายการไม่พร้อมใช้งาน</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-4">
                                            <span class="text-gray-500">จำนวน: {{ $detail->quantity }}</span>
                                            <span class="text-gray-900 font-medium">
                                                @if ($detail->menu)
                                                    {{ number_format($detail->menu->menu_price * $detail->quantity, 2) }}
                                                    บาท
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="pl-3 pr-4 py-3 text-sm text-gray-500">ไม่พบรายการสินค้า</li>
                                @endforelse
                            </ul>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">ยอดรวมทั้งหมด</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-bold">
                            {{ number_format($sale->saleDetails->sum(function ($detail) {return $detail->menu ? $detail->menu->menu_price * $detail->quantity : 0;}),2) }}
                            บาท
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection
