@extends('layouts.app')

@section('title', 'แก้ไขวัตถุดิบ')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">แก้ไขวัตถุดิบ</h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
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
                    </div>

                    <div class="mb-6">
                        <label for="ingredient_unit"
                            class="block mb-2 text-sm font-medium text-gray-700">หน่วยวัตถุดิบ</label>
                        <input type="text" id="ingredient_unit" name="ingredient_unit"
                            value="{{ $ingredient->ingredient_unit }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div class="mb-6">
                        <label for="ingredient_quantity"
                            class="block mb-2 text-sm font-medium text-gray-700">จำนวนคงเหลือ</label>
                        <input type="number" id="ingredient_quantity" name="ingredient_quantity"
                            value="{{ $ingredient->ingredient_quantity }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 ease-in-out">
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
