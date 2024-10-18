// resources/views/payrolls/show.blade.php
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Payroll Details</h1>
    
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">Employee:</strong>
            <p>{{ $payroll->employee->name }}</p>
        </div>
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">Bonus:</strong>
            <p>{{ number_format($payroll->bonus, 2) }}</p>
        </div>
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">Deductions:</strong>
            <p>{{ number_format($payroll->deductions, 2) }}</p>
        </div>
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">Net Salary:</strong>
            <p>{{ number_format($payroll->net_salary, 2) }}</p>
        </div>
        <div class="mb-4">
            <strong class="block text-gray-700 text-sm font-bold mb-2">Payment Date:</strong>
            <p>{{ $payroll->payment_date }}</p>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('payrolls.edit', $payroll) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit
            </a>
            <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection