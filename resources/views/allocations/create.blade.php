@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- เรียกใช้ breadcrumb component -->
    <x-breadcrumb :paths="[['label' => 'ระบบช่วยเหลือจัดสรรเมนู', 'url' => route('allocations.index')], ['label' => 'สร้างรายการจัดสรร']]" />
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            เพิ่มการจัดสรรเมนู
        </h2>

        @if ($errors->any())
            <div class="mb-4">
                <ul class="list-disc list-inside text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('allocations.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- วันที่เริ่มต้น -->
            <div class="space-y-2">
                <label for="allocation_date" class="block text-sm font-medium text-gray-700">
                    วันที่เริ่มต้น
                </label>
                <input type="date" 
                       name="allocation_date" 
                       id="allocation_date"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>

            <!-- จำนวนวัน -->
            <div class="space-y-2">
                <label for="days" class="block text-sm font-medium text-gray-700">
                    จำนวนวัน (สูงสุด 7 วัน)
                </label>
                <div class="relative">
                    <input type="number" 
                           name="days" 
                           id="days"
                           min="1" 
                           max="7" 
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">วัน</span>
                    </div>
                </div>
            </div>

            <!-- เมนูขายดี -->
            <div class="space-y-2">
                <label for="best_selling_menus" class="block text-sm font-medium text-gray-700">
                    เลือกเมนูขายดีที่ต้องการคงไว้
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($bestSellingMenus as $menu)
                        <div class="flex items-center">
                            <input id="menu_{{ $menu->id }}" name="best_selling_menus[]" type="checkbox" value="{{ $menu->id }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="menu_{{ $menu->id }}" class="ml-2 block text-sm text-gray-700">
                                {{ $menu->menu_name }} (ขายดี: {{ $menu->total_sold }} ชิ้น)
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- จำนวนเมนูสุ่มที่เหลือ -->
            <div class="space-y-2">
                <label for="random_menus_count" class="block text-sm font-medium text-gray-700">
                    จำนวนเมนูสุ่มที่ต้องการเพิ่ม
                </label>
                <div class="relative">
                    <input type="number" 
                           name="random_menus_count" 
                           id="random_menus_count"
                           min="0" 
                           max="10" 
                           value="0"
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">เมนู</span>
                    </div>
                </div>
            </div>

            <!-- จำนวนเมนูทั้งหมด -->
            <div class="space-y-2">
                <label for="total_menus" class="block text-sm font-medium text-gray-700">
                    จำนวนเมนูทั้งหมดที่ต้องการจัดสรรต่อวัน
                </label>
                <div class="relative">
                    <input type="number" 
                           name="total_menus" 
                           id="total_menus"
                           min="1" 
                           required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">เมนู</span>
                    </div>
                </div>
            </div>

            <!-- ปุ่มบันทึก -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    บันทึกการจัดสรร
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
