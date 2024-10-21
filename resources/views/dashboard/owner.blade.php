@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">แดชบอร์ดเจ้าของร้าน</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- ยอดขายรายวัน -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายรายวัน ({{ now()->format('d-m-Y') }})</h2>
                <p class="text-4xl font-bold text-blue-600">{{ number_format($dailySales, 2) }} บาท</p>
            </div>

            <!-- ยอดขายรายเดือน -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายรายเดือน ({{ now()->format('F Y') }})</h2>
                <p class="text-4xl font-bold text-green-600">{{ number_format($monthlySales['current'], 2) }} บาท</p>
                <p class="text-sm text-gray-500 mt-2">
                    เดือนก่อนหน้า: {{ number_format($monthlySales['previous'], 2) }} บาท
                </p>
                <p class="text-sm {{ $monthlySales['current'] > $monthlySales['previous'] ? 'text-green-500' : 'text-red-500' }} mt-1">
                    @if ($monthlySales['previous'] > 0)
                        {{ $monthlySales['current'] > $monthlySales['previous'] ? '▲' : '▼' }}
                        {{ number_format(abs($monthlySales['current'] - $monthlySales['previous']), 2) }} บาท
                        ({{ $monthlySales['current'] > $monthlySales['previous'] ? 'เพิ่มขึ้น' : 'ลดลง' }})
                    @else
                        - ไม่สามารถเปรียบเทียบได้
                    @endif
                </p>
            </div>

            <!-- ยอดขายรายสัปดาห์ -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายรายสัปดาห์ (สัปดาห์ที่ {{ now()->weekOfYear }})</h2>
                <ul class="space-y-2">
                    @foreach ($weeklySales as $sale)
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">{{ $sale->date }}</span>
                            <span class="font-semibold">{{ number_format($sale->total, 2) }} บาท</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- ยอดขายแยกตามช่องทางชำระเงิน -->
            <div class="bg-white rounded-lg shadow-md p-6 col-span-full">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายแยกตามช่องทางชำระเงิน ({{ now()->format('d-m-Y') }})</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($paymentMethodSales as $payment)
                        <div class="bg-gray-100 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-700">{{ $payment->payment_type }}</h3>
                            <p class="text-lg font-bold text-blue-600">{{ number_format($payment->total, 2) }} บาท</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-5">
            <!-- เมนูขายดีสุด -->
            <div class="bg-white rounded-lg shadow-md p-6 col-span-full md:col-span-1">
                <form action="{{ route('dashboard.owner') }}" method="GET" class="mb-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-grow">
                            <label for="menu_limit" class="block text-sm font-medium text-gray-700 mb-1">จำนวนเมนูขายดีที่สุดวันนี้</label>
                            <input type="number" name="menu_limit" id="menu_limit" value="{{ $menuLimit }}" min="1"
                                   class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">แสดงข้อมูล</button>
                    </div>
                </form>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">เมนูขายดีสุด {{ $menuLimit }} อันดับวันนี้</h2>
                <ul class="space-y-2">
                    @foreach ($bestSellingMenu as $menu)
                        <li class="flex justify-between items-center bg-gray-100 rounded-lg p-3">
                            <span class="font-semibold">{{ $menu->menu_name }}</span>
                            <span class="text-blue-600 font-bold">{{ $menu->total_quantity }} ครั้ง</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- เมนูที่ขายดีสุดตลอดกาล 5 อันดับ -->
            <div class="bg-white rounded-lg shadow-md p-6 col-span-full md:col-span-1">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">เมนูที่ขายดีสุดตลอดกาล 5 อันดับ</h2>
                <ul class="space-y-2">
                    @foreach ($topSellingMenus as $menu)
                        <li class="flex justify-between items-center bg-gray-100 rounded-lg p-3">
                            <span class="font-semibold">{{ $menu->menu_name }}</span>
                            <span class="text-blue-600 font-bold">{{ $menu->sale_details_count }} ครั้ง</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-5">
            <!-- เมนูที่จะผลิตวันระบุ -->
            <div class="bg-white rounded-lg shadow-md p-6 col-span-full md:col-span-1">
                <form action="{{ route('dashboard.owner') }}" method="GET" class="mb-6">
                    <div class="flex flex-col space-y-2">
                        <label for="selected_date" class="block text-sm font-medium text-gray-700">เลือกวันที่</label>
                        <input type="date" name="selected_date" id="selected_date"
                               value="{{ request('selected_date', now()->addDay()->toDateString()) }}"
                               class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">แสดงเมนู</button>
                    </div>
                </form>
                <h2 class="text-xl font-semibold text-gray-700 mb-4">เมนูที่จะผลิตในวันที่ {{ Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</h2>
                <ul class="space-y-2">
                    @if (!empty($menuAllocations) && count($menuAllocations) > 0)
                        @foreach ($menuAllocations as $allocation)
                            @if (!empty($allocation->allocationDetails))
                                @foreach ($allocation->allocationDetails as $detail)
                                    @if (!is_null($detail->menu))
                                        <li class="bg-gray-100 rounded-lg p-3">{{ $detail->menu->menu_name }}</li>
                                    @else
                                        <li class="bg-gray-100 rounded-lg p-3 text-gray-500">ข้อมูลเมนูไม่พร้อมใช้งาน</li>
                                    @endif
                                @endforeach
                            @else
                                <li class="bg-gray-100 rounded-lg p-3 text-gray-500">ไม่มีเมนูที่ถูกจัดสรร</li>
                            @endif
                        @endforeach
                    @else
                        <li class="bg-gray-100 rounded-lg p-3 text-gray-500">ยังไม่ได้จัดสรรเมนูที่จะผลิตสำหรับวันที่นี้</li>
                    @endif
                </ul>
            </div>

            <!-- สรุปการผลิตวันนี้ -->
            <div class="bg-white rounded-lg shadow-md p-6 col-span-full md:col-span-1">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">สรุปการผลิตวันนี้</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($productionSummary as $production)
                        @foreach ($production->productionDetails as $detail)
                            <div class="bg-gray-100 rounded-lg p-4">
                                <h3 class="font-bold text-gray-800 mb-2">{{ $detail->menu->menu_name }}</h3>
                                <p class="text-gray-600">ปริมาณผลิต: <span class="font-semibold">{{ $detail->quantity }} กิโลกรัม</span></p>
                                <p class="text-gray-600">คงเหลือ: <span class="font-semibold">{{ $detail->remaining_amount }}</span></p>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-5">
            <!-- การจัดการวัตถุดิบ -->
            <div class="bg-white rounded-lg shadow-md p-6 col-span-full">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">การจัดการวัตถุดิบ</h2>

                <!-- วัตถุดิบที่เหลือน้อย -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-yellow-600 mb-4">วัตถุดิบที่เหลือน้อย</h3>
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <ul class="space-y-2">
                            @foreach ($lowStockProducts as $product)
                                <li class="flex justify-between items-center">
                                    <span class="font-medium">{{ $product->ingredient_name }}</span>
                                    <span>
                                        เหลือ <span class="font-semibold">{{ $product->ingredient_stock }} {{ $product->ingredient_unit }}</span>
                                        (ขั้นต่ำ {{ $product->minimum_quantity }} {{ $product->ingredient_unit }})
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- วัตถุดิบที่ต้องสั่งซื้อเพิ่ม -->
                <div>
                    <h3 class="text-lg font-semibold text-red-600 mb-4">วัตถุดิบที่ต้องสั่งซื้อเพิ่ม</h3>
                    <div class="bg-red-50 rounded-lg p-4">
                        <ul class="space-y-2">
                            @foreach ($ingredientsToOrder as $ingredient)
                                <li class="flex justify-between items-center">
                                    <span class="font-medium">{{ $ingredient->ingredient_name }}</span>
                                    <span>
                                        เหลือ <span class="font-semibold">{{ $ingredient->ingredient_stock }} {{ $ingredient->ingredient_unit }}</span>
                                        (ขั้นต่ำ {{ $ingredient->minimum_quantity }} {{ $ingredient->ingredient_unit }})
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection