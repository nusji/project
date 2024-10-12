@extends('layouts.guest')

@section('title', 'ยินดีต้อนรับสู่ร้านข้าวแกง KAOKANG')

@section('content')
    <header class="mb-12">
        <h1 class="text-6xl font-bold mb-4">ยินดีต้อนรับสู่ร้านข้าวแกง KAOKANG</h1>
        <p class="text-2xl">อาหารอร่อย รวดเร็ว ในราคาที่ทุกคนเข้าถึงได้</p>
    </header>

    <div class="flex flex-wrap justify-center gap-8 mb-12">
        <h2 class="text-4xl font-bold mb-6">เมนูวันนี้</h2>
        @if ($menus->isEmpty())
            <p class="text-xl">ขออภัย ไม่มีเมนูสำหรับวันนี้</p>
        @else
            @foreach ($menus as $menu)
                <div class="bg-white rounded-lg p-6 shadow-lg text-center">
                    <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->name }}"
                        class="w-24 h-24 mx-auto mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $menu->menu_name }}</h2>
                    <p class="text-lg">{{ $menu->description }}</p>
                </div>
            @endforeach
        @endif
    </div>

    <div class="text-center">
        <a href="#"
            class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300 mb-4 inline-block">
            สั่งอาหารเลย!
        </a>
        <br>
        <a href="{{ url('login') }}"
            class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300 inline-block">
            เฉพาะพนักงาน!
        </a>
    </div>
@endsection
