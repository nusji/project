<!-- resources/views/orders/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[
            ['label' => 'ระบบสั่งซื้อวัตถุดิบ', 'url' => route('orders.index')],
            ['label' => 'สร้างรายการสั่งซื้อใหม่'],
        ]" />

        <h2 class="text-2xl font-bold text-gray-800 mb-2">สร้างรายการสั่งซื้อใหม่</h2>
        <p class=" text-sm text-gray-500">กรอกข้อมูลด้านล่างเพื่อสร้างรายการสั่งซื้อใหม่</p>
    </div>
    <div class="p-6">
        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="order_date" class="block mb-2">วันที่และเวลา</label>
                <input type="datetime-local" name="order_date" id="order_date" class="w-full border rounded px-3 py-2"
                    value="{{ old('order_date') }}" required>
            </div>
            @error('order_date')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <div class="mb-4">
                <label for="order_detail" class="block mb-2">รายละเอียด</label>
                <textarea name="order_detail" id="order_detail" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('order_detail') }}</textarea>
            </div>
            @error('order_detail')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <div class="mb-4">
                <label for="order_receipt" class="block mb-2">ใบเสร็จ (รูปภาพ)</label>
                <input type="file" name="order_receipt" id="order_receipt" class="w-full border rounded px-3 py-2"
                    accept="image/*">
                @if (isset($order) && $order->order_receipt)
                    <img src="{{ asset('storage/' . $order->order_receipt) }}" alt="ใบเสร็จปัจจุบัน" class="mt-2 max-w-xs">
                    <p class="text-sm text-gray-600">อัปโหลดรูปใหม่เพื่อเปลี่ยนใบเสร็จ</p>
                @endif
            </div>
            @error('order_receipt')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <div id="ingredients">
                <h2 class="text-xl font-bold mb-2">รายการวัตถุดิบ</h2>
                <div class="ingredient-item mb-4">
                    <select name="ingredients[0][id]" class="border rounded px-3 py-2 mr-2" required>
                        <option value="">เลือกวัตถุดิบ</option>
                        @foreach ($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->ingredient_name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="ingredients[0][quantity]" placeholder="จำนวน"
                        class="border rounded px-3 py-2 mr-2" required>
                    <input type="number" name="ingredients[0][price]" placeholder="ราคา"
                        class="border rounded px-3 py-2 mr-2" required>
                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        onclick="removeIngredient(this)">ลบ</button>
                </div>
            </div>
            @error('ingredients')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <button type="button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4"
                onclick="addIngredient()">เพิ่มวัตถุดิบ</button>

            <div>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">บันทึกรายการสั่งซื้อ</button>
            </div>
        </form>
    </div>

    <script>
        let ingredientCount = 1;

        function addIngredient() {
            const ingredientsDiv = document.getElementById('ingredients');
            const newItem = document.createElement('div');
            newItem.className = 'ingredient-item mb-4';
            newItem.innerHTML = `
                <select name="ingredients[${ingredientCount}][id]" class="border rounded px-3 py-2 mr-2" required>
                    <option value="">เลือกวัตถุดิบ</option>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->ingredient_name }}</option>
                    @endforeach
                </select>
                <input type="number" name="ingredients[${ingredientCount}][quantity]" placeholder="จำนวน" class="border rounded px-3 py-2 mr-2" required>
                <input type="number" name="ingredients[${ingredientCount}][price]" placeholder="ราคา" class="border rounded px-3 py-2 mr-2" required>
                <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="removeIngredient(this)">ลบ</button>
            `;
            ingredientsDiv.appendChild(newItem);
            ingredientCount++;
        }

        function removeIngredient(button) {
            button.parentElement.remove();
        }

        // กำหนดค่า default ของฟิลด์วันที่และเวลาเป็นปัจจุบัน
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const datetimeLocal = `${year}-${month}-${day}T${hours}:${minutes}`;

            document.getElementById('order_date').value = datetimeLocal;
        });
    </script>
@endsection
