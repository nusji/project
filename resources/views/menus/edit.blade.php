<!-- resources/views/menus/edit.blade.php -->
@extends('layouts.app')
@section('content')
    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- เรียกใช้ breadcrumb component -->
            <x-breadcrumb :paths="[['label' => 'ระบบเมนูข้าวแกง', 'url' => route('menus.index')], ['label' => 'แก้ไขเมนู']]" />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-4">Edit Menu</h1>
                    <form action="{{ route('menus.update', $menu) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="menu_name" class="block text-sm font-medium text-gray-700 mb-1">ชื่อเมนู</label>
                            <input type="text" name="menu_name" id="menu_name"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ $menu->menu_name }}">
                            @error('menu_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="menu_detail"
                                class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดเมนู</label>
                            <textarea name="menu_detail" id="menu_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                {{ $menu->menu_detail }}
                            </textarea>
                            @error('menu_detail')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="menu_type_id" class="block text-sm font-medium text-gray-700">ประเภทเมนู</label>
                            <select name="menu_type_id" id="menu_type_id"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach ($menuTypes as $menuType)
                                    <option value="{{ $menuType->id }}"
                                        {{ $menu->menu_type_id == $menuType->id ? 'selected' : '' }}>
                                        {{ $menuType->menu_type_name }}</option>
                                @endforeach
                            </select>
                            @error('menu_type_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex space-x-4">
                            <!-- ราคาต่อทัพพี -->
                            <div class="w-1/3">
                                <label for="menu_price" class="block text-sm font-medium text-gray-700">ราคาต่อทัพพี</label>
                                <input type="number" name="menu_price" id="menu_price" step="0.01"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    value="{{ $menu->menu_price }}">
                                @error('menu_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- สถานะขาย -->
                            <div class="w-1/3">
                                <label for="menu_status" class="block text-sm font-medium text-gray-700">สถานะขาย</label>
                                <select name="menu_status" id="menu_status"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="1" {{ $menu->menu_status ? 'selected' : '' }}>พร้อมขาย</option>
                                    <option value="0" {{ !$menu->menu_status ? 'selected' : '' }}>ไม่พร้อมขาย</option>
                                </select>
                                @error('menu_status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- รูปเมนู -->
                            <div class="w-1/3">
                                <label for="menu_image" class="block text-sm font-medium text-gray-700">รูปเมนู</label>
                                @if ($menu->menu_image)
                                    <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}"
                                        class="mb-2 w-32 h-32 object-cover">
                                @endif
                                <input type="file" name="menu_image" id="menu_image"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('menu_image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">วัตถุดิบที่ใช้</label>
                            <label class="block text-sm font-medium text-gray-700">สูตรอาหารต่อ 1 กิโล จะได้ประมาณ 8-10 ทัพพี ต่อ 1 กิโลกรัม</label>
                            <div id="ingredients-container">
                                @foreach ($menu->recipes as $index => $recipe)
                                    <div class="ingredient-row flex space-x-2 mb-2">
                                        <select name="ingredients[{{ $index }}][id]"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required>
                                            @foreach ($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}"
                                                    {{ $recipe->ingredient_id == $ingredient->id ? 'selected' : '' }}>
                                                    {{ $ingredient->ingredient_name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="ingredients[{{ $index }}][amount]"
                                            step="0.01" value="{{ $recipe->amount }}" placeholder="amount"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            required>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-ingredient"
                                class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add
                                Ingredient</button>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Menu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            let ingredientIndex = {{ count($menu->recipes) }};
            document.getElementById('add-ingredient').addEventListener('click', function() {
                const container = document.getElementById('ingredients-container');
                const newRow = document.createElement('div');
                newRow.className = 'ingredient-row flex space-x-2 mb-2';
                newRow.innerHTML = `
                <select name="ingredients[${ingredientIndex}][id]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="ingredients[${ingredientIndex}][amount]" step="0.01" placeholder="Amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
            `;
                container.appendChild(newRow);
                ingredientIndex++;
            });
        </script>
    @endpush
@endsection
