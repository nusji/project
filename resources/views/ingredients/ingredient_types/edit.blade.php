@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">แก้ไขประเภทวัตถุดิบ</h2>

    <form action="{{ route('ingredient_types.update', $ingredientType->id) }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="ingredient_type_name">
                ชื่อประเภท
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ingredient_type_name" type="text" name="ingredient_type_name" value="{{ $ingredientType->ingredient_type_name }}" required>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="ingredient_type_detail">
                รายละเอียด
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ingredient_type_detail" name="ingredient_type_detail" rows="3">{{ $ingredientType->ingredient_type_detail }}</textarea>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                อัพเดท
            </button>
            <a href="{{ route('ingredient_types.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                ยกเลิก
            </a>
        </div>
    </form>
</div>
@endsection