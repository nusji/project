@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-2">เพิ่มประเภทวัตถุดิบใหม่</h2>

        <form action="{{ route('ingredient_types.store') }}" method="POST" class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ingredient_type_name">
                    ชื่อประเภท
                </label>
                <input class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" id="ingredient_type_name" type="text" name="ingredient_type_name" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="ingredient_type_detail">
                    รายละเอียด
                </label>
                <textarea class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" id="ingredient_type_detail" name="ingredient_type_detail" rows="4"></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105" type="submit">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    บันทึก
                </button>
                <a href="{{ route('ingredient_types.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 transition duration-300">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>
@endsection