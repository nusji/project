@extends('layouts.guest')
@section('title', 'ยินดีต้อนรับ KAOKANG')
@section('content')
    <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-6 text-center">เมนูวันนี้</h2>
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