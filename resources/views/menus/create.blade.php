@extends('layouts.app')
@section('content')
    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- เรียกใช้ breadcrumb component -->
            <x-breadcrumb :paths="[['label' => 'ระบบเมนูข้าวแกง', 'url' => route('menus.index')], ['label' => 'เพิ่มเมนูใหม่']]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-4">เพิ่มเมนูใหม่</h1>
                    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="menu_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อเมนู</label>
                            <input type="text" name="menu_name" id="menu_name"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('menu_name') }}">
                            @error('menu_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="menu_detail"
                                class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดเมนู</label>
                            <textarea name="menu_detail" id="menu_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('menu_detail') }}</textarea>
                            @error('menu_detail')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4 grid grid-cols-2 gap-4">
                            <div>
                                <label for="menu_type_id" class="block text-sm font-medium text-gray-700">ประเภทเมนู</label>
                                <select name="menu_type_id" id="menu_type_id"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @if ($menuTypes->isEmpty())
                                        <option disabled>ไม่พบประเภท กรุณาเพิ่มประเภทก่อน</option>
                                    @else
                                        @foreach ($menuTypes as $menuType)
                                            <option value="{{ $menuType->id }}"
                                                {{ old('menu_type_id') == $menuType->id ? 'selected' : '' }}>
                                                {{ $menuType->menu_type_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('menu_type_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="">
                                <label for="menu_taste" class="block text-sm font-medium text-gray-700">รสชาติหลัก</label>
                                <div class="mt-2 flex space-x-2">
                                    <label>
                                        <input type="radio" name="menu_taste" value="หวาน" class="hidden peer"
                                            {{ old('menu_taste') == 'หวาน' ? 'checked' : '' }}>
                                        <span
                                            class="inline-block px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-green-500">หวาน</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="menu_taste" value="เค็ม" class="hidden peer"
                                            {{ old('menu_taste') == 'เค็ม' ? 'checked' : '' }}>
                                        <span
                                            class="inline-block px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-green-500">เค็ม</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="menu_taste" value="เปรี้ยว" class="hidden peer"
                                            {{ old('menu_taste') == 'เปรี้ยว' ? 'checked' : '' }}>
                                        <span
                                            class="inline-block px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-green-500">เปรี้ยว</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="menu_taste" value="เผ็ด" class="hidden peer"
                                            {{ old('menu_taste') == 'เผ็ด' ? 'checked' : '' }}>
                                        <span
                                            class="inline-block px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-green-500">เผ็ด</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="menu_taste" value="จืด" class="hidden peer"
                                            {{ old('menu_taste') == 'จืด' ? 'checked' : '' }}>
                                        <span
                                            class="inline-block px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:ring-2 peer-checked:ring-green-500">จืด</span>
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="flex space-x-4">
                            <!-- ราคาต่อทัพพี -->
                            <div class="w-1/3">
                                <label for="menu_price" class="block text-sm font-medium text-gray-700">ราคาต่อทัพพี</label>
                                <input type="number" name="menu_price" id="menu_price" step="0.01"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    value="{{ old('menu_price') }}">
                                @error('menu_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- สถานะขาย -->
                            <div class="w-1/3">
                                <label for="menu_status" class="block text-sm font-medium text-gray-700">สถานะขาย</label>
                                <select name="menu_status" id="menu_status"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="1" {{ old('menu_status') == '1' ? 'selected' : '' }}>ขาย</option>
                                    <option value="0" {{ old('menu_status') == '0' ? 'selected' : '' }}>ไม่ขาย
                                    </option>
                                </select>
                                @error('menu_status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-full max-w-md">
                                <label for="menu_image" class="block text-sm font-medium text-gray-700 mb-2">
                                    รูปเมนู
                                </label>
                                <div class="mt-1 flex items-center space-x-4">
                                    <div id="image_container" class="flex-shrink-0 w-24 h-24 border border-gray-300 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
                                        <svg id="upload_icon" class="h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <img id="image_preview" class="w-full h-full object-cover hidden" alt="Preview">
                                    </div>
                                    <div class="flex-grow">
                                        <label for="menu_image" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            เลือกรูปภาพ
                                        </label>
                                        <input id="menu_image" name="menu_image" type="file" class="sr-only" accept="image/*">
                                        <p id="file_name" class="mt-2 text-sm text-gray-500"></p>
                                    </div>
                                </div>
                                @error('menu_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <script>
                                document.getElementById('menu_image').addEventListener('change', function(event) {
                                    const file = event.target.files[0];
                                    const uploadIcon = document.getElementById('upload_icon');
                                    const imagePreview = document.getElementById('image_preview');
                                    const fileName = document.getElementById('file_name');
                                    
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            imagePreview.src = e.target.result;
                                            uploadIcon.classList.add('hidden');
                                            imagePreview.classList.remove('hidden');
                                        }
                                        reader.readAsDataURL(file);
                                        fileName.textContent = 'ไฟล์ที่เลือก: ' + file.name;
                                    } else {
                                        uploadIcon.classList.remove('hidden');
                                        imagePreview.classList.add('hidden');
                                        fileName.textContent = '';
                                    }
                                });
                                </script>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">วัตถุดิบที่ใช้</label>
                            <p class="text-sm text-gray-600 mb-4">สูตรอาหารต่อ 1 กิโล</p>
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
                                @if (old('ingredients'))
                                    @foreach (old('ingredients') as $index => $ingredient)
                                        <div class="ingredient-row flex items-center space-x-2 p-2 bg-gray-100 rounded">
                                            <input type="hidden" name="ingredients[{{ $index }}][id]"
                                                value="{{ $ingredient['id'] }}">
                                            <span
                                                class="flex-grow">{{ $ingredients->firstWhere('id', $ingredient['id'])->ingredient_name }}</span>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" name="ingredients[{{ $index }}][amount]"
                                                    step="0.01" placeholder="จำนวน"
                                                    value="{{ $ingredient['amount'] }}"
                                                    class="w-24 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                    required>
                                                <span
                                                    class="text-sm text-gray-600">{{ $ingredients->firstWhere('id', $ingredient['id'])->ingredient_unit }}</span>
                                            </div>
                                            <button type="button"
                                                class="remove-ingredient text-red-500 hover:text-red-700">
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
                                บันทึกเมนูใหม่
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let ingredientIndex = {{ count(old('ingredients', [])) }};
            const ingredients = @json($ingredients);
            const selectedIngredients = new Set(
                @json(old('ingredients', []))
                .map(ingredient => parseInt(ingredient.id))
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

                // Slice เพื่อแสดงผลลัพธ์เพียง 10 รายการแรก
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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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
