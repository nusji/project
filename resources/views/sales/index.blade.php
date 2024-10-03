@extends('layouts.pos')
@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Sales History</h1>

    <div class="mb-4">
        <a href="{{ route('sales.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            New Sale
        </a>
    </div>

    <table class="min-w-full bg-white">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-2 px-4 border-b text-left">ID</th>
                <th class="py-2 px-4 border-b text-left">Date</th>
                <th class="py-2 px-4 border-b text-left">Employee</th>
                <th class="py-2 px-4 border-b text-left">Payment Type</th>
                <th class="py-2 px-4 border-b text-left">Total</th>
                <th class="py-2 px-4 border-b text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td class="py-2 px-4 border-b">{{ $sale->id }}</td>
                <td class="py-2 px-4 border-b">{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                <td class="py-2 px-4 border-b">{{ $sale->employee->name }}</td>
                <td class="py-2 px-4 border-b">{{ ucfirst($sale->payment_type) }}</td>
                <td class="py-2 px-4 border-b">
                    ${{ $sale->saleDetails->sum(function($detail) { return $detail->price * $detail->quantity; }) }}
                </td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('pos.show', $sale) }}" class="text-blue-500 hover:underline">View Details</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $sales->links() }}
    </div>
</div>
@endsection