@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[
            ['label' => 'ระบบช่วยเหลือจัดสรรเมนู', 'url' => route('allocations.index')],
            ['label' => 'รายละเอียดการจัดสรรของวันที่ ' . $allocation->allocation_date],
        ]" />

        <h1 class="text-3xl font-bold mb-8 text-gray-800">รายละเอียดการจัดสรรของวันที่ {{ $allocation->allocation_date }}
        </h1>

        <!-- Container สำหรับคำอธิบายและปุ่ม -->
        <div class="flex items-center justify-end space-x-4 mb-6">
            <!-- คำอธิบาย -->
            <span class="text-sm text-gray-700">ถ้าอยากผลิตเยอะ ให้กดตรงนี้:</span>

            <!-- ปุ่มเปิด Modal -->
            <button type="button" onclick="openProductionModal()"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-md text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400 shadow-lg transform hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                ระบุจำนวนการผลิต
            </button>
        </div>

        <!-- Modal -->
        <div id="productionModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">ระบุจำนวนการผลิต</h3>
                            <button type="button" onclick="closeProductionModal()"
                                class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">ปิด</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <form action="{{ route('allocations.show', $allocation) }}" method="GET">
                            <div class="grid gap-4 max-h-96 overflow-y-auto">
                                @foreach ($allocation->allocationDetails as $detail)
                                    <div class="flex items-center justify-between">
                                        <label for="production_{{ $detail->menu->id }}"
                                            class="block text-sm font-medium text-gray-700">
                                            {{ $detail->menu->menu_name }}
                                        </label>
                                        <input type="number" id="production_{{ $detail->menu->id }}"
                                            name="productionQuantities[{{ $detail->menu->id }}]"
                                            value="{{ $productionQuantities[$detail->menu->id] ?? 1 }}" min="1"
                                            class="w-32 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                @endforeach
                            </div>

                            <!-- Modal Footer -->
                            <div class="px-6 py-4 border-t bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                                <button type="button" onclick="closeProductionModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    ยกเลิก
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    คำนวณใหม่
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function openProductionModal() {
                document.getElementById('productionModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeProductionModal() {
                document.getElementById('productionModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // ปิด Modal เมื่อคลิกพื้นหลัง
            document.getElementById('productionModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeProductionModal();
                }
            });
        </script>

        <!-- วัตถุดิบที่ขาดรวม -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-red-600 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                วัตถุดิบที่ต้องสั่งซื้อเพิ่มเติม
            </h3>
            @if (!empty($totalMissingIngredients))
                <!-- เปลี่ยนจาก ul ที่ใช้ space-y-2 เป็น Grid Layout -->
                <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($totalMissingIngredients as $ingredientName => $missingInfo)
                        <li class="flex items-start text-red-600 bg-red-50 p-2 rounded-lg">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                            <div>
                                <span class="block font-medium">{{ $ingredientName }} : ขาด {{ $missingInfo['missing_amount'] }} {{ $missingInfo['unit'] }} </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="flex items-center text-green-600 bg-green-50 p-4 rounded-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="font-medium">วัตถุดิบครบถ้วน ไม่ต้องสั่งซื้อเพิ่มเติม</p>
                </div>
            @endif
        </div>


        <!-- รายละเอียดการใช้วัตถุดิบของแต่ละเมนู -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                รายละเอียดการใช้วัตถุดิบ
            </h3>
            <ul class="space-y-6">
                @foreach ($allocation->allocationDetails as $detail)
                    <li class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                        <div class="font-medium text-lg  mb-2 ">
                            {{ $detail->menu->menu_name }}
                            <span class="text-white-700 bg-green-200 px-2 rounded-lg border-2 border-green-500 ">
                                จำนวนที่ผลิต: {{ $productionQuantities[$detail->menu->id] ?? 1 }} กิโลกรัม
                            </span>

                        </div>
                        <ul class="ml-6 space-y-2">
                            @foreach ($ingredientUsage[$detail->menu->id] as $usage)
                                <li
                                    class="flex items-center {{ $usage['missing_amount'] > 0 ? 'text-red-600' : 'text-green-600' }} bg-gray-50 p-2 rounded-md">
                                    <span class="font-medium mr-2">{{ $usage['ingredient_name'] }}:</span>
                                    <span>ต้องการ {{ $usage['required_amount'] }} {{ $usage['ingredient_unit'] }},</span>
                                    <span class="ml-2">ใช้ได้ {{ $usage['used_amount'] }}
                                        {{ $usage['ingredient_unit'] }},</span>
                                    @if ($usage['missing_amount'] > 0)
                                        <span class="ml-2 text-red-600">ขาด {{ $usage['missing_amount'] }}
                                            {{ $usage['ingredient_unit'] }}</span>
                                    @else
                                        <span class="ml-2 text-green-600">เพียงพอ</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
