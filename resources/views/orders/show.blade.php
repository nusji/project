@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">Order Details</h3>
                    <p>Order Date: {{ $order->order_date }}</p>
                    <p>Order Detail: {{ $order->order_detail }}</p>
                    <p>Order Receipt: {{ $order->order_receipt }}</p>
                    <p>Employee: {{ $order->employee->name }}</p>
                    <h3 class="text-lg font-medium mt-6 mb-4">Order Items</h3>
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">Ingredient</th>
                                <th class="px-4 py-2">Quantity</th>
                                <th class="px-4 py-2">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $orderDetail)
                            <tr>
                                <td class="border px-4 py-2">{{ $orderDetail->ingredient->ingredient_name }}</td>
                                <td class="border px-4 py-2">{{ $orderDetail->quantity }}</td>
                                <td class="border px-4 py-2">{{ $orderDetail->price }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection