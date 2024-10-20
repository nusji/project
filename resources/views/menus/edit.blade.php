@extends('layouts.app')
@section('content')
    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- เรียกใช้ breadcrumb component -->
            <x-breadcrumb :paths="[['label' => 'ระบบเมนูข้าวแกง', 'url' => route('menus.index')], ['label' => 'แก้ไขเมนู']]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-4">แก้ไขเมนู</h1>
                    <form action="{{ route('menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- This is needed for the update method -->

                        <div class="mb-4">
                            <label for="menu_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อเมนู</label>
                            <input type="text" name="menu_name" id="menu_name"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('menu_name', $menu->menu_name) }}">
                            @error('menu_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="menu_detail"
                                class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดเมนู</label>
                            <textarea name="menu_detail" id="menu_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('menu_detail', $menu->menu_detail) }}</textarea>
                            @error('menu_detail')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4 grid grid-cols-3 gap-4">
                            <div>
                                <label for="menu_type_id" class="block text-sm font-medium text-gray-700">ประเภทเมนู</label>
                                <select name="menu_type_id" id="menu_type_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach ($menuTypes as $menuType)
                                        <option value="{{ $menuType->id }}"
                                            {{ old('menu_type_id', $menu->menu_type_id) == $menuType->id ? 'selected' : '' }}>
                                            {{ $menuType->menu_type_name }}</option>
                                    @endforeach
                                </select>
                                @error('menu_type_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="menu_taste" class="block text-sm font-medium text-gray-700">รสชาติหลัก</label>
                                <div class="mt-2 flex space-x-2">
                                    @php
                                        $tastes = ['หวาน', 'เค็ม', 'เปรี้ยว', 'เผ็ด', 'จืด'];
                                    @endphp
                                    @foreach ($tastes as $taste)
                                        <label>
                                            <input type="radio" name="menu_taste" value="{{ $taste }}"
                                                class="hidden peer"
                                                {{ old('menu_taste', $menu->menu_taste) == $taste ? 'checked' : '' }}>
                                            <span
                                                class="inline-block px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-green-500">
                                                {{ $taste }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <label for="portion_size" class="block text-sm font-medium text-gray-700"><span class="text-sm italic">ปริมาณต่อ 1 ทัพพี (หน่วยเป็นกิโล 0.00 )</span></label>
                                    <input type="number" name="portion_size" id="portion_size" step="0.01"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="ปริมาณ ( เช่น 0.2 )" value="{{ old('portion_size', $menu->portion_size) }}">
                                </label>
                            </div>
                        </div>

                        <div class="flex space-x-4">
                            <div class="w-1/3">
                                <label for="menu_price" class="block text-sm font-medium text-gray-700">ราคาต่อทัพพี</label>
                                <input type="number" name="menu_price" id="menu_price" step="0.01"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    value="{{ old('menu_price', $menu->menu_price) }}">
                                @error('menu_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- ส่วนของรูปเมนู -->
                            <div class="w-full max-w-md">
                                <label for="menu_image" class="block text-sm font-medium text-gray-700 mb-2">
                                    รูปเมนู
                                </label>
                                <div class="mt-1 flex items-center space-x-4">
                                    <div id="image_container"
                                        class="flex-shrink-0 w-24 h-24 border border-gray-300 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                                        @if ($menu->menu_image)
                                            <!-- แสดงรูปภาพเก่าถ้ามี -->
                                            <img id="image_preview" src="{{ asset('storage/' . $menu->menu_image) }}"
                                                class="w-full h-full object-cover" alt="Menu Image">
                                        @else
                                            <!-- แสดงไอคอนอัปโหลดถ้าไม่มีรูปภาพ -->
                                            <svg id="upload_icon" class="h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <img id="image_preview" class="w-full h-full object-cover hidden"
                                                alt="Preview">
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <label for="menu_image"
                                            class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            เลือกรูปภาพ
                                        </label>
                                        <input id="menu_image" name="menu_image" type="file" class="sr-only"
                                            accept="image/*">
                                        <p id="file_name" class="mt-2 text-sm text-gray-500"></p>
                                    </div>
                                </div>
                                @error('menu_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- ส่วนของวัตถุดิบ -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">วัตถุดิบที่ใช้</label>
                            <p class="text-sm text-gray-600 mb-4">สูตรอาหารต่อ 1 กิโล จะได้ 10 ทัพพี ต่อ 1 กิโลกรัม</p>
                            <div class="flex space-x-2 mb-4">
                                <input type="text" id="ingredient-search" placeholder="ค้นหาวัตถุดิบ"
                                    class="flex-grow mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button type="button" id="search-ingredient"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    ค้นหา
                                </button>
                            </div>
                            <div id="search-results" class="mb-4 flex flex-wrap gap-2"></div>

                            <div id="ingredients-container" class="space-y-2">
                                @php
                                    $oldIngredients = old('ingredients');
                                    if ($oldIngredients === null) {
                                        $oldIngredients = [];
                                        foreach ($menu->ingredients as $ingredient) {
                                            $oldIngredients[] = [
                                                'id' => $ingredient->id,
                                                'amount' => $ingredient->pivot->amount,
                                            ];
                                        }
                                    }
                                @endphp

                                @foreach ($oldIngredients as $index => $ingredient)
                                    <div class="ingredient-row flex items-center space-x-2 p-2 bg-gray-100 rounded">
                                        <input type="hidden" name="ingredients[{{ $index }}][id]"
                                            value="{{ $ingredient['id'] }}">
                                        <span
                                            class="flex-grow">{{ $ingredients->firstWhere('id', $ingredient['id'])->ingredient_name }}</span>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" name="ingredients[{{ $index }}][amount]"
                                                step="0.01" placeholder="จำนวน" value="{{ $ingredient['amount'] }}"
                                                required
                                                class="w-24 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                            <span
                                                class="text-sm text-gray-600">{{ $ingredients->firstWhere('id', $ingredient['id'])->ingredient_unit }}</span>
                                        </div>
                                        <button type="button" class="remove-ingredient text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- ปุ่มอัปเดตเมนู -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                อัปเดตเมนู
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const fileInput = document.getElementById('menu_image');
            const uploadIcon = document.getElementById('upload_icon');
            const imagePreview = document.getElementById('image_preview');
            const imageContainer = document.getElementById('image_container');
            const fileName = document.getElementById('file_name');

            // ถ้ามีรูปภาพปัจจุบัน แสดงผลรูปภาพนั้น
            @if ($menu->menu_image)
                imagePreview.src = "{{ asset('storage/' . $menu->menu_image) }}";
                imagePreview.classList.remove('hidden');
                uploadIcon.classList.add('hidden');
            @endif

            // เมื่อมีการเลือกไฟล์ใหม่ ให้แสดงตัวอย่างของรูปที่เลือก
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                        uploadIcon.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                    fileName.textContent = 'ไฟล์ที่เลือก: ' + file.name;
                } else {
                    // ถ้าไม่มีไฟล์ที่เลือก ซ่อนตัวอย่างและแสดงไอคอนอัปโหลด
                    uploadIcon.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                    fileName.textContent = '';
                }
            });

            let ingredientIndex = {{ count($oldIngredients) }};
            const ingredients = @json($ingredients);
            const selectedIngredients = new Set(
                @php
                    echo json_encode(
                        array_map(function ($ingredient) {
                            return intval($ingredient['id']);
                        }, $oldIngredients),
                    );
                @endphp
            );

            const searchInput = document.getElementById('ingredient-search');
            const searchButton = document.getElementById('search-ingredient');
            const searchResults = document.getElementById('search-results');
            const ingredientsContainer = document.getElementById('ingredients-container');

            function updateSearchResults() {
                const searchTerm = searchInput.value.toLowerCase();
                const results = ingredients.filter(ingredient =>
                    ingredient.ingredient_name.toLowerCase().includes(searchTerm) &&
                    !selectedIngredients.has(ingredient.id)
                );
                displaySearchResults(results);
            }

            searchButton.addEventListener('click', updateSearchResults);
            searchInput.addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    updateSearchResults();
                }
            });

            function displaySearchResults(results) {
                searchResults.innerHTML = '';

                const limitedResults = results.slice(0, 20);

                limitedResults.forEach(ingredient => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className =
                        'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded';
                    button.textContent = ingredient.ingredient_name;
                    button.onclick = function() {
                        addIngredient(ingredient);
                        updateSearchResults();
                    };
                    searchResults.appendChild(button);
                });
            }

            function addIngredient(ingredient) {
                if (selectedIngredients.has(ingredient.id)) {
                    alert('วัตถุดิบนี้ถูกเพิ่มไปแล้ว');
                    return;
                }

                const newRow = document.createElement('div');
                newRow.className = 'ingredient-row flex items-center space-x-2 p-2 bg-gray-100 rounded';
                newRow.innerHTML = `
                <input type="hidden" name="ingredients[${ingredientIndex}][id]" value="${ingredient.id}">
                <span class="flex-grow">${ingredient.ingredient_name}</span>
                <div class="flex items-center space-x-2">
                    <input type="number" name="ingredients[${ingredientIndex}][amount]" step="0.01" placeholder="จำนวน" required class="w-24 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <span class="text-sm text-gray-600">${ingredient.ingredient_unit}</span>
                </div>
                <button type="button" class="remove-ingredient text-red-500 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
                ingredientsContainer.appendChild(newRow);
                selectedIngredients.add(ingredient.id);
                ingredientIndex++;
            }

            ingredientsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-ingredient')) {
                    const row = e.target.closest('.ingredient-row');
                    const ingredientId = parseInt(row.querySelector('input[type="hidden"]').value);
                    selectedIngredients.delete(ingredientId);
                    row.remove();
                    updateSearchResults();
                }
            });

            // Initial update of search results
            updateSearchResults();
        });
    </script>
@endsection
