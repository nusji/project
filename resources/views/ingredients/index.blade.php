@extends('layouts.app')

@section('title', 'จัดการวัตถุดิบ')

@section('content')
    <div class="container mx-auto px-4 py-0">
            <!-- เรียกใช้ breadcrumb component -->
    <x-breadcrumb :paths="[
        ['label' => 'ระบบจัดการวัตถุดิบ', 'url' => route('ingredients.index')],
        ['label' => '']
    ]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบจัดการวัตถุดิบ</h2>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            @if (auth()->user()->role === 'owner')
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('ingredients.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 ease-in-out shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    เพิ่มวัตถุดิบ
                </a>
                <a href="{{ route('ingredient_types.index') }}"
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
            <form action="#" method="GET" class="flex-grow md:max-w-md">
                <div class="relative">
                    <input type="text" name="search" placeholder="ค้นหาวัตถุดิบ..." value="{{ request('search') }}"
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
    </div>
    <!-- ส่วนของตาราง-->
    <div class="p-6">
        <h1 class="text-xl font-bold text-gray-800 mb-4">รายการวัตถุดิบ</h1>
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ชื่อวัตถุดิบ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ประเภทวัตถุดิบ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            หน่วยวัตถุดิบ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จำนวนคงเหลือ
                        </th>
                        <th class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            การจัดการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if ($ingredients->isEmpty())
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="5">
                                ไม่พบข้อมูลวัตถุดิบ
                            </td>
                        </tr>
                    @else
                        @foreach ($ingredients as $ingredient)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $ingredient->ingredient_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $ingredient->ingredientType->ingredient_type_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $ingredient->ingredient_unit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span id="quantity-{{ $ingredient->id }}">{{ $ingredient->ingredient_quantity }}</span>
                                </td>

                                <!-- ส่วนของการจัดการ -->
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2 text-center">
                                    <button onclick="updateQuantity({{ $ingredient->id }})"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                        </svg>
                                        ปรับปรุงสต็อค
                                    </button>
                                    @if (auth()->user()->role === 'owner')
                                    <a href="{{ route('ingredients.edit', ['ingredient' => $ingredient->id]) }}"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        แก้ไข
                                    </a>

                                    <form id="delete-form-{{ $ingredient->id }}"
                                        action="{{ route('ingredients.destroy', ['ingredient' => $ingredient->id]) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            onclick="confirmDelete({{ $ingredient->id }})">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            ลบ
                                        </button>
                                    </form>
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            <!-- Pagination links -->
            {{ $ingredients->links() }}
        </div>
    </div>
    <script>
        // Confirm delete ingredient
        function confirmDelete(ingredientId) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณจะไม่สามารถกู้คืนการลบนี้ได้!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + ingredientId).submit();
                }
            });
        }

        // Update ingredient quantity
        function updateQuantity(id) {
            Swal.fire({
                title: 'ปรับปรุงจำนวนวัตถุดิบ',
                input: 'number',
                inputLabel: 'ใส่จำนวนที่ต้องการเพิ่มหรือลด',
                inputPlaceholder: 'เช่น 10 หรือ -5',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                inputValidator: (value) => {
                    if (!value) {
                        return 'กรุณาใส่จำนวน'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/ingredients/update-quantity', {
                            ingredient_id: id,
                            quantity: result.value
                        })
                        .then(function(response) {
                            if (response.data.success) {
                                Swal.fire('สำเร็จ', response.data.message, 'success');
                                document.getElementById(`quantity-${id}`).textContent = response.data
                                    .new_quantity;
                            }
                        })
                        .catch(function(error) {
                            Swal.fire('ผิดพลาด', 'เกิดข้อผิดพลาดในการอัปเดตจำนวน', 'error');
                        });
                }
            })
        }
    </script>
@endsection
