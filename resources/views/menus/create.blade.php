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
                                value="{{ old('menu_name') }}" required >
                            @error('menu_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="menu_detail" class="block text-sm font-medium text-gray-700 mb-1">รายละเอียดเมนู</label>
                            <textarea name="menu_detail" id="menu_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('menu_detail') }}</textarea>
                            @error('menu_detail')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="menu_type_id" class="block text-sm font-medium text-gray-700">ประเภทเมนู</label>
                            <select name="menu_type_id" id="menu_type_id"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                @if ($menuTypes->isEmpty())
                                    <option disabled>ไม่พบประเภท กรุณาเพิ่มประเภทก่อน</option>
                                @else
                                    @foreach ($menuTypes as $menuType)
                                        <option value="{{ $menuType->id }}" {{ old('menu_type_id') == $menuType->id ? 'selected' : '' }}>{{ $menuType->menu_type_name }}</option>
                                    @endforeach
                                @endif
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
                                    value="{{ old('menu_price') }}" required>
                                @error('menu_price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- สถานะขาย -->
                            <div class="w-1/3">
                                <label for="menu_status" class="block text-sm font-medium text-gray-700">สถานะขาย</label>
                                <select name="menu_status" id="menu_status"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                                    <option value="1" {{ old('menu_status') == '1' ? 'selected' : '' }}>ขาย</option>
                                    <option value="0" {{ old('menu_status') == '0' ? 'selected' : '' }}>ไม่ขาย</option>
                                </select>
                                @error('menu_status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- รูปเมนู -->
                            <div class="w-1/3">
                                <label for="menu_image" class="block text-sm font-medium text-gray-700">รูปเมนู</label>
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
                                @if(old('ingredients'))
                                    @foreach(old('ingredients') as $index => $ingredient)
                                    <div class="ingredient-row flex space-x-2 mb-2">
                                        <select name="ingredients[{{ $index }}][id]"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required>
                                            @foreach ($ingredients as $option)
                                                <option value="{{ $option->id }}" {{ $ingredient['id'] == $option->id ? 'selected' : '' }}>
                                                    {{ $option->ingredient_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="ingredients[{{ $index }}][amount]" step="0.01" placeholder="จำนวนที่ใช้"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            value="{{ $ingredient['amount'] }}" required>
                                        <button type="button" class="remove-ingredient bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">ลบ</button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="ingredient-row flex space-x-2 mb-2">
                                        <select name="ingredients[0][id]"
                                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            required>
                                            @foreach ($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}">{{ $ingredient->ingredient_name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="ingredients[0][amount]" step="0.01" placeholder="จำนวนที่ใช้"
                                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                            required>
                                        <button type="button" class="remove-ingredient bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">ลบ</button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" id="add-ingredient"
                                class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                เพิ่มวัตถุดิบอีก
                            </button>

                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                บันทึกเมนูใหม่
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let ingredientIndex = {{ count(old('ingredients', [0])) }};
        document.getElementById('add-ingredient').addEventListener('click', function() {
            const container = document.getElementById('ingredients-container');
            const newRow = document.createElement('div');
            newRow.className = 'ingredient-row flex space-x-2 mb-2';
            newRow.innerHTML = `
                <select name="ingredients[${ingredientIndex}][id]" required
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->ingredient_name }}</option>
                    @endforeach
                </select>
                <input type="number" name="ingredients[${ingredientIndex}][amount]" step="0.01" placeholder="จำนวนที่ใช้" required
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <button type="button" class="remove-ingredient bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">ลบ</button>
            `;
            container.appendChild(newRow);
            ingredientIndex++;
        });

        document.getElementById('ingredients-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-ingredient')) {
                e.target.parentElement.remove();
            }
        });
    </script>
@endsection
