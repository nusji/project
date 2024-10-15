@extends('layouts.guest')

@section('title', 'ยินดีต้อนรับ KAOKANG')

@section('content')
    <div class="container mx-auto px-4">
        <header class="mb-12">
            <img src="images/banner-a.png" alt="" class="max-w-3xl w-full h-auto mx-auto mb-0">
        </header>

        <div class="mb-15">
            <h2 class="text-4xl font-bold mb-6 text-center text-white-500">เลือกปุ่มทำรายการ</h2>
            <div class="text-center">
                <a href="#"
                    class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300 mb-4 inline-block">
                    สั่งอาหารเลย!
                </a>
                <br>
                <a href="{{ route('menu-today') }}"
                    class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300 inline-block">
                    ดูเมนูวันนี้!
                </a>
            </div>
            <h2 class="text-4xl font-bold mb-6 text-center mt-10">เมนูวันนี้</h2>
            @if ($menus->isEmpty())
                <p class="text-xl text-center">ขออภัย ไม่มีเมนูสำหรับวันนี้</p>
            @else
                <div class="flex flex-wrap justify-center gap-8">
                    @foreach ($menus as $menu)
                        <div class="bg-white rounded-lg p-6 shadow-lg text-center w-64">
                            <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->name }}"
                                class="w-full h-40 object-cover rounded-lg mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">{{ $menu->menu_name }}</h2>
                            <p class="text-lg text-black">{{ $menu->menu_price }} บาท</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>


    </div>
@endsection