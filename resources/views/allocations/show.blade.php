@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Menu Allocation Details</h1>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Date: {{ $allocation->allocation_date }}</h2>
        </div>

        <!-- หากมีรายละเอียดเมนูเพิ่มเติม (เช่น menu_allocation_details) -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold">Allocation Details</h3>
            <ul>
                @foreach($allocationDetails as $detail)
                    <li>{{ $detail->menu->menu_name }}: {{ $detail->quantity }}</li>
                @endforeach
            </ul>
        </div>

        <a href="{{ route('allocations.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Back to Allocations</a>
    </div>
@endsection
