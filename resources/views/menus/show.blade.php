<!-- resources/views/menus/show.blade.php -->
@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบเมนูข้าวแกง', 'url' => route('menus.index')], ['label' => 'แสดงเมนูรายการที่ ' . $menu->id]]" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-semibold">{{ $menu->menu_name }}</h1>
                        <div class="flex space-x-2">
                            @if (auth()->user()->role === 'owner')
                                <a href="{{ route('menus.edit', $menu) }}"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    แก้ไข
                                </a>

                                <form id="delete-form-{{ $menu->id }}" action="{{ route('menus.destroy', $menu) }}"
                                    method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        onclick="confirmDelete({{ $menu->id }})">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        ลบ
                                    </button>
                                </form>
                                <script>
                                    function confirmDelete(menuId) {
                                        Swal.fire({
                                            title: 'คุณแน่ใจหรือไม่?',
                                            html: 'การลบรายการเมนูจะไม่สามารถกู้คืนได้!<br><br>' +
                                                '<span style="color: red; font-weight: bold;">..</span>',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'ใช่, ลบเลย!',
                                            cancelButtonText: 'ยกเลิก'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // หากผู้ใช้ยืนยันการลบ, ส่งแบบฟอร์ม
                                                document.getElementById('delete-form-' + menuId).submit();
                                            }
                                        });
                                    }
                                </script>
                            @endif
                        </div>
                    </div>



                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if ($menu->menu_image)
                                <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}"
                                    class="w-full h-auto object-cover rounded-lg mb-4">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg mb-4">
                                    <span class="text-gray-500">ไม่มีรูปภาพเมนู</span>
                                </div>
                            @endif
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <p class="text-gray-700 mb-2"><span class="font-semibold">ประเภทเมนู :</span>
                                    {{ $menu->menuType->menu_type_name }}</p>
                                <p class="text-gray-700 mb-2"><span class="font-semibold">ราคาต่อทัพพี :</span>
                                    ฿{{ number_format($menu->menu_price, 2) }}</p>
                                <p class="text-gray-700 mb-2">
                                    <span class="font-semibold">สถานะขาย :</span>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $menu->menu_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $menu->menu_status ? 'พร้อมขาย' : 'ไม่พร้อมขาย' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold ">รายละเอียดเมนู</h2>
                            <p class="text-gray-700 mb-4">{{ $menu->menu_detail ?: 'ไม่มีรายละเอียด' }}</p>
                            <h2 class="text-2xl font-semibold ">รสชาติหลัก</h2>
                            <p class="text-gray-700 mb-4 ">{{ $menu->menu_taste ?: 'ไม่มีรสชาติหลัก' }}</p>
                            <h2 class="text-2xl font-semibold ">ปริมาณหักต่อทัพพี</h2>
                            <p class="text-gray-700 mb-4 ">{{ $menu->portion_size ?: 'ไม่มีปริมาณหัก' }} กิโลกรัม</p>
                            <h2 class="text-2xl font-semibold">สูตรเมนู/วัตถุดิบที่ใช้</h2>
                            @if ($menu->recipes->count() > 0)
                                <ul class="list-disc list-inside space-y-2 mb-4">
                                    @foreach ($menu->recipes as $recipe)
                                        <li class="text-gray-700">
                                            {{ $recipe->ingredient->ingredient_name }} {{ $recipe->amount }}
                                            {{ $recipe->ingredient->ingredient_unit }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-700">ไม่มีวัตถุดิบที่ใช้ในเมนูนี้</p>
                            @endif

                            <div>
                                <h2 class="text-2xl font-semibold">รีวิวเมนู</h2>
                                
                            </div>
                        </div>
                        
                    </div>

                    <div class="mt-8 right">
                        <a href="{{ route('menus.index') }}"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                            กลับไปหน้ารายการ
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
