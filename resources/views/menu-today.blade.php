@extends('layouts.guest')

@section('title', 'ยินดีต้อนรับ KAOKANG')

@section('content')
    <div class="container mx-auto px-4 items-center">
        <h2 class="text-4xl font-bold mb-6 text-center text-orange-800">เมนูวันนี้</h2>

        @if ($menus->isEmpty())
            <p class="text-xl text-center text-black">ขออภัย ไม่มีเมนูสำหรับวันนี้</p>
        @else
            <!-- ปุ่มเลือกประเภทเมนู -->
            <div class="flex justify-center space-x-4 mb-6">
                <button class="bg-orange-500 border-2 border-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-4 rounded-full"
                    onclick="filterMenu('')">ทั้งหมด</button>
                @foreach ($menus->groupBy('menuType.menu_type_name') as $type => $menusByType)
                    <button class="bg-orange-500 border-2 border-orange-600 hover:bg-orange-800 text-white font-bold py-2 px-4 rounded-full"
                        onclick="filterMenu('{{ $type }}')">{{ $type }}</button>
                @endforeach
            </div>

            <!-- รายการเมนู -->
            <div id="menu-list" class="flex flex-wrap justify-center gap-6">
                @foreach ($menus->groupBy('menuType.menu_type_name') as $type => $menusByType)
                    <div class="menu-group" data-type="{{ $type }}">
                        <h3 class="text-2xl font-bold mb-4 text-orange-800">{{ $type }}</h3>
                        @foreach ($menusByType as $menu)
                            <div class="relative bg-white rounded-lg p-4 shadow-md text-center w-48 mb-6">
                                <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}"
                                    class="w-full h-32 object-cover rounded-lg mb-2">
                                
                                <!-- ตรวจสอบ is_sold_out ที่ถูกกำหนดจาก Controller -->
                                @if ($menu->is_sold_out == 1)
                                    <!-- ลายน้ำ "หมด" -->
                                    <div
                                        class="absolute inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 rounded-lg">
                                        <span class="text-3xl font-bold text-white">หมด</span>
                                    </div>
                                @endif
                                
                                <h2 class="text-lg font-semibold text-gray-800">{{ $menu->menu_name }}</h2>
                                <p class="text-md text-black">{{ $menu->menu_price }} บาท</p>
                                <p class="text-sm italic text-gray-500">คงเหลือ {{ $menu->remaining_amount }} กิโลกรัม</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
        @endif
    </div>

    <script>
        // ฟังก์ชันสำหรับกรองเมนูตามประเภท
        function filterMenu(type) {
            // ซ่อนกลุ่มเมนูทั้งหมด
            document.querySelectorAll('.menu-group').forEach(group => {
                group.style.display = 'none';
            });

            // แสดงกลุ่มเมนูที่ตรงกับประเภทที่เลือก หรือแสดงทั้งหมดถ้าเลือก "ทั้งหมด"
            if (type === '') {
                document.querySelectorAll('.menu-group').forEach(group => {
                    group.style.display = 'inline-block';
                });
            } else {
                document.querySelectorAll(`.menu-group[data-type="${type}"]`).forEach(group => {
                    group.style.display = 'inline-block';
                });
            }
        }

        // เรียกใช้งานฟังก์ชันเพื่อแสดงเมนูทั้งหมดเริ่มต้น
        filterMenu('');
    </script>
@endsection
