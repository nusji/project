@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบจัดการผลิต', 'url' => route('productions.index')], ['label' => '']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบจัดการผลิต</h2>
        @if (auth()->user()->role === 'owner')
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('productions.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                            </path>
                        </svg>
                        สั่งผลิตใหม่
                    </a>
                </div>
        @endif
        <a href="{{ route('allocations.index') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            View Menu Allocations
        </a>
        
        <form action="#" method="GET" class="flex-grow md:max-w-md">
            <div class="relative">
                <input type="text" name="search" placeholder="ค้นหา..." value="{{ request('search') }}"
                    class="w-full px-4 py-2 rounded-md border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-gray-100 border-l border-gray-300 rounded-r-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>

    </div>

    <!-- ส่วนของตาราง-->
    <div class="p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">ประวัตการผลิต</h1>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            รหัสการผลิต
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            วันที่ผลิต
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            รายละเอียด
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            เมนูที่ผลิต
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จำนวนกิโลกรัมที่ผลิต
                        </th>
                        <th
                            class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            การจัดการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($productions->isEmpty())
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="4">
                                ไม่พบประวัติการผลิต
                            </td>
                        </tr>
                    @else
                        @foreach ($productions as $production)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $production->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $production->production_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $production->production_detail }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <ul>
                                        @foreach ($production->productionDetails as $detail)
                                            <li>{{ $detail->menu->menu_name }} ({{ $detail->quantity }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $production->productionDetails->sum('quantity') }}
                                </td>
                                <!-- ส่วนของการจัดการ -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 text-center">

                                    <a href="{{ route('productions.show', $production) }}"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        ดูรายละเอียด
                                    </a>

                                    <form action="{{ route('productions.destroy', $production) }}" method="POST"
                                        class="inline" id="delete-form-{{ $production->id }}">
                                        @csrf
                                        @method('DELETE') <!-- เพิ่ม METHOD DELETE -->
                                        <button type="button" onclick="confirmDelete({{ $production->id }})"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            ยกเลิกการผลิต
                                        </button>
                                    </form>

                                    <script>
                                        function confirmDelete(productionId) {
                                            Swal.fire({
                                                title: 'คุณแน่ใจหรือไม่?',
                                                text: "กรุณาพิมพ์คำว่า 'ยกเลิกการผลิต' เพื่อยืนยันการลบข้อมูล!",
                                                icon: 'warning',
                                                input: 'text',
                                                inputPlaceholder: 'พิมพ์ที่นี่...',
                                                showCancelButton: true,
                                                confirmButtonText: 'ใช่, ลบเลย!',
                                                cancelButtonText: 'ยกเลิก',
                                                customClass: {
                                                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded',
                                                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded'
                                                },
                                                preConfirm: (inputValue) => {
                                                    if (inputValue !== 'ยกเลิกการผลิต') {
                                                        Swal.showValidationMessage('คำที่พิมพ์ไม่ถูกต้อง! กรุณาพิมพ์ "ยกเลิกการผลิต".');
                                                    }
                                                    return inputValue;
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById(`delete-form-${productionId}`).submit();
                                                }
                                            });
                                        }
                                    </script>

                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
