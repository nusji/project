<!-- resources/views/menus/create.blade.php -->
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
                            <label for="menu_name" class="block text-sm font-medium text-gray-700">ชื่อเมนู</label>
                            <input type="text" name="menu_name" id="menu_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="menu_detail" class="block text-sm font-medium text-gray-700">รายละเอียดเมนู</label>
                            <textarea name="menu_detail" id="menu_detail" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="menu_type_id" class="block text-sm font-medium text-gray-700">ประเภทเมนู</label>
                            <select name="menu_type_id" id="menu_type_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                @foreach($menuTypes as $menuType)
                                    <option value="{{ $menuType->id }}">{{ $menuType->menu_type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="menu_price" class="block text-sm font-medium text-gray-700">ราคาต่อทัพพี</label>
                            <input type="number" name="menu_price" id="menu_price" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                        <div class="mb-4">
                            <label for="menu_status" class="block text-sm font-medium text-gray-700">สถานะขาย</label>
                            <select name="menu_status" id="menu_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="1">Available</option>
                                <option value="0">Not Available</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="menu_image" class="block text-sm font-medium text-gray-700">รูปเมนู</label>
                            <input type="file" name="menu_image" id="menu_image" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">วัตถุดิบที่ใช้</label>
                            <label class="block text-sm font-medium text-gray-700">สูตรอาหารต่อ 1 กิโล  จะได้ประมาณ 8-10 ทัพพี ต่อ 1 กิโลกรัม</label>
                            <div id="ingredients-container">
                                <div class="ingredient-row flex space-x-2 mb-2">
                                    <select name="ingredients[0][id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">{{ $ingredient->ingredient_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="ingredients[0][amount]" step="0.01" placeholder="จำนวนที่ใช้" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>
                            <button type="button" id="add-ingredient" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">เพิ่มวัตถุดิบอีก</button>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                บันทึกเมนูใหม่
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let ingredientIndex = 1;
        document.getElementById('add-ingredient').addEventListener('click', function() {
            const container = document.getElementById('ingredients-container');
            const newRow = document.createElement('div');
            newRow.className = 'ingredient-row flex space-x-2 mb-2';
            newRow.innerHTML = `
                <select name="ingredients[${ingredientIndex}][id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->ingredient_name }}</option>
                    @endforeach
                </select>
                <input type="number" name="ingredients[${ingredientIndex}][amount]" step="0.01" placeholder="Amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
            `;
            container.appendChild(newRow);
            ingredientIndex++;
        });
    </script>
@endsection