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
                        @method('PUT') <!-- ใช้ PUT สำหรับอัปเดตข้อมูล -->
                        <div class="mb-4">
                            <label for="order_date"
                                class="block text-sm font-medium text-gray-700 mb-2">วันที่สั่งซื้อ</label>
                            <input type="datetime-local" name="order_date" id="order_date"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                value="{{ old('order_date', $order->order_date) }}" required>
                            @error('order_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="order_detail"
                                class="block text-sm font-medium text-gray-700 mb-2">รายละเอียดการสั่งซื้อ</label>
                            <textarea name="order_detail" id="order_detail" rows="3"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('order_detail', $order->order_detail) }}</textarea>
                            @error('order_detail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="order_receipt"
                                class="block text-sm font-medium text-gray-700 mb-2">แนบรูปใบเสร็จ
                            </label>
                            <div class="container mx-auto px-4 border-2 rounded-md p-2 justify-center">
                                <div class="flex flex-wrap -mx-4 ">
                                    <!-- คอลัมน์สำหรับอัปโหลดรูปภาพ -->
                                    <div class="w-full md:w-1/2 px-4 mb-4 ">
                                        <div class="flex items-center">
                                            <label for="order_receipt"
                                                class="flex items-center cursor-pointer bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M4 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H4zm3 5a1 1 0 112 0 1 1 0 11-2 0zm3-2a2 2 0 100 4 2 2 0 000-4zm3 2a1 1 0 110-2 1 1 0 010 2z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                เลือกไฟล์
                                            </label>
                                            <input type="file" name="order_receipt" id="order_receipt" accept="image/*"
                                                class="hidden">
                                            <span id="file-name" class="ml-4 text-gray-600">ยังไม่ได้เลือกไฟล์</span>
                                        </div>
                                        @error('order_receipt')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- คอลัมน์สำหรับแสดงรูปภาพ -->
                                    <div class="w-full md:w-1/4 px-4 mb-4">
                                        <div id="image-preview-container"
                                            class="border border-gray-300 rounded-lg overflow-hidden bg-gray-50 p-4">
                                            @if ($order->order_receipt)
                                                <img id="image-preview"
                                                    src="{{ asset('storage/' . $order->order_receipt) }}"
                                                    class="w-full h-auto object-cover rounded cursor-pointer" alt="Preview"
                                                    onclick="openModal()">
                                            @else
                                                <p class="text-gray-500">ยังไม่มีรูปภาพใบเสร็จที่แนบ</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div id="imageModal" class="fixed z-10 inset-0 overflow-y-auto hidden"
                                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                        aria-hidden="true"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                        aria-hidden="true">&#8203;</span>
                                    <div
                                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <img id="modalImage" class="w-full h-auto" alt="Full size preview">
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                onclick="closeModal()">
                                                ปิด
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.getElementById('order_receipt').addEventListener('change', function(event) {
                                    const input = event.target;
                                    const file = input.files[0];
                                    const fileNameElement = document.getElementById('file-name');

                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const imagePreview = document.getElementById('image-preview');
                                            const previewContainer = document.getElementById('image-preview-container');
                                            const modalImage = document.getElementById('modalImage');

                                            imagePreview.src = e.target.result;
                                            modalImage.src = e.target.result;
                                            previewContainer.classList.remove('hidden');
                                            fileNameElement.textContent = file.name;
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        fileNameElement.textContent = 'ยังไม่ได้เลือกไฟล์';
                                    }
                                });

                                function openModal() {
                                    document.getElementById('imageModal').classList.remove('hidden');
                                }

                                function closeModal() {
                                    document.getElementById('imageModal').classList.add('hidden');
                                }
                            </script>
                        </div>

                        <!-- ส่วนของรายการวัตถุดิบ (แก้ไขตามที่มีอยู่ใน order) -->
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
                                                placeholder="จำนวน" value="{{ $ingredient->pivot->quantity }}"
                                                class="w-32 py-1 px-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                                required>
                                            <span class="text-sm text-gray-600">{{ $ingredient->ingredient_unit }}</span>

                                            <input type="number" name="ingredients[{{ $index }}][price]"
                                                placeholder="ราคา" value="{{ $ingredient->pivot->price }}"
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

                                @error('ingredients')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
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
                                อัปเดตข้อมูล
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
