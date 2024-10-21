@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :paths="[
                ['label' => 'ระบบการผลิต', 'url' => route('productions.index')],
                ['label' => 'สร้างรายการผลิตใหม่'],
            ]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">สร้างรายการผลิตใหม่</h1>
                    <form action="{{ route('productions.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="production_date"
                                class="block text-sm font-medium text-gray-700 mb-2">วันที่ผลิต</label>
                            <input type="date" name="production_date" id="production_date"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('production_date', date('Y-m-d')) }}" required>
                            @error('production_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="production_detail"
                                class="block text-sm font-medium text-gray-700 mb-2">รายละเอียดการผลิต</label>
                            <textarea name="production_detail" id="production_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('production_detail') }}</textarea>
                            @error('production_detail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ปุ่มประเภทเมนู --}}
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-2">ประเภทเมนู</h2>
                            <div class="flex gap-2 mb-4">
                                <button type="button"
                                    class="category-button bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded"
                                    data-category-id="all">แสดงทั้งหมด</button>
                                @foreach ($menuTypes as $menuType)
                                    <button type="button"
                                        class="category-button bg-gray-200 hover:bg-gray-300 text-gray-800 py-1 px-2 rounded"
                                        data-category-id="{{ $menuType->id }}">
                                        {{ $menuType->menu_type_name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- ส่วนแสดงปุ่มเลือกเมนู --}}
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-2">เมนูขายดี</h2>
                            <div id="best-selling-menus" class="flex flex-wrap gap-2 mb-4">
                                @foreach ($bestSellingMenus as $menu)
                                    @if (!old('menus') || !array_key_exists($menu->id, old('menus')))
                                        <button type="button"
                                            class="menu-button bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded"
                                            data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->menu_name }}"
                                            data-menu-type-id="{{ $menu->menu_type_id }}">
                                            {{ $menu->menu_name }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-2">เมนูทั่วไป</h2>
                            <div id="regular-menus" class="flex flex-wrap gap-2 mb-4">
                                @foreach ($regularMenus as $menu)
                                    @if (!old('menus') || !array_key_exists($menu->id, old('menus')))
                                        <button type="button"
                                            class="menu-button bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded"
                                            data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->menu_name }}"
                                            data-menu-type-id="{{ $menu->menu_type_id }}">
                                            {{ $menu->menu_name }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- แสดงเมนูที่เลือกและสามารถใส่จำนวนได้ --}}
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-2">รายการเมนูที่เลือก</h2>
                            <div id="selected-menus" class="space-y-2">
                                @if (old('menus'))
                                    @foreach (old('menus') as $menuId => $menu)
                                        <div class="menu-row flex items-center space-x-2 p-2 bg-gray-100 rounded">
                                            <input type="hidden" name="menus[{{ $menuId }}][id]"
                                                value="{{ $menuId }}">
                                            <span
                                                class="flex-grow">{{ $menus->firstWhere('id', $menuId)->menu_name }}</span>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" name="menus[{{ $menuId }}][quantity]"
                                                    value="{{ $menu['quantity'] }}" placeholder="จำนวนที่ผลิต" required
                                                    class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <span class="text-sm text-gray-600">กิโลกรัม</span>
                                            </div>
                                            <button type="button" class="remove-menu text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                บันทึกการผลิต
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedMenusContainer = document.getElementById('selected-menus');
            let selectedMenus = new Set(@json(array_keys(old('menus', [])))); // เก็บรายการเมนูที่เลือกแล้ว

            // จัดการการแสดงเมนูตามประเภท
            const categoryButtons = document.querySelectorAll('.category-button');
            const menuButtons = document.querySelectorAll('.menu-button');

            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-category-id');

                    // เพิ่มคลาส active ให้กับปุ่มที่ถูกกด และลบจากปุ่มอื่น
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('bg-blue-500', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-800');
                    });
                    this.classList.remove('bg-gray-200', 'text-gray-800');
                    this.classList.add('bg-blue-500', 'text-white');
                    
                    // แสดง/ซ่อนเมนูตามประเภทที่เลือก
                    menuButtons.forEach(menuButton => {
                        const menuTypeId = menuButton.getAttribute('data-menu-type-id');
                        if (categoryId === 'all' || categoryId === menuTypeId) {
                            menuButton.style.display = 'inline-block'; // แสดงเมนู
                        } else {
                            menuButton.style.display = 'none'; // ซ่อนเมนู
                        }
                    });
                });
            });

            // จัดการเมื่อคลิกที่ปุ่มเลือกเมนู
            menuButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const menuId = this.getAttribute('data-menu-id');
                    const menuName = this.getAttribute('data-menu-name');

                    // ถ้าเมนูนี้ถูกเลือกแล้ว จะไม่ให้เลือกซ้ำ
                    if (selectedMenus.has(menuId)) {
                        alert('เมนูนี้ถูกเลือกแล้ว');
                        return;
                    }

                    // เพิ่มเมนูเข้าไปในชุดที่เลือก
                    selectedMenus.add(menuId);

                    // ซ่อนปุ่มเมื่อเมนูถูกเลือกแล้ว
                    this.style.display = 'none';

                    // สร้างองค์ประกอบแสดงรายการเมนูที่เลือก
                    const newRow = document.createElement('div');
                    newRow.className =
                        'menu-row flex items-center space-x-2 p-2 bg-gray-100 rounded';
                    newRow.innerHTML = `
                <input type="hidden" name="menus[${menuId}][id]" value="${menuId}">
                <span class="flex-grow">${menuName}</span>
                <div class="flex items-center space-x-2">
                    <input type="number" name="menus[${menuId}][quantity]" placeholder="จำนวนที่ผลิต" required 
                           class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <span class="text-sm text-gray-600">กิโลกรัม</span>
                </div>
                <button type="button" class="remove-menu text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;

                    // เพิ่มรายการใหม่เข้าไปใน DOM
                    selectedMenusContainer.appendChild(newRow);

                    // ผูก event listener สำหรับปุ่มลบ
                    attachRemoveEvent(newRow.querySelector('.remove-menu'), menuId);
                });
            });

            // ฟังก์ชันเพื่อจัดการการลบเมนู
            function attachRemoveEvent(button, menuId) {
                button.addEventListener('click', function() {
                    selectedMenus.delete(menuId);
                    button.closest('.menu-row').remove();

                    // แสดงปุ่มเมนูกลับมา
                    document.querySelector(`[data-menu-id="${menuId}"]`).style.display = 'inline-block';
                });
            }

            // แน่ใจว่าเมนูที่มีอยู่แล้วสามารถลบได้
            document.querySelectorAll('.remove-menu').forEach(button => {
                const menuId = button.closest('.menu-row').querySelector('input[type="hidden"]').value;
                attachRemoveEvent(button, menuId);
            });
        });

        // ตรวจสอบว่ามีข้อมูลใน session หรือไม่ ถ้ามีให้แสดง SweetAlert
        @if (session('insufficientIngredients'))
            let insufficientIngredients = @json(session('insufficientIngredients'));
            let message = '';

            let menuMap = new Map();

            // จัดกลุ่มวัตถุดิบตามเมนู
            insufficientIngredients.forEach(item => {
                if (!menuMap.has(item.menu_name)) {
                    menuMap.set(item.menu_name, []); // สร้างรายการวัตถุดิบสำหรับเมนูใหม่
                }
                menuMap.get(item.menu_name).push(item); // เพิ่มวัตถุดิบเข้าไปในเมนูที่มีอยู่
            });

            // สร้างข้อความเพื่อแสดงผล
            menuMap.forEach((ingredients, menuName) => {
                message += `เมนู: ${menuName}<br>`; // แสดงชื่อเมนู
                ingredients.forEach((ingredient, index) => {
                    message +=
                        `วัตถุดิบที่ ${index + 1}: ${ingredient.ingredient_name}  ต้องการ: ${ingredient.required} ${ingredient.unit} คงเหลือ: ${ingredient.available} ${ingredient.unit}<br>`;
                });
                message += '<br>'; // เพิ่มบรรทัดว่างระหว่างเมนูแต่ละรายการ
            });

            Swal.fire({
                title: 'วัตถุดิบไม่เพียงพอ!',
                html: message, // เปลี่ยนจาก 'text' เป็น 'html'
                icon: 'error',
                confirmButtonText: 'ตกลง'
            });
        @endif


        @if (session('success'))
            Swal.fire({
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        @endif
    </script>
@endsection
