@extends('layouts.app')
@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Menu Allocation Details</h1>

        <!-- แสดงวัตถุดิบที่ขาดรวม -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-red-600">วัตถุดิบที่ต้องสั่งซื้อเพิ่มเติม</h3>
            @if(!empty($totalMissingIngredients))
                <ul>
                    @foreach($totalMissingIngredients as $ingredientName => $missingAmount)
                        <li>{{ $ingredientName }}: ขาด {{ $missingAmount }} หน่วย</li>
                    @endforeach
                </ul>
            @else
                <p class="text-green-600">วัตถุดิบครบถ้วน ไม่ต้องสั่งซื้อเพิ่มเติม</p>
            @endif
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Date: {{ $allocation->allocation_date }}</h2>
        </div>

        <div class="mb-4">
            <h3 class="text-lg font-semibold">Allocation Details</h3>
            <ul>
                @foreach($allocation->allocationDetails as $detail)
                    <li>
                        {{ $detail->menu->menu_name }}

                        <!-- Ensure $missingIngredients[$detail->menu->id] exists and is an array -->
                        @if (isset($missingIngredients[$detail->menu->id]) && is_array($missingIngredients[$detail->menu->id]))
                            <ul class="ml-4">
                                @foreach($missingIngredients[$detail->menu->id] as $ingredient)
                                    <li class="text-red-600">
                                        วัตถุดิบ {{ $ingredient['ingredient_name'] }} ขาด {{ $ingredient['missing_amount'] }} {{ $ingredient['ingredient_unit'] }} (ต้องการ {{ $ingredient['required_amount'] }} หน่วย)
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="ml-4 text-green-600">วัตถุดิบครบถ้วน</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
