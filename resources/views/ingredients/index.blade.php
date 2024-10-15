@extends('layouts.app')

@section('title', 'จัดการวัตถุดิบ')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[['label' => 'ระบบวัตถุดิบ', 'url' => route('ingredients.index')], ['label' => '']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบจัดการวัตถุดิบ</h2>
        <div class="mt-2 grid md:grid-cols-1 gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col h-full">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">วัตถุดิบตามประเภท</h3>

                    <div class="flex-grow">
                        <canvas id="ingredientTypeChart"></canvas>
                    </div>

                </div>

                <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col h-full">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">วัตถุดิบเหลือน้อย</h3>
                    <div class="space-y-3 flex-grow overflow-y-auto">
                        @forelse ($lowStockIngredients as $ingredient)
                            @if ($loop->index < 4)
                                <div
                                    class="flex items-center justify-between bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">{{ $ingredient->ingredient_name }}</p>
                                        <p class="text-xs text-gray-500">ปริมาณต่ำสุด: {{ $ingredient->minimum_quantity }}
                                            {{ $ingredient->ingredient_unit }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-red-600">เหลือ: {{ $ingredient->ingredient_stock }}
                                            {{ $ingredient->ingredient_unit }}</p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-sm text-gray-500 italic">ไม่มีวัตถุดิบที่เหลือน้อยในขณะนี้</p>
                        @endforelse
                    </div>

                    @if ($lowStockIngredients->count() > 4)
                        <div class="mt-4">
                            <button id="viewMoreBtn"
                                class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">ดูเพิ่มเติม</button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal -->
            <div id="ingredientModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">วัตถุดิบเหลือน้อยทั้งหมด</h3>
                        <div class="mt-2 px-7 py-3 max-h-96 overflow-y-auto">
                            @foreach ($lowStockIngredients as $ingredient)
                                <div
                                    class="flex items-center justify-between bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">{{ $ingredient->ingredient_name }}</p>
                                        <p class="text-xs text-gray-500">ปริมาณต่ำสุด: {{ $ingredient->minimum_quantity }}
                                            {{ $ingredient->ingredient_unit }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-red-600">เหลือ: {{ $ingredient->ingredient_stock }}
                                            {{ $ingredient->ingredient_unit }}</p>
                                        <p class="text-xs text-red-500">ต่ำกว่าขั้นต่ำ
                                            {{ $ingredient->minimum_quantity - $ingredient->ingredient_stock }}
                                            {{ $ingredient->ingredient_unit }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="closeModal"
                                class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                ปิด
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const viewMoreBtn = document.getElementById('viewMoreBtn');
                    const modal = document.getElementById('ingredientModal');
                    const closeModal = document.getElementById('closeModal');

                    if (viewMoreBtn) {
                        viewMoreBtn.addEventListener('click', function() {
                            modal.classList.remove('hidden');
                        });
                    }

                    closeModal.addEventListener('click', function() {
                        modal.classList.add('hidden');
                    });

                    window.addEventListener('click', function(event) {
                        if (event.target == modal) {
                            modal.classList.add('hidden');
                        }
                    });
                });
            </script>
        </div>
    </div>

    </div>

    <div class="mt-8 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4 ml-4">
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
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-700 bg-white-800 border-l border-gray-300 rounded-r-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </form>
        <div class="mb-4 flex justify-end">
            <form action="{{ route('ingredients.index') }}" method="GET" class="flex items-center">
                <div class="bg-white p-2.5 border rounded-xl">
                    <label for="orderBy" class="mr-2 text-sm text-gray-700">เรียงตาม:</label>
                    <select name="orderBy" id="orderBy"
                        class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 mr-2">
                        <option value="ingredient_name" {{ request('orderBy') === 'ingredient_name' ? 'selected' : '' }}>
                            ชื่อวัตถุดิบ</option>
                        <option value="ingredient_stock" {{ request('orderBy') === 'ingredient_stock' ? 'selected' : '' }}>
                            จำนวนคงเหลือ</option>
                        <option value="ingredient_type_id"
                            {{ request('orderBy') === 'ingredient_type_id' ? 'selected' : '' }}>
                            ประเภทวัตถุดิบ</option>
                    </select>
                    <select name="direction" id="direction"
                        class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>น้อยไปมาก</option>
                        <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>มากไปน้อย</option>
                    </select>
                </div>


                <button type="submit"
                    class="ml-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                    เรียงลำดับ
                </button>
            </form>
        </div>

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
                            จำนวนคงเหลือ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            หน่วยวัตถุดิบ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            แจ้งเตือนเมื่อเหลือ
                        </th>
                        @if (auth()->user()->role === 'owner')
                            <th
                                class="text-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                การจัดการ
                            </th>
                        @endif
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
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span id="quantity-{{ $ingredient->id }}">{{ $ingredient->ingredient_stock }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">
                                        {{ $ingredient->ingredient_unit }}
                                    </span>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-orange-500 font-bold">
                                    <span id="quantity-{{ $ingredient->id }}">{{ $ingredient->minimum_quantity }}</span>
                                </td>


                                <!-- ส่วนของการจัดการ -->
                                @if (auth()->user()->role === 'owner')
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
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                ลบ
                                            </button>
                                        </form>

                                    </td>
                                @endif
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
                html: 'การลบรายการวัตถุดิบจะไม่สามารถกู้คืนได้!<br><br>' +
                    '<span style="color: red; font-weight: bold;"></span>',
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

        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('ingredientTypeChart').getContext('2d');
            var data = @json($ingredientTypes);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.type),
                    datasets: [{
                        data: data.map(item => item.count),
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
                            text: 'วัตถุดิบตามประเภท'
                        }
                    }
                }
            });
        });
    </script>
@endsection
