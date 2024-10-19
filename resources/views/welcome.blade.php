@extends('layouts.guest')

@section('title', 'ยินดีต้อนรับ KAOKANG')

@section('content')
    <div class="container mx-auto px-4">
        <header class="mb-12">
            <img src="images/banner-a.png" alt="" class="max-w-3xl w-full h-auto mx-auto mb-0">
        </header>

        <div class="mb-15">
            <h2 class="text-4xl font-bold mb-6 text-center text-orange-800">เลือกปุ่มทำรายการ</h2>
            <div class="text-center">
                <a href="{{route('survey-suggest')}}"
                    class="bg-orange-500 text-slate-50 border-2 shadow-md font-bold py-3 px-8 rounded-full text-xl hover:bg-orange-700 transition duration-300 mb-4 inline-block">
                    แนะนำเมนูตามความชอบ!
                </a>
                <br>
                <a href="{{ route('menu-today') }}"
                    class="bg-orange-500 text-slate-50 border-2 shadow-md font-bold py-3 px-8 rounded-full text-xl hover:bg-orange-700 transition duration-300 inline-block">
                    ดูเมนูวันนี้!
                </a>
            </div>
            <div class="container border-4 border-slate-500 rounded-lg mt-10">
                <h2 class="text-4xl font-bold mb-6 text-center mt-10 text-orange-800">เมนูวันนี้</h2>
                @if ($menus->isEmpty())
                    <p class="text-xl text-center text-orange-800 mb-10">ขออภัย ไม่มีเมนูสำหรับวันนี้</p>
                @else
                    <div class="flex flex-wrap justify-center gap-8 mb-10">
                        @foreach ($menus as $menu)
                            <div class="relative bg-white rounded-lg p-6 shadow-lg text-center w-64">
                                <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}"
                                    class="w-full h-40 object-cover rounded-lg mb-4">
                                <!-- ตรวจสอบ is_sold_out ที่ถูกกำหนดจาก Controller -->
                                @if ($menu->is_sold_out == 1)
                                    <!-- ลายน้ำ "หมด" -->
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 rounded-lg">
                                        <span class="text-4xl font-bold text-white">หมด</span>
                                    </div>
                                @endif
    
                                <h2 class="text-xl font-semibold text-gray-800">{{ $menu->menu_name }}</h2>
                                <p class="text-lg text-black">{{ $menu->menu_price }} บาท</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
        </div>


    </div>
@endsection
