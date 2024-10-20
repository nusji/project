@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[
            ['label' => 'ระบบจัดการวัตถุดิบ', 'url' => route('ingredients.index')],
            ['label' => 'ประเภทวัตถุดิบ', 'url' => route('ingredient_types.index')],
            ['label' => 'เพิ่ม'],
        ]" />
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">เพิ่มประเภทวัตถุดิบ</h2>
            <p class=" text-sm text-gray-500">กรอกข้อมูลด้านล่างเพื่อเพิ่มประเภทวัตถุดิบใหม่</p>
            <!-- ส่วนของตาราง-->
            <div class="p-6">
                <form action="{{ route('ingredient_types.store') }}" method="POST" class="px-4 py-5 sm:p-6">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="ingredient_type_name" class="block text-sm font-medium text-gray-700 mb-1">
                                ชื่อประเภท
                            </label>
                            <div class="mt-1">
                                <input type="text" name="ingredient_type_name" id="ingredient_type_name" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-2 border-gray-300 rounded-md transition duration-150 ease-in-out bg-gray-50 px-4 py-2"
                                    placeholder="ใส่ชื่อประเภทวัตถุดิบ">
                                @error('ingredient_type_name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="ingredient_type_detail" class="block text-sm font-medium text-gray-700 mb-1">
                                รายละเอียด
                            </label>
                            <div class="mt-1">
                                <textarea id="ingredient_type_detail" name="ingredient_type_detail" rows="4"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-2 border-gray-300 rounded-md transition duration-150 ease-in-out bg-gray-50 px-4 py-2"
                                    placeholder="ใส่รายละเอียดเพิ่มเติม (ถ้ามี)"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route('ingredient_types.index') }}"
                            class="inline-flex items-center px-4 py-2 border-2 border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
