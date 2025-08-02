@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">แดชบอร์ดพนักงาน</h1>
            <!-- Quick Stats Section -->
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
                    <p
                        class="text-sm {{ $monthlySales['current'] > $monthlySales['previous'] ? 'text-green-500' : 'text-red-500' }} mt-1">
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
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายรายสัปดาห์ (สัปดาห์ที่
                        {{ now()->weekOfYear }})</h2>
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
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">ยอดขายแยกตามช่องทางชำระเงิน
                        ({{ now()->format('d-m-Y') }})</h2>
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

            <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-5">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png') }}"
                            alt="Profile Picture" class="w-20 h-20 rounded-full mr-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">สวัสดี, คุณ {{ Auth::user()->name }}</h2>
                            <p class="text-gray-600">ประเภทการจ้างงาน: {{ Auth::user()->employment_type }}</p>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">ปุ่มด่วน</h1>
                    <!-- ปุ่มรายการด่วน -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                        <a href="{{ route('orders.create') }}"
                            class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition duration-200 text-center">
                            บันทึกการสั่งซื้อวัตถุดิบ
                        </a>
                        <a href="{{ route('sales.create') }}"
                            class="bg-green-500 text-white py-2 px-4 rounded-lg shadow hover:bg-green-600 transition duration-200 text-center">
                            บันทึกการขาย
                        </a>
                        <a href="{{ route('profile.profile_edit', $user->id) }}"
                            class="bg-yellow-500 text-white py-2 px-4 rounded-lg shadow hover:bg-yellow-600 transition duration-200 text-center">
                            แก้ไขข้อมูลส่วนตัว
                        </a>
                        <a href="{{ route('profile.change_password', $user->id) }}"
                            class="bg-red-500 text-white py-2 px-4 rounded-lg shadow hover:bg-red-600 transition duration-200 text-center">
                            เปลี่ยนรหัสผ่าน
                        </a>
                    </div>

                    <!-- Latest Sales Section -->
                    <div class="mt-10">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">การขายล่าสุด</h2>
                        <ul class="bg-white shadow-lg rounded-lg p-6">
                            @forelse ($latestSales as $sale)
                                <li class="mb-3">
                                    <span class="text-gray-800 font-semibold">ไอดีขาย {{ $sale->id }} : </span> จำนวน
                                    {{ $sale->quantity }} ที่ เมื่อ
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->diffForHumans() }}
                                </li>
                            @empty
                                <li class="text-gray-500">ยังไม่มีข้อมูลการขาย</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Latest Purchase Orders Section -->
                    <div class="mt-10">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">การสั่งซื้อล่าสุด</h2>
                        <ul class="bg-white shadow-lg rounded-lg p-6">
                            @forelse ($latestOrders as $order)
                                <li class="mb-3">
                                    <span class="text-gray-800 font-semibold">สั่งซื้อวัตถุดิบ
                                        {{ $order->ingredient_name }}:</span> ไอดีสั่งซื้อวัตถุดิบ : {{ $order->id }} เมื่อ
                                    {{ \Carbon\Carbon::parse($order->order_date)->diffForHumans() }}
                                </li>
                            @empty
                                <li class="text-gray-500">ยังไม่มีข้อมูลการสั่งซื้อ</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
