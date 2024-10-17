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

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">รายการเมนูที่ผลิต</label>
                            @error('menus')
                                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
                            @enderror
                            <div class="flex space-x-2 mb-4">
                                <input type="text" id="menu-search" placeholder="ค้นหาเมนู"
                                    class="flex-grow mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <button type="button" id="search-menu"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                                    ค้นหา
                                </button>
                            </div>
                            <div class="flex space-x-2 mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">ประเภทเมนู :</label>

                                <button type="button" id="show-all-categories"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded mr-2">
                                    แสดงทั้งหมด
                                </button>
                                @foreach ($menuCategories as $category)
                                    <button type="button"
                                        class="category-button bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded mr-2"
                                        data-category-id="{{ $category->id }}">
                                        {{ $category->menu_type_name }}
                                    </button>
                                @endforeach
                            </div>

                            <div id="search-results" class="mb-4 flex flex-wrap gap-2"></div>
                            <div id="menus-container" class="space-y-2">
                                @if (old('menus'))
                                    @foreach (old('menus') as $index => $menu)
                                        <div class="menu-row flex items-center space-x-2 p-2 bg-gray-100 rounded">
                                            <input type="hidden" name="menus[{{ $index }}][id]"
                                                value="{{ $menu['id'] }}">
                                            <span
                                                class="flex-grow">{{ $menus->firstWhere('id', $menu['id'])->menu_name }}</span>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" name="menus[{{ $index }}][quantity]"
                                                    placeholder="จำนวนที่ผลิต" value="{{ $menu['quantity'] }}"
                                                    class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                    required>
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
            let menuIndex = {{ count(old('menus', [])) }};
            const menus = @json($menus);
            const menuCategories = @json($menuCategories);
            const selectedMenus = new Set(
                @json(old('menus', []))
                .map(menu => parseInt(menu.id))
            );

            const searchInput = document.getElementById('menu-search');
            const searchButton = document.getElementById('search-menu');
            const searchResults = document.getElementById('search-results');
            const menusContainer = document.getElementById('menus-container');

            function updateSearchResults(results) {
                searchResults.innerHTML = '';
                results.forEach(menu => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className =
                        'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded mr-2 mb-2';
                    button.textContent = menu.menu_name;
                    button.onclick = function() {
                        addMenu(menu);
                        // หลังจากเพิ่มเมนูแล้ว อัปเดตผลการค้นหาใหม่
                        filterAndDisplayMenus();
                    };
                    searchResults.appendChild(button);
                });
            }

            function filterAndDisplayMenus(categoryId = null) {
                let results = menus.filter(menu => !selectedMenus.has(menu.id));
                if (categoryId) {
                    results = results.filter(menu => menu.menu_type_id == categoryId);
                } else if (searchInput.value.trim() !== '') {
                    const searchTerm = searchInput.value.toLowerCase();
                    results = results.filter(menu => menu.menu_name.toLowerCase().includes(searchTerm));
                }
                updateSearchResults(results);
            }

            searchButton.addEventListener('click', function() {
                // ลบคลาส active จากปุ่มประเภทเมนูทั้งหมด
                document.querySelectorAll('.category-button').forEach(btn => {
                    btn.classList.remove('bg-gray-700', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-800');
                });
                // ลบคลาส active จากปุ่ม "แสดงทั้งหมด"
                document.getElementById('show-all-categories')?.classList.remove('bg-gray-700',
                    'text-white');
                document.getElementById('show-all-categories')?.classList.add('bg-gray-200',
                    'text-gray-800');
                // กรองและแสดงผลตามคำค้นหา
                filterAndDisplayMenus();
            });

            searchInput.addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    searchButton.click();
                }
            });

            // จัดการกับการคลิกปุ่มประเภทเมนู
            document.querySelectorAll('.category-button').forEach(button => {
                button.addEventListener('click', function() {
                    // ลบคลาส active จากปุ่มประเภทเมนูทั้งหมด
                    document.querySelectorAll('.category-button').forEach(btn => {
                        btn.classList.remove('bg-gray-700', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-800');
                    });
                    // ลบคลาส active จากปุ่ม "แสดงทั้งหมด"
                    document.getElementById('show-all-categories')?.classList.remove('bg-gray-700',
                        'text-white');
                    document.getElementById('show-all-categories')?.classList.add('bg-gray-200',
                        'text-gray-800');
                    // เพิ่มคลาส active ให้กับปุ่มที่ถูกคลิก
                    this.classList.remove('bg-gray-200', 'text-gray-800');
                    this.classList.add('bg-gray-700', 'text-white');

                    const categoryId = this.getAttribute('data-category-id');
                    // ล้างคำค้นหา
                    searchInput.value = '';
                    filterAndDisplayMenus(categoryId);
                });
            });

            // จัดการกับการคลิกปุ่ม "แสดงทั้งหมด"
            document.getElementById('show-all-categories')?.addEventListener('click', function() {
                // ลบคลาส active จากปุ่มประเภทเมนูทั้งหมด
                document.querySelectorAll('.category-button').forEach(btn => {
                    btn.classList.remove('bg-gray-700', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-800');
                });
                // เพิ่มคลาส active ให้กับปุ่ม "แสดงทั้งหมด"
                this.classList.remove('bg-gray-200', 'text-gray-800');
                this.classList.add('bg-gray-700', 'text-white');
                // ล้างคำค้นหา
                searchInput.value = '';
                filterAndDisplayMenus();
            });

            function addMenu(menu) {
                if (selectedMenus.has(menu.id)) {
                    alert('เมนูนี้ถูกเพิ่มไปแล้ว');
                    return;
                }

                const newRow = document.createElement('div');
                newRow.className = 'menu-row flex items-center space-x-2 p-2 bg-gray-100 rounded';
                newRow.innerHTML = `
            <input type="hidden" name="menus[${menuIndex}][id]" value="${menu.id}">
            <span class="flex-grow">${menu.menu_name}</span>
            <div class="flex items-center space-x-2">
                <input type="number" name="menus[${menuIndex}][quantity]" placeholder="จำนวนที่ผลิต" required 
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
                menusContainer.appendChild(newRow);
                selectedMenus.add(menu.id);
                menuIndex++;
            }

            menusContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-menu')) {
                    const row = e.target.closest('.menu-row');
                    const menuId = parseInt(row.querySelector('input[type="hidden"]').value);
                    selectedMenus.delete(menuId);
                    row.remove();
                    // อัปเดตผลการค้นหาใหม่
                    filterAndDisplayMenus();
                }
            });

            // อัปเดตผลการค้นหาเริ่มต้น
            filterAndDisplayMenus();
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
