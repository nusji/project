@extends('layouts.app')

@section('title', 'เพิ่มวัตถุดิบใหม่')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[
            ['label' => 'ระบบจัดการวัตถุดิบ', 'url' => route('ingredients.index')],
            ['label' => 'วัตถุดิบ', 'url' => route('ingredients.index')],
            ['label' => 'เพิ่ม'],
        ]" />

        <h2 class="text-2xl font-bold text-gray-800 mb-2">เพิ่มวัตถุดิบ</h2>
        <p class=" text-sm text-gray-500">กรอกข้อมูลด้านล่างเพื่อเพิ่มวัตถุดิบใหม่</p>
    </div>
    
    <div class="p-6">
        <form action="{{ route('ingredients.store') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="ingredient_name" class="block mb-2 text-sm font-medium text-gray-700">ชื่อวัตถุดิบ</label>
                <input type="text" id="ingredient_name" name="ingredient_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('ingredient_name') }}" required autofocus>
                @error('ingredient_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="ingredient_type_id" class="block mb-2 text-sm font-medium text-gray-700">ประเภทวัตถุดิบ</label>
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
                    <select name="unit_suggestion"
                        class="block w-1/3 mt-1 px-4 py-2 border rounded-md  bg-gray-100 text-gray-700 focus:outline-none focus:ring-indigo-500"
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
                <label for="ingredient_quantity" class="block mb-2 text-sm font-medium text-gray-700">จำนวนเริ่มต้น</label>
                <input type="number" id="ingredient_quantity" name="ingredient_quantity"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                @error('ingredient_quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('ingredients.index') }}"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    ยกเลิก
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    บันทึก
                </button>
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
