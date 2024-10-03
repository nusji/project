@extends('layouts.app')

@section('content')
<div id="app" class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="flex">
                <!-- Left side: Menu categories and items -->
                <div class="w-2/3 p-4">
                    <div class="mb-4">
                        <h2 class="text-2xl font-bold mb-2">Menu</h2>
                        <div class="flex space-x-2 mb-4">
                            <button v-for="category in categories" 
                                    @click="currentCategory = category"
                                    :class="['px-4 py-2 rounded-full', currentCategory === category ? 'bg-blue-500 text-white' : 'bg-gray-200']">
                                @{{ category }}
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div v-for="item in filteredItems" 
                             @click="addToCart(item)"
                             class="bg-gray-100 p-4 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                            <h3 class="font-semibold">@{{ item.name }}</h3>
                            <p class="text-gray-600">$@{{ item.price.toFixed(2) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right side: Cart and checkout -->
                <div class="w-1/3 bg-gray-800 text-white p-4">
                    <h2 class="text-2xl font-bold mb-4">Cart</h2>
                    <div class="mb-4 h-96 overflow-y-auto">
                        <div v-for="item in cart" :key="item.id" class="flex justify-between items-center mb-2">
                            <div>
                                <span class="font-semibold">@{{ item.name }}</span>
                                <span class="text-sm text-gray-400">$@{{ item.price.toFixed(2) }} x @{{ item.quantity }}</span>
                            </div>
                            <div class="flex items-center">
                                <button @click="decreaseQuantity(item)" class="px-2 py-1 bg-red-500 rounded-l">-</button>
                                <span class="px-2 bg-gray-700">@{{ item.quantity }}</span>
                                <button @click="increaseQuantity(item)" class="px-2 py-1 bg-green-500 rounded-r">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between text-xl font-bold">
                            <span>Total:</span>
                            <span>$@{{ total.toFixed(2) }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="payment_type" class="block mb-2">Payment Type:</label>
                        <select v-model="paymentType" id="payment_type" class="w-full p-2 bg-gray-700 rounded">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                    <button @click="checkout" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded transition">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/vue@next"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const app = Vue.createApp({
        data() {
            return {
                menuItems: @json($menus),
                cart: [],
                paymentType: 'cash',
                currentCategory: 'All',
                categories: ['All'],
            }
        },
        computed: {
            total() {
                return this.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            },
            filteredItems() {
                if (this.currentCategory === 'All') {
                    return this.menuItems;
                }
                return this.menuItems.filter(item => item.category === this.currentCategory);
            }
        },
        methods: {
            addToCart(item) {
                const existingItem = this.cart.find(i => i.id === item.id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    this.cart.push({...item, quantity: 1});
                }
            },
            increaseQuantity(item) {
                item.quantity++;
            },
            decreaseQuantity(item) {
                if (item.quantity > 1) {
                    item.quantity--;
                } else {
                    this.cart = this.cart.filter(i => i.id !== item.id);
                }
            },
            async checkout() {
                try {
                    const response = await axios.post('{{ route("sales.store") }}', {
                        items: this.cart,
                        payment_type: this.paymentType
                    });
                    if (response.data.success) {
                        alert('Sale completed successfully!');
                        this.cart = [];
                        this.paymentType = 'cash';
                    }
                } catch (error) {
                    console.error('Error during checkout:', error);
                    alert('An error occurred during checkout.');
                }
            }
        },
        created() {
            this.categories = ['All', ...new Set(this.menuItems.map(item => item.category))];
        }
    });

    app.mount('#app');
</script>
@endpush