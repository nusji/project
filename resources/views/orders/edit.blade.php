@extends('layouts.app')
@section('content')
    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :paths="[
                ['label' => 'ระบบสั่งซื้อวัตถุดิบ', 'url' => route('orders.index')],
                ['label' => 'แก้ไขรายการสั่งซื้อ'],
            ]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-6">แก้ไขรายการสั่งซื้อ</h1>
                    <form action="{{ route('orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- ใช้ PUT สำหรับการแก้ไข -->

                        <div class="mb-4">
                            <label for="order_date"
                                class="block text-sm font-medium text-gray-700 mb-2">วันที่สั่งซื้อ</label>
                            <input type="datetime-local" name="order_date" id="order_date"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('order_date', $order->order_date->format('Y-m-d\TH:i')) }}" required>
                            @error('order_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="order_detail"
                                class="block text-sm font-medium text-gray-700 mb-2">รายละเอียดการสั่งซื้อ</label>
                            <textarea name="order_detail" id="order_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>{{ old('order_detail', $order->order_detail) }}</textarea>
                            @error('order_detail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="container mx-auto px-4">
                                <div class="flex flex-wrap -mx-4">
                                    <!-- คอลัมน์สำหรับอัปโหลดรูปภาพ -->
                                    <div class="w-full md:w-1/2 px-4 mb-4">
                                        <label for="order_receipt"
                                            class="block text-sm font-medium text-gray-700 mb-2">แนบรูปใบเสร็จ</label>
                                        <input type="file" name="order_receipt" id="order_receipt"
                                            class="block w-full h-10 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                        @error('order_receipt')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror

                                        @if ($order->order_receipt)
                                            <div id="image-preview-container">
                                                <img id="image-preview"
                                                    class="w-32 h-32 object-cover rounded cursor-pointer"
                                                    src="{{ asset('storage/' . $order->order_receipt) }}" alt="Preview"
                                                    onclick="openModal()">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- คอลัมน์สำหรับแสดงรูปภาพ -->
                                    <div class="w-full md:w-1/2 px-4 mb-4">
                                        <!-- Modal -->
                                        <div id="imageModal" class="fixed z-10 inset-0 overflow-y-auto hidden"
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
                                                        <img id="modalImage" class="w-full h-auto"
                                                            src="{{ asset('storage/' . $order->order_receipt) }}"
                                                            alt="Full size preview">
                                                    </div>
                                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                        <button type="button"
                                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                            onclick="closeModal()">ปิด</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">รายการวัตถุดิบ</label>
                            <div id="ingredients-container" class="space-y-2">
                                @foreach ($order->ingredients as $index => $ingredient)
                                    <div class="ingredient-row flex items-center space-x-2 p-2 bg-gray-100 rounded">
                                        <input type="hidden" name="ingredients[{{ $index }}][id]"
                                            value="{{ $ingredient->id }}">
                                        <span class="flex-grow">{{ $ingredient->ingredient_name }}</span>
                                        <div class="flex items-center space-x-2">
                                            <input type="number" name="ingredients[{{ $index }}][quantity]"
                                                placeholder="จำนวน"
                                                value="{{ old('ingredients.' . $index . '.quantity', $ingredient->pivot->quantity) }}"
                                                class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                required>
                                            <input type="number" name="ingredients[{{ $index }}][price]"
                                                placeholder="ราคา"
                                                value="{{ old('ingredients.' . $index . '.price', $ingredient->pivot->price) }}"
                                                class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                required>
                                        </div>
                                        <button type="button" class="remove-ingredient text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let ingredientIndex = {{ count(old('ingredients', $order->ingredients)) }};
            const ingredients = @json($ingredients);
            const selectedIngredients = new Set(
                @json(old('ingredients', $order->ingredients))
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
                <input type="number" name="ingredients[${ingredientIndex}][price]" placeholder="ราคา" required 
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
