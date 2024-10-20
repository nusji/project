@extends('layouts.app')
@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Menu Allocation Details</h1>
 
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Date: {{ $allocation->allocation_date }}</h2>
        </div>
 
        <div class="mb-4">
            <h3 class="text-lg font-semibold">Allocation Details</h3>
            <ul>
                @foreach($allocation->allocationDetails as $detail)
                    <li>{{ $detail->menu->menu_name }}</li>
                @endforeach
            </ul>
        </div>

        <!-- แสดงวัตถุดิบที่ขาด -->
        @if (!empty($missingIngredients))
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-red-600">วัตถุดิบที่ขาด</h3>
                <ul>
                    @foreach($missingIngredients as $ingredient)
                        <li>
                            {{ $ingredient['menu_name'] }}: วัตถุดิบ {{ $ingredient['ingredient_name'] }} ขาด {{ $ingredient['missing_amount'] }} หน่วย (ต้องการ {{ $ingredient['required_amount'] }} หน่วย)
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-green-600">วัตถุดิบครบถ้วน</h3>
            </div>
        @endif
    </div>
@endsection
