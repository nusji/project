@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[
            ['label' => 'ระบบจัดการวัตถุดิบ', 'url' => route('ingredients.index')],
            ['label' => 'ประเภทวัตถุดิบ'],
        ]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">จัดการประเภทวัตถุดิบ</h2>
        <!-- ใส่รายละเอียดหรือฟังก์ชันเพิ่มเติมตรงนี้ -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('ingredient_types.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    เพิ่มประเภทวัตถุดิบใหม่
                </a>
                <a href="{{ route('ingredients.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>

    <!-- ส่วนของ Card-->
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($ingredientTypes as $ingredientType)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col">
                <div class="p-4 flex-1">
                    <h3 class="text-lg font-bold text-gray-800">{{ $ingredientType->ingredient_type_name }}</h3>
                    <p class="text-sm text-gray-600 mt-2">{{ $ingredientType->ingredient_type_detail }}</p>
                </div>
                <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                    <a href="{{ route('ingredient_types.edit', $ingredientType->id) }}"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>แก้ไข
                    </a>
                    <button onclick="confirmDelete({{ $ingredientType->id }})"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>ลบ
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endsection
