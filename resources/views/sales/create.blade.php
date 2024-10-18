@extends('layouts.pos')

@section('content')
    <div id="pos-app" class="bg-gray-100 min-h-screen">
        <div class="container mx-auto p-0">
            <div class="bg-white overflow-hidden">
                <div class="flex">
                    <!-- ซ้าย: รายการเมนู -->
                    <div class="w-2/3 p-4 min-h-screen pb-24">
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-2xl font-bold" id="menu-title">เมนูวันที่ <span
                                        id="selected-date">{{ $today->format('d/m/Y') }}</span></h2>
                                <div class="flex items-center">
                                    <input type="date" id="date-picker" class="border rounded px-2 py-1 mr-2"
                                        value="{{ $today->format('Y-m-d') }}">
                                    <button id="load-menu-btn"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                        โหลดเมนู
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-5 gap-4" id="menu-items-container">
                                @foreach ($menus as $menu)
                                    <div class="bg-gray-100 p-4 rounded-lg cursor-pointer hover:bg-gray-200 transition menu-item"
                                        data-category="{{ $menu->menu_type_id }}" onclick="addToCart({{ $menu->id }})">
                                        @if ($menu->menu_image)
                                            <img src="{{ Storage::url($menu->menu_image) }}" alt="{{ $menu->menu_name }}"
                                                class="menu-image h-10 w-10 rounded-md">
                                        @endif
                                        <h3 class="font-semibold">{{ $menu->menu_name }}</h3>
                                        <p class="text-gray-600">{{ number_format($menu->menu_price, 2) }} บาท</p>
                                        <p class="text-gray-600">เหลือ:
                                            {{ number_format($menu->total_remaining_amount, 1) }} กิโลกรัม</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- เพิ่มส่วนของประเภทเมนู -->
                    <div class="fixed bottom-0 w-full bg-gray-200 justify-around">
                        <div class="flex space-x-2 p-4" id="category-buttons">
                            <button class="bg-green-500 hover:bg-green-600 text-white font-bold w-20 h-12 rounded"
                                onclick="filterMenuItems('all')">ทั้งหมด</button>
                            @foreach ($categories as $category)
                                <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold w-20 h-12 rounded"
                                    onclick="filterMenuItems({{ $category->id }})">{{ $category->menu_type_name }}</button>
                            @endforeach
                        </div>
                    </div>
                    <!-- ขวา: ตะกร้าสินค้า -->
                    <div class="w-1/3 bg-gray-800 text-white p-4 z-50">
                        <h2 class="text-2xl font-bold mb-4">ตะกร้า (ทัพพี)</h2>
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
                            <label for="payment_type" class="block mb-2 font-medium text-white-700">ประเภทการชำระเงิน
                                :</label>
                            <div class="flex justify-center items-center space-x-4">
                                <label class="relative">
                                    <input type="radio" name="payment_type" value="เงินสด" class="sr-only peer">
                                    <div
                                        class="flex items-center px-14 py-2 border rounded-md transition-colors cursor-pointer
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
                                        class="flex items-center px-14 py-2 border rounded-md transition-colors cursor-pointer
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

                        <button onclick="clearCart()"
                            class="w-full bg-grey-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded transition mt-4">
                            เคลียร์ตะกร้า
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
                <span class="px-4 py-1 bg-gray-700">{quantity}</span>
                <button onclick="increaseQuantity({id})" class="px-2 py-1 bg-green-500 rounded-r">+</button>
            </div>
        </div>
    </template>
    <script>
        let currentMenus = @json($menus);

        function loadMenuByDate() {
            const selectedDate = document.getElementById('date-picker').value;
            fetch(`/sales/menus-by-date?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    currentMenus = data.menus;
                    document.getElementById('selected-date').textContent = new Date(data.date).toLocaleDateString(
                        'en-GB');
                    renderMenuItems();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดเมนู: ' + error.message);
                });
        }


        function renderMenuItems() {
            console.log('Rendering menu items:', currentMenus); // ตรวจสอบว่ามีเมนูอะไรบ้าง
            const container = document.getElementById('menu-items-container');
            container.innerHTML = '';

            currentMenus.forEach(menu => {
                const menuItem = document.createElement('div');
                menuItem.className =
                    'bg-gray-100 p-4 rounded-lg cursor-pointer hover:bg-gray-200 transition menu-item';
                menuItem.dataset.category = menu.menu_type_id;

                // Check if the item is sold out
                let soldOut = menu.total_remaining_amount <= 0;

                menuItem.innerHTML = `
            ${menu.menu_image ? `<img src="${menu.menu_image}" alt="${menu.menu_name}" class="menu-image h-10 w-10">` : ''}
            <h3 class="font-semibold">${menu.menu_name}</h3>
            <p class="text-gray-600">${Number(menu.menu_price).toFixed(2)} บาท</p>
            <p class="text-gray-600">เหลือ: ${Number(menu.total_remaining_amount).toFixed(1)} กิโลกรัม</p>
            ${soldOut ? '<p class="text-red-500 font-bold">สินค้าหมด</p>' : ''}
        `;
                // Disable click if sold out
                if (!soldOut) {
                    menuItem.onclick = () => addToCart(menu.id);
                } else {
                    menuItem.classList.add('opacity-50', 'cursor-not-allowed');
                }
                container.appendChild(menuItem);
            });
        }

        document.getElementById('load-menu-btn').addEventListener('click', loadMenuByDate);

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

        // ฟังก์ชันสำหรับเคลียร์ตะกร้า
        function clearCart() {
            cart = []; // ล้างรายการในตะกร้า
            renderCart(); // อัปเดตการแสดงผล
        }
    </script>
@endsection
