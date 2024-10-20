@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[['label' => 'ระบบช่วยเหลือจัดสรรเมนู', 'url' => route('allocations.index')], ['label' => 'รายละเอียดการจัดสรรของวันที่ ' . $allocation->allocation_date]]" />
        <h1 class="text-3xl font-bold mb-8 text-gray-800">รายละเอียดการจัดสรรของวันที่ {{ $allocation->allocation_date }}</h1>

        <form action="{{ route('allocations.show', $allocation) }}" method="GET" class="mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    ระบุจำนวนการผลิต
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($allocation->allocationDetails as $detail)
                        <div class="flex items-center space-x-2">
                            <label for="production_{{ $detail->menu->id }}" class="text-sm font-medium text-gray-700">{{ $detail->menu->menu_name }}:</label>
                            <input type="number" id="production_{{ $detail->menu->id }}" name="productionQuantities[{{ $detail->menu->id }}]" value="{{ $productionQuantities[$detail->menu->id] ?? 1 }}" min="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        คำนวณใหม่
                    </button>
                </div>
            </div>
        </form>

        <!-- วัตถุดิบที่ขาดรวม -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-semibold text-red-600 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                วัตถุดิบที่ต้องสั่งซื้อเพิ่มเติม
            </h3>
            @if(!empty($totalMissingIngredients))
                <ul class="space-y-2">
                    @foreach($totalMissingIngredients as $ingredientName => $missingAmount)
                        <li class="flex items-center text-red-600 bg-red-50 p-3 rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                            <span class="font-medium">{{ $ingredientName }}:</span>
                            <span class="ml-2">ขาด {{ $missingAmount }} หน่วย</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="flex items-center text-green-600 bg-green-50 p-4 rounded-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="font-medium">วัตถุดิบครบถ้วน ไม่ต้องสั่งซื้อเพิ่มเติม</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                รายละเอียดการจัดสรร
            </h3>
            <ul class="space-y-6">
                @foreach($allocation->allocationDetails as $detail)
                    <li class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                        <div class="font-medium text-lg text-gray-800 mb-2">
                            {{ $detail->menu->menu_name }}
                            (จำนวนที่ผลิต: {{ $productionQuantities[$detail->menu->id] ?? 1 }})
                        </div>

                        @if (isset($missingIngredients[$detail->menu->id]) && is_array($missingIngredients[$detail->menu->id]))
                            <ul class="ml-6 space-y-2">
                                @foreach($missingIngredients[$detail->menu->id] as $ingredient)
                                    <li class="text-red-600 bg-red-50 p-3 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            <span>
                                                วัตถุดิบ <span class="font-medium">{{ $ingredient['ingredient_name'] }}</span>
                                                (ต้องการ <span class="font-medium">{{ $ingredient['required_amount'] }}</span> {{ $ingredient['ingredient_unit'] }})
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="ml-6 text-green-600 bg-green-50 p-3 rounded-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="font-medium">วัตถุดิบครบถ้วน</span>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection