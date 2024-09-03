<!-- resources/views/orders/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">รายละเอียดการสั่งซื้อ</h1>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">วันที่สั่งซื้อ:</strong>
            <p>{{ $order->order_date }}</p>
        </div>
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">รายละเอียด:</strong>
            <p>{{ $order->order_detail }}</p>
        </div>
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">ใบเสร็จ:</strong>
            <p>{{ $order->order_receipt }}</p>
        </div>
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">ผู้สั่งซื้อ:</strong>
            <p>{{ $order->employee->name }}</p>
        </div>
        
        <h2 class="text-xl font-bold mb-2">รายการวัตถุดิบ</h2>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">วัตถุดิบ</th>
                    <th class="py-2 px-4 border-b">จำนวน</th>
                    <th class="py-2 px-4 border-b">ราคา</th>
                    <th class="py-2 px-4 border-b">รวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $detail)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $detail->ingredient->ingredient_name }}</td>
                    <td class="py-2 px-4 border-b">{{ $detail->quantity }}</td>
                    <td class="py-2 px-4 border-b">{{ number_format($detail->price, 2) }}</td>
                    <td class="py-2 px-4 border-b">{{ number_format($detail->quantity * $detail->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="py-2 px-4 border-b text-right font-bold">รวมทั้งหมด:</td>
                    <td class="py-2 px-4 border-b font-bold">
                        {{ number_format($order->orderDetails->sum(function($detail) {
                            return $detail->quantity * $detail->price;
                        }), 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('orders.edit', $order) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">แก้ไข</a>
        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')">ลบ</button>
        </form>
        <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">กลับไปหน้ารายการ</a>
    </div>
</div>
@endsection