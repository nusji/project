@extends('layouts.app')

@section('title', 'แก้ไขวัตถุดิบ')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[
            ['label' => 'ระบบจัดการวัตถุดิบ', 'url' => route('ingredients.index')],
            ['label' => 'วัตถุดิบ', 'url' => route('ingredients.index')],
            ['label' => 'แก้ไข'],
        ]" />

        <h2 class="text-2xl font-bold text-gray-800 mb-2">แก้ไขวัตถุดิบ</h2>
        <p class=" text-sm text-gray-500">กรอกข้อมูลด้านล่างเพื่อแก้ไขวัตถุดิบ</p>
    </div>

    <div class="p-6">
        <div class="p-6">
            <form action="{{ route('ingredients.update', $ingredient) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-6">
                    <label for="ingredient_name" class="block mb-2 text-sm font-medium text-gray-700">ชื่อวัตถุดิบ</label>
                    <input type="text" id="ingredient_name" name="ingredient_name"
                        value="{{ $ingredient->ingredient_name }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
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
                        @foreach ($ingredientTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ $ingredient->ingredient_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->ingredient_type_name }}</option>
                        @endforeach
                    </select>
                    @error('ingredient_type_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="ingredient_unit" class="block mb-2 text-sm font-medium text-gray-700">หน่วยวัตถุดิบ</label>
                    <input type="text" id="ingredient_unit" name="ingredient_unit"
                        value="{{ $ingredient->ingredient_unit }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    @error('ingredient_unit')
                        <p class="text-red-500 text-sm italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="ingredient_stock" class="block mb-2 text-sm font-medium text-gray-700">
                            จำนวนคงเหลือ
                        </label>
                        <input type="number" id="ingredient_stock" name="ingredient_stock"
                            value="{{ $ingredient->ingredient_stock }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('ingredient_stock')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="minimum_quantity" class="block mb-2 text-sm font-medium text-gray-700">
                            แจ้งเตือนเมื่อเหลือ
                        </label>
                        <input type="number" id="minimum_quantity" name="minimum_quantity"
                            value="{{ $ingredient->minimum_quantity }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        @error('minimum_quantity')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
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
@endsection
