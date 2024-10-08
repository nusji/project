@extends('layouts.pos')

@section('content')
    <div id="pos-app" class="bg-gray-100 min-h-screen">
        <div class="container mx-auto p-0">
            <div class="bg-white overflow-hidden">
                <div class="flex">
                    <!-- ซ้าย: รายการเมนู -->
                    <div class="w-2/3 p-4">
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold mb-2">เมนูวันนี้</h2>
                            <!-- เพิ่มส่วนของประเภทเมนู -->
                            <div class="mb-4">
                                <h3 class="text-xl font-semibold mb-2">ประเภทเมนู</h3>
                                <div class="flex space-x-2" id="category-buttons">
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                        onclick="filterMenuItems('all')">ทั้งหมด</button>
                                    @foreach ($categories as $category)
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                            onclick="filterMenuItems({{ $category->id }})">{{ $category->menu_type_name }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4" id="menu-items-container">
                                @foreach ($menus as $menu)
                                    <div class="bg-gray-100 p-4 rounded-lg cursor-pointer hover:bg-gray-200 transition menu-item"
                                        data-category="{{ $menu->menu_type_id }}" onclick="addToCart({{ $menu->id }})">
                                        @if ($menu->menu_image)
                                        <img src="{{ Storage::url($menu->menu_image) }}" alt="{{ $menu->menu_name }}" class="menu-image h-10 w-10">
                                        @endif
                                        <h3 class="font-semibold">{{ $menu->menu_name }}</h3>
                                        <p class="text-gray-600">{{ number_format($menu->menu_price, 2) }} บาท</p>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- ขวา: ตะกร้าสินค้า -->
                    <div class="w-1/3 bg-gray-800 text-white p-4">
                        <h2 class="text-2xl font-bold mb-4">ตะกร้า</h2>
                        <div class="mb-4 h-96 overflow-y-auto" id="cart-items-container">
                            <!-- รายการในตะกร้าจะถูกแสดงที่นี่ -->
                        </div>
                        <div class="mb-4">
                            <div class="flex justify-between text-xl font-bold">
                                <span>รวม:</span>
                                <span id="cart-total">0.00 บาท</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="payment_type"
                                class="block mb-2 font-medium text-white-700">ประเภทการชำระเงิน:</label>
                            <div class="flex space-x-4">
                                <label class="relative">
                                    <input type="radio" name="payment_type" value="เงินสด" class="sr-only peer">
                                    <div
                                        class="flex items-center px-4 py-2 border rounded-md transition-colors cursor-pointer
                                                peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500
                                                bg-white text-gray-700 border-gray-300 hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        เงินสด
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="payment_type" value="โอนเงิน" class="sr-only peer">
                                    <div
                                        class="flex items-center px-4 py-2 border rounded-md transition-colors cursor-pointer
                                                peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500
                                                bg-white text-gray-700 border-gray-300 hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        โอนเงิน
                                    </div>
                                </label>
                            </div>
                        </div>
                        <button onclick="checkout()"
                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded transition">
                            ชำระเงิน
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="cart-item-template">
        <div class="flex justify-between items-center mb-2">
            <div>
                <span class="font-semibold">{name}</span>
                <span class="text-sm text-gray-400">{price} บาท x {quantity}</span>
            </div>
            <div class="flex items-center">
                <button onclick="decreaseQuantity({id})" class="px-2 py-1 bg-red-500 rounded-l">-</button>
                <span class="px-2 bg-gray-700">{quantity}</span>
                <button onclick="increaseQuantity({id})" class="px-2 py-1 bg-green-500 rounded-r">+</button>
            </div>
        </div>
    </template>
    <script>
        // เพิ่มฟังก์ชันสำหรับกรองรายการเมนูตามประเภท
        function filterMenuItems(categoryId) {
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                if (categoryId === 'all' || item.dataset.category === categoryId.toString()) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // เรียกใช้ filterMenuItems('all') เมื่อโหลดหน้าเพื่อแสดงทุกรายการเริ่มต้น
        document.addEventListener('DOMContentLoaded', () => {
            filterMenuItems('all');
        });

        let cart = [];
        let menuItems = @json($menus); // เมนูของวันนั้น

        // ฟังก์ชันสำหรับเพิ่มสินค้าในตะกร้า
        function addToCart(itemId) {
            console.log('Adding to cart:', itemId); // เช็คว่ามีการเรียกใช้ฟังก์ชันหรือไม่
            const menuItem = menuItems.find(i => i.id === itemId); // ค้นหารายการเมนูที่เลือก
            if (!menuItem) {
                console.error('Menu item not found:', itemId);
                return;
            }
            console.log('Menu item:', menuItem); // ตรวจสอบว่าพบเมนูที่เลือกหรือไม่

            const existingItem = cart.find(i => i.id === itemId); // ตรวจสอบว่ามีสินค้าในตะกร้าแล้วหรือไม่

            if (existingItem) {
                existingItem.quantity++; // ถ้ามีอยู่แล้ว เพิ่มจำนวนสินค้า
            } else {
                cart.push({
                    ...menuItem,
                    quantity: 1 // ถ้าไม่มี ให้เพิ่มรายการใหม่พร้อมจำนวนเป็น 1
                });
            }
            console.log('Cart:', cart); // ตรวจสอบว่าตะกร้ามีรายการหรือไม่
            renderCart(); // แสดงผลตะกร้าใหม่
        }

        // ฟังก์ชันสำหรับแสดงผลรายการในตะกร้า
        function renderCart() {
            const container = document.getElementById('cart-items-container');
            const template = document.getElementById('cart-item-template').innerHTML;

            container.innerHTML = cart.map(item =>
                template
                .replace(/{id}/g, item.id) // ใช้ {id} หลายตำแหน่ง
                .replace('{name}', item.menu_name)
                .replace('{price}', item.menu_price.toFixed(2))
                .replace(/{quantity}/g, item.quantity) // แทนค่าจำนวนสินค้า
            ).join('');

            document.getElementById('cart-total').textContent = cart.reduce((sum, item) => sum + item.menu_price * item
                .quantity, 0).toFixed(2) + ' บาท';
        }

        // ฟังก์ชันสำหรับลดจำนวนสินค้าในตะกร้า
        function decreaseQuantity(itemId) {
            const item = cart.find(i => i.id === itemId);
            if (item && item.quantity > 1) {
                item.quantity--;
            } else {
                cart = cart.filter(i => i.id !== itemId); // เอาสินค้าที่จำนวนเป็น 0 ออกจากตะกร้า
            }
            renderCart();
        }

        // ฟังก์ชันสำหรับเพิ่มจำนวนสินค้าในตะกร้า
        function increaseQuantity(itemId) {
            const item = cart.find(i => i.id === itemId);
            if (item) {
                item.quantity++;
            }
            renderCart();
        }

        // ฟังก์ชันสำหรับชำระเงิน
        function checkout() {

            const paymentType = document.querySelector('input[name="payment_type"]:checked').value;

            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่มีสินค้าในตะกร้า',
                    text: 'กรุณาเพิ่มสินค้าในตะกร้าก่อน',
                });
                return;
            }

            // Log ข้อมูลที่ส่งไปยัง backend
            const dataToSend = {
                items: cart,
                payment_type: paymentType,
            };
            console.log('Sending data to backend:', dataToSend);

            axios.post('{{ route('sales.store') }}', dataToSend)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'การขายสำเร็จ!',
                            text: 'การชำระเงินเสร็จสมบูรณ์',
                        }).then(() => {
                            cart = [];
                            renderCart();
                        });
                    }
                })
                .catch(error => {
                    console.error(error.response); // ตรวจสอบ response
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'เกิดข้อผิดพลาดในการชำระเงิน: ' + error.response.data.message,
                    });
                });
        }
    </script>
@endsection
