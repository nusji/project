@extends('layouts.app')
@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :paths="[
                ['label' => 'ระบบสั่งซื้อวัตถุดิบ', 'url' => route('orders.index')],
                ['label' => 'แก้ไขรายการสั่งซื้อ'],
            ]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">แก้ไขรายการสั่งซื้อ</h1>
                    <form action="{{ route('orders.update', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- วันที่สั่งซื้อ -->
                        <div class="mb-4">
                            <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">วันที่สั่งซื้อ</label>
                            <input type="datetime-local" name="order_date" id="order_date"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('order_date', $order->order_date->format('Y-m-d\TH:i')) }}" required>
                            @error('order_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- รายละเอียดการสั่งซื้อ -->
                        <div class="mb-4">
                            <label for="order_detail" class="block text-sm font-medium text-gray-700 mb-2">รายละเอียดการสั่งซื้อ</label>
                            <textarea name="order_detail" id="order_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>{{ old('order_detail', $order->order_detail) }}</textarea>
                            @error('order_detail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- แนบรูปใบเสร็จ -->
                        <div class="mb-4">
                            <div class="container mx-auto px-4">
                                <div class="flex flex-wrap -mx-4">
                                    <!-- คอลัมน์สำหรับอัปโหลดรูปภาพ -->
                                    <div class="w-full md:w-1/2 px-4 mb-4">
                                        <label for="order_receipt" class="block text-sm font-medium text-gray-700 mb-2">แนบรูปใบเสร็จ</label>
                                        <div class="flex items-center">
                                            <label for="order_receipt" class="flex items-center cursor-pointer">
                                                <span
                                                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-l-md">เลือกไฟล์</span>
                                                <input type="file" name="order_receipt" id="order_receipt" accept="image/*" class="hidden">
                                            </label>
                                            <span id="file-name" class="ml-4 text-gray-600">ยังไม่ได้เลือกไฟล์</span>
                                        </div>
                                        @error('order_receipt')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- คอลัมน์สำหรับแสดงรูปภาพ -->
                                    <div class="w-full md:w-1/2 px-4 mb-4">
                                        <div id="image-preview-container" class="{{ $order->order_receipt ? '' : 'hidden' }}">
                                            <img id="image-preview" src="{{ $order->order_receipt ? asset('storage/' . $order->order_receipt) : '' }}"
                                                class="w-32 h-32 object-cover rounded cursor-pointer"
                                                alt="Preview" onclick="openModal()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal สำหรับแสดงรูปภาพ -->
                            <div id="imageModal"
                                class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                                <div class="bg-white rounded-lg p-4 max-w-3xl w-full mx-4 overflow-auto max-h-screen">
                                    <div class="relative">
                                        <button onclick="closeModal()"
                                            class="absolute top-0 right-0 mt-2 mr-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                                </path>
                                            </svg>
                                        </button>
                                        <img id="modalImage" src="{{ $order->order_receipt ? asset('storage/' . $order->order_receipt) : '' }}"
                                            alt="ใบเสร็จ" class="w-full h-auto max-h-full rounded-lg object-contain">
                                    </div>
                                </div>
                            </div>

                            <!-- สคริปต์สำหรับจัดการรูปภาพและ Modal -->
                            <script>
                                document.getElementById('order_receipt').addEventListener('change', function(event) {
                                    const input = event.target;
                                    const file = input.files[0];

                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const imagePreview = document.getElementById('image-preview');
                                            const previewContainer = document.getElementById('image-preview-container');
                                            const modalImage = document.getElementById('modalImage');
                                            const fileNameSpan = document.getElementById('file-name');

                                            imagePreview.src = e.target.result;
                                            modalImage.src = e.target.result;
                                            previewContainer.classList.remove('hidden');
                                            fileNameSpan.textContent = file.name;
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                });

                                function openModal() {
                                    const modal = document.getElementById('imageModal');
                                    modal.classList.remove('hidden');
                                }

                                function closeModal() {
                                    const modal = document.getElementById('imageModal');
                                    modal.classList.add('hidden');
                                }
                            </script>
                        </div>

                        <!-- รายการวัตถุดิบ -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">รายการวัตถุดิบ</label>
                            <div class="flex space-x-2 mb-4">
                                <input type="text" id="ingredient-search" placeholder="ค้นหาวัตถุดิบ"
                                    class="flex-grow mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button type="button" id="search-ingredient"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                    ค้นหา
                                </button>
                            </div>
                            <div>
                                <label for="ingredient_id"
                                    class="block text-sm font-medium text-green-700 mb-2">วัตถุดิบแนะนำ</label>
                            </div>
                            <div id="search-results" class="mb-4 flex flex-wrap gap-2"></div>
                            <div id="ingredients-container" class="space-y-2">
                                @php
                                    $oldIngredients = old('ingredients', $order->orderDetails->map(function ($detail) {
                                        return [
                                            'id' => $detail->ingredient_id,
                                            'quantity' => $detail->quantity,
                                            'price' => $detail->price,
                                        ];
                                    })->toArray());
                                @endphp
                                @if ($oldIngredients)
                                    @foreach ($oldIngredients as $index => $ingredient)
                                        @php
                                            $ingredientModel = $ingredients->firstWhere('id', $ingredient['id']);
                                        @endphp
                                        <div class="ingredient-row flex items-center space-x-2 p-2 bg-gray-100 rounded">
                                            <input type="hidden" name="ingredients[{{ $index }}][id]"
                                                value="{{ $ingredient['id'] }}">
                                            <span class="flex-grow">{{ $ingredientModel->ingredient_name }}</span>
                                            <div class="flex items-center space-x-2">
                                                <input type="number" name="ingredients[{{ $index }}][quantity]"
                                                    placeholder="จำนวน" value="{{ $ingredient['quantity'] }}"
                                                    class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                    required>
                                                <input type="number" name="ingredients[{{ $index }}][price]"
                                                    placeholder="ราคา" value="{{ $ingredient['price'] }}"
                                                    class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                    required>
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

                        <!-- ปุ่มบันทึก -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                                บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- สคริปต์สำหรับจัดการวัตถุดิบ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let ingredientIndex = {{ count($oldIngredients) }};
            const ingredients = @json($ingredients);
            const selectedIngredients = new Set(
                @json($oldIngredients)
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
                results.forEach(ingredient => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className =
                        'bg-gray-200 hover:bg-gray-300 text-gray-800 font-regular py-1 px-2 rounded mr-2';
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
                <input type="number" name="ingredients[${ingredientIndex}][quantity]" placeholder="จำนวน" required 
                       class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                       
                <input type="number" name="ingredients[${ingredientIndex}][price]" placeholder="ราคารวม" required 
                       class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
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
