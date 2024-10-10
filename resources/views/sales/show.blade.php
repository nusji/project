@extends('layouts.pos')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Sale Details</h1>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h2 class="text-xl font-semibold text-gray-700">Sale ID: {{ $sale->id }}</h2>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Sale Date</span>
                    <span class="text-gray-900">{{ $sale->sale_date }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Payment Type</span>
                    <span class="text-gray-900">{{ $sale->payment_type }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500">Employee</span>
                    <span class="text-gray-900">{{ $sale->employee->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-700">Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Menu Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sale->saleDetails as $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($detail->menu)
                                {{ $detail->menu->menu_name }}
                            @else
                                <span class="text-red-500">Menu not available</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $detail->quantity }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('sales.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Back to Sales
        </a>
    </div>
</div>
@endsection