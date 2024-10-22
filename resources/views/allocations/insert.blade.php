@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <x-breadcrumb :paths="[
            ['label' => 'การจัดสรรเมนู', 'url' => route('allocations.index')],
            ['label' => 'รายละเอียดการจัดสรร', 'url' => route('allocations.show', $allocation)],
            ['label' => 'บันทึกการผลิต'],
        ]" />

        <h1 class="text-3xl font-bold mb-6">บันทึกการผลิตจากการจัดสรรเมนู</h1>

        @if ($errors->any())
            <div class="mb-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('alloproductions.store', $allocation) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="production_date" class="block text-sm font-medium text-gray-700">วันที่ผลิต</label>
                <input type="date" id="production_date" name="production_date"
                    value="{{ old('production_date', date('Y-m-d')) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
            </div>

            <div class="mb-4">
                <input type="text" id="production_detail" name="production_detail"
                    value="{{ old('production_detail', 'รายการผลิตที่สั่งผลิตด้วยระบบจัดสรรเมนูไอดีที่ ' . $allocation->id) }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>

            </div>

            <h2 class="text-xl font-semibold mb-4">รายละเอียดการผลิต</h2>

            <div class="grid grid-cols-1 gap-4">
                @foreach ($allocation->allocationDetails as $detail)
                    <div class="flex items-center justify-between">
                        <label for="quantity_{{ $detail->menu->id }}" class="block text-sm font-medium text-gray-700">
                            {{ $detail->menu->menu_name }}
                        </label>
                        <input type="number" step="0.1" id="quantity_{{ $detail->menu->id }}"
                            name="productionQuantities[{{ $detail->menu->id }}]"
                            value="{{ old('productionQuantities.' . $detail->menu->id, $productionQuantities[$detail->menu->id] ?? 1) }}"
                            min="0.1"
                            class="w-32 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" readonly>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-400">
                    บันทึกการผลิต
                </button>
            </div>
        </form>
    </div>
    <script>
        // ตรวจสอบว่ามีข้อมูลใน session หรือไม่ ถ้ามีให้แสดง SweetAlert
        @if (session('insufficientIngredients'))
            let insufficientIngredients = @json(session('insufficientIngredients'));
            let message = '';

            let menuMap = new Map();

            // จัดกลุ่มวัตถุดิบตามเมนู
            insufficientIngredients.forEach(item => {
                if (!menuMap.has(item.menu_name)) {
                    menuMap.set(item.menu_name, []); // สร้างรายการวัตถุดิบสำหรับเมนูใหม่
                }
                menuMap.get(item.menu_name).push(item); // เพิ่มวัตถุดิบเข้าไปในเมนูที่มีอยู่
            });

            // สร้างข้อความเพื่อแสดงผล
            menuMap.forEach((ingredients, menuName) => {
                message += `เมนู: ${menuName}<br>`; // แสดงชื่อเมนู
                ingredients.forEach((ingredient, index) => {
                    message +=
                        `วัตถุดิบที่ ${index + 1}: ${ingredient.ingredient_name}  ต้องการ: ${ingredient.required} ${ingredient.unit} คงเหลือ: ${ingredient.available} ${ingredient.unit}<br>`;
                });
                message += '<br>'; // เพิ่มบรรทัดว่างระหว่างเมนูแต่ละรายการ
            });

            Swal.fire({
                title: 'วัตถุดิบไม่เพียงพอ!',
                html: message, // เปลี่ยนจาก 'text' เป็น 'html'
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        @endif


        @if (session('success'))
            Swal.fire({
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        @endif
    </script>
@endsection
