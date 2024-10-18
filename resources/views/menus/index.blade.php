@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบเมนูข้าวแกง', 'url' => route('menus.index')], ['label' => '']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบจัดการเมนูข้าวแกง</h2>
        <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col h-full mb-2">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">วัตถุดิบตามประเภท</h3>
            <div style="display: flex;">
                <!-- ส่วนของกราฟ -->
                <div style="flex: 1;">
                    <canvas id="menuTypeChart" width="300" height="300"></canvas>
                </div>
                <!-- ส่วนของข้อความกำกับข้างๆ กราฟ -->
                <div id="chartLabels" style="flex: 1; padding-left: 20px;">
                    <!-- ข้อความจะถูกเติมในนี้ผ่าน JavaScript -->
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            @if (auth()->user()->role === 'owner')
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('menus.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    เพิ่มเมนูใหม่
                </a>
                <a href="{{ route('menu_types.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16">
                        </path>
                    </svg>
                    จัดการประเภท
                </a>
            </div>
            @endif
            <div>
                <!-- เรียกใช้ search form component -->
                <x-search-form :search="$search" />
            </div>
        </div>
        <!-- ส่วนของตาราง-->
        <div class="p-6">
            <h1 class="text-xl font-bold text-gray-800 mb-4">รายการเมนูอาหาร</h1>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ชื่อเมนู
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ประเภทเมนู
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ราคา
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                สถานะขาย
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                รูปเมนู
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                จัดการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if ($menus->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    ไม่มีเมนู
                                </td>
                            </tr>
                        @else
                            @foreach ($menus as $menu)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $menu->menu_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $menu->menuType->menu_type_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($menu->menu_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="@if ($menu->menu_status) bg-green-200 text-green-600 @else bg-red-200 text-red-600 @endif py-1 px-3 rounded-full text-xs">
                                            {{ $menu->menu_status ? 'พร้อมขาย' : 'ไม่พร้อมขาย' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($menu->menu_image)
                                            <img src="{{ asset('storage/' . $menu->menu_image) }}"
                                                alt="{{ $menu->menu_name }}" class="w-16 h-16 object-cover rounded-md">
                                        @else
                                            <span class="text-gray-400">ไม่มีรูปภาพ</span>
                                        @endif
                                    </td>

                                    <!-- ส่วนของการจัดการ -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 text-center">
                                        <a href="{{ route('menus.show', $menu) }}"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            ดูสูตร
                                        </a>
                                        @if (auth()->user()->role === 'owner')
                                            <a href="{{ route('menus.edit', $menu) }}"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                แก้ไขเมนู
                                            </a>
                                            <form id="delete-form-{{ $menu->id }}"
                                                action="{{ route('menus.destroy', ['menu' => $menu->id]) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    onclick="confirmDelete({{ $menu->id }})">
                                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    ลบเมนู
                                                </button>
                                            </form>
                                            <script>
                                                function confirmDelete(menuId) {
                                                    Swal.fire({
                                                        title: 'คุณแน่ใจหรือไม่?',
                                                        text: 'การลบรายการจะไม่สามารถกู้คืนได้!',
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
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('menuTypeChart').getContext('2d');
            var data = @json($menuTypes);
    
            // สร้างกราฟโดนัท
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.type), // ชื่อประเภทเมนู
                    datasets: [{
                        data: data.map(item => item.count), // จำนวนเมนูแต่ละประเภท
                        backgroundColor: [
                            '#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884D8',
                            '#82ca9d', '#a4de6c', '#d0ed57', '#ffc658', '#8884d8'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'จำนวนเมนูตามประเภท'
                        }
                    }
                }
            });
    
            // สร้างข้อความกำกับข้างๆ กราฟ
            var chartLabels = document.getElementById('chartLabels');
            data.forEach(function(item, index) {
                var label = document.createElement('p');
                label.innerHTML = `<span style="color: ${['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884D8', '#82ca9d', '#a4de6c', '#d0ed57', '#ffc658', '#8884d8'][index]};">●</span> ${item.type} : ${item.count} รายการ`;
                chartLabels.appendChild(label);
            });
        });
    </script>
@endsection
