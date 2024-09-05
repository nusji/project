@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <x-breadcrumb :paths="[
        ['label' => 'ระบบสั่งซื้อวัตถุดิบ', 'url' => route('orders.index')],
        ['label' => 'รหัสสั่งซื้อที่ '. $order->id]
    ]" />
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-800 text-white">
                <h1 class="text-2xl font-bold">ใบเสร็จการสั่งซื้อ</h1>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-sm text-gray-600">วันที่สั่งซื้อ</p>
                        <p class="font-semibold">{{ $order->order_date }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">เลขที่รายการสั่งซื้อ</p>
                        <p class="font-semibold">{{ $order->id }}</p>
                    </div>
                </div>
                
                @if($order->order_receipt)
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">รูปใบเสร็จ</h2>
                    <img src="{{ asset('storage/' . $order->order_receipt) }}" alt="รูปโปรไฟล์" class="w-32 h-32 object-cover rounded-full">
                </div>
            @endif

                <div class="mb-6">
                    <p class="text-sm text-gray-600">ผู้สั่งซื้อ</p>
                    <p class="font-semibold">{{ $order->employee->first_name }}</p>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-lg font-bold mb-2">รายละเอียด</h2>
                    <p class="text-gray-700">{{ $order->order_detail }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-bold mb-2">รายการวัตถุดิบ</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-4 py-2 text-left">วัตถุดิบ</th>
                                    <th class="px-4 py-2 text-right">จำนวน</th>
                                    <th class="px-4 py-2 text-left">หน่วย</th>
                                    <th class="px-4 py-2 text-right">ราคาซื้อ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $detail)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $detail->ingredient->ingredient_name }}</td>
                                    <td class="px-4 py-2 text-right">{{ $detail->quantity }}</td>
                                    <td class="px-4 py-2">{{ $detail->ingredient->ingredient_unit }}</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($detail->price, 2) }} บาท</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="font-bold">
                                    <td colspan="3" class="px-4 py-2 text-right">รวมทั้งหมด:</td>
                                    <td class="px-4 py-2 text-right">
                                        {{ number_format($order->orderDetails->sum('price'), 2) }} บาท
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-100 flex justify-between">
                <a href="{{ route('orders.edit', $order) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300">แก้ไข</a>
                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')">ลบ</button>
                </form>
                <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">กลับไปหน้ารายการ</a>
            </div>
        </div>
    </div>
</div>
@endsection