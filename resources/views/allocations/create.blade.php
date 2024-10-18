@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-4">Create Menu Allocation</h1>

        <!-- ฟอร์มสำหรับการจัดสรรเมนู -->
        <form action="{{ route('allocations.store') }}" method="POST">
            @csrf

            <!-- จำนวนวันที่ต้องการสุ่มเมนู -->
            <div class="mb-4">
                <label for="days" class="block text-sm font-medium text-gray-700">Number of Days</label>
                <input type="number" name="days" id="days" class="mt-1 block w-full" placeholder="Enter number of days" value="3" min="1" class="rounded-md border-gray-300">
            </div>

            <!-- จำนวนเมนูที่ต้องการจัดสรรในแต่ละวัน -->
            <div class="mb-4">
                <label for="menu_count" class="block text-sm font-medium text-gray-700">Total Menus Per Day</label>
                <input type="number" name="menu_count" id="menu_count" class="mt-1 block w-full" placeholder="Enter number of menus per day" value="5" min="1" class="rounded-md border-gray-300">
            </div>

            <!-- จำนวนเมนูขายดีที่ต้องการตรึง -->
            <div class="mb-4">
                <label for="fixed_top_sellers" class="block text-sm font-medium text-gray-700">Number of Top Sellers to Fix</label>
                <input type="number" name="fixed_top_sellers" id="fixed_top_sellers" class="mt-1 block w-full" placeholder="Enter number of top sellers to fix" value="1" min="0" class="rounded-md border-gray-300">
            </div>

            <!-- ปุ่มส่งฟอร์ม -->
            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Allocate Menus</button>
            </div>

        </form>
    </div>
@endsection
