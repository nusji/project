@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">รายละเอียดประเภทวัตถุดิบ</h2>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                ชื่อประเภท
            </label>
            <p class="text-gray-700 text-base">{{ $ingredientType->ingredient_type_name }}</p>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                รายละเอียด
            </label>
            <p class="text-gray-700 text-base">{{ $ingredientType->ingredient_type_detail }}</p>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('ingredient_types.edit', $ingredientType->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                แก้ไข
            </a>
            <a href="{{ route('ingredient_types.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                กลับไปหน้ารายการ
            </a>
        </div>
    </div>
</div>
@endsection