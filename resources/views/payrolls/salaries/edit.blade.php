@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- เรียกใช้ breadcrumb component -->
    <x-breadcrumb :paths="[['label' => 'ระบบเงินเดือน', 'url' => route('payrolls.index')],
    ['label' => 'ฐานเงินเดือน', 'url' => route('salaries.index')],
     ['label' => 'ปรับฐานเงินเดือน']]" />
    <h2 class="text-2xl font-bold text-gray-800 mb-4">ปรับฐานเงินเดือน</h2>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">ข้อมูลพนักงาน</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ชื่อพนักงาน</p>
                        <p class="mt-1">{{ $employee->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">รหัสพนักงาน</p>
                        <p class="mt-1">{{ $employee->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">ตำแหน่งงาน/ประเภทจ้าง</p>
                        <p class="mt-1">{{ $employee->employment_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">วันที่เริ่มเข้าทำงาน</p>
                        <p class="mt-1">{{ $employee->start_date }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">ฐานเงินเดือนปัจจุบัน</p>
                        <p class="mt-1">{{ number_format($employee->salary, 2) }} บาท</p>
                    </div>

                </div>
            </div>
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Please correct the following errors:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('salaries.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="salary" class="block text-gray-700 text-sm font-bold mb-2">ฐานเงินเดือนใหม่</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="salary" id="salary" class="form-input font-bold text-xl block w-full px-3 py-5 pl-7 pr-12 sm:text-sm sm:leading-5 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" value="{{ old('salary', $employee->salary) }}">
                        <div class="absolute inset-y-0 right-0 flex items-center">
                            <span class="text-gray-500 sm:text-l mr-2">บาท</span>
                        </div>
                    </div>
                </div>


                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('salaries.index') }}"
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
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
