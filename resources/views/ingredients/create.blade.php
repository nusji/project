@extends('layouts.app')

@section('title', 'เพิ่มวัตถุดิบใหม่')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">เพิ่มวัตถุดิบใหม่</h1>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <form action="{{ route('ingredients.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="ingredient_name" class="block mb-2 text-sm font-medium text-gray-700">ชื่อวัตถุดิบ</label>
                        <input type="text" id="ingredient_name" name="ingredient_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('ingredient_name') }}"" required autofocus>
                        @error('ingredient_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="ingredient_type_id"
                            class="block mb-2 text-sm font-medium text-gray-700">ประเภทวัตถุดิบ</label>
                        <select id="ingredient_type_id" name="ingredient_type_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">เลือกประเภทวัตถุดิบ</option>
                            @foreach ($ingredientTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->ingredient_type_name }}</option>
                            @endforeach
                        </select>
                        @error('ingredient_type_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-lg font-semibold mb-2">หน่วยวัตถุดิบ</label>
                        <div class="flex">
                            <input type="text" name="ingredient_unit" placeholder="กรอกหน่วย เช่น กรัม ( หรือเลือกจากด้านขวา )"
                                   class="block w-2/3 mt-1 px-4 py-2 border rounded-md bg-gray-100 text-gray-700 focus:outline-none focus:ring-indigo-500">
                            <select name="unit_suggestion" class="block w-1/3 mt-1 px-4 py-2 border rounded-md  bg-gray-100 text-gray-700 focus:outline-none focus:ring-indigo-500" 
                                    onchange="this.previousElementSibling.value = this.value;">
                                <option value="" disabled selected>เลือกหน่วย</option>
                                <option value="กรัม">กรัม</option>
                                <option value="กิโลกรัม">กิโลกรัม</option>
                                <option value="ลิตร">ลิตร</option>
                            </select>
                        </div>
                        @error('ingredient_unit')
                            <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="ingredient_quantity"
                            class="block mb-2 text-sm font-medium text-gray-700">จำนวนเริ่มต้น</label>
                        <input type="number" id="ingredient_quantity" name="ingredient_quantity"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('ingredient_quantity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 ease-in-out">
                            เพิ่มวัตถุดิบ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unitContainer = document.getElementById('ingredient-units');
            const radioButtons = unitContainer.querySelectorAll('input[type="radio"]');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    radioButtons.forEach(rb => {
                        rb.parentElement.classList.remove('bg-indigo-500', 'text-white');
                        rb.parentElement.classList.add('bg-gray-100');
                    });
                    if (this.checked) {
                        this.parentElement.classList.remove('bg-gray-100');
                        this.parentElement.classList.add('bg-indigo-500', 'text-white');
                    }
                });
            });
        });
    </script>
@endsection
