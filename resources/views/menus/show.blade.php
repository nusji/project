<!-- resources/views/menus/show.blade.php -->
@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[
            ['label' => 'ระบบเมนูข้าวแกง', 'url' => route('menus.index')],
            ['label' => 'แสดงเมนูรายการที่ ' . $menu->id],
        ]" />
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

                                @if ($averageRating)
                                    <div class="mb-4">
                                        <p class="text-gray-700">คะแนนรีวิวเฉลี่ย: {{ number_format($averageRating, 1) }} /
                                            5</p>
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $averageRating)
                                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-700 mb-4">ยังไม่มีรีวิวสำหรับเมนูนี้</p>
                                @endif

                                @if (count($comments) > 0)
                                    <div class="mb-4">
                                        <h3 class="text-lg font-semibold mb-2">ความคิดเห็นล่าสุด</h3>
                                        @foreach ($comments->take(2) as $comment)
                                            <div class="bg-gray-100 p-3 rounded mb-2">

                                                <p>{{ $comment->comment }}</p>
                                            </div>
                                        @endforeach
                                        @if (count($comments) > 2)
                                            <button onclick="openCommentsModal()" class="text-blue-600 hover:text-blue-800">
                                                ดูความคิดเห็นทั้งหมด ({{ count($comments) }})
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Modal -->
                                    <div id="commentsModal" class="fixed z-10 inset-0 overflow-y-auto hidden"
                                        aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div
                                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                                aria-hidden="true"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                aria-hidden="true">&#8203;</span>
                                            <div
                                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900"
                                                        id="modal-title">
                                                        ความคิดเห็นทั้งหมด
                                                    </h3>
                                                    <div class="mt-2 max-h-96 overflow-y-auto">
                                                        @foreach ($comments as $comment)
                                                            <div class="bg-gray-100 p-3 rounded mb-2">
                                                                <p>{{ $comment->comment }}</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="button" onclick="closeCommentsModal()"
                                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                        ปิด
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        function openCommentsModal() {
                                            document.getElementById('commentsModal').classList.remove('hidden');
                                        }

                                        function closeCommentsModal() {
                                            document.getElementById('commentsModal').classList.add('hidden');
                                        }
                                    </script>
                                @endif

                                <h2 class="text-2xl font-semibold">จำนวนการขาย</h2>
                                <p class="text-gray-700 mb-4">จำนวนขาย: {{ $totalSales }} ทัพพี</p>


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
