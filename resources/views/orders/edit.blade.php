@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="order_date" class="block font-medium text-sm text-gray-700">Order Date</label>
                            <input type="date" id="order_date" name="order_date" value="{{ $order->order_date }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="order_detail" class="block font-medium text-sm text-gray-700">Order Detail</label>
                            <textarea id="order_detail" name="order_detail" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ $order->order_detail }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="order_receipt" class="block font-medium text-sm text-gray-700">Order Receipt</label>
                            <input type="text" id="order_receipt" name="order_receipt" value="{{ $order->order_receipt }}" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Ingredients</label>
                            <div id="ingredients">
                                @foreach ($order->orderDetails as $orderDetail)
                                <div class="ingredient-row mb-2">
                                    <select name="ingredients[{{ $loop->index }}][id]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Select Ingredient</option>
                                        @foreach ($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}" {{ $ingredient->id == $orderDetail->ingredient_id ? 'selected' : '' }}>{{ $ingredient->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="ingredients[{{ $loop->index }}][quantity]" value="{{ $orderDetail->quantity }}" placeholder="Quantity" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <input type="number" name="ingredients[{{ $loop->index }}][price]" value="{{ $orderDetail->price }}" placeholder="Price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded remove-ingredient">Remove</button>
                                </div>
                                @endforeach
                                <div class="ingredient-row mb-2">
                                    <select name="ingredients[{{ count($order->orderDetails) }}][id]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select Ingredient</option>
                                        @foreach ($ingredients as $ingredient)
                                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="ingredients[{{ count($order->orderDetails) }}][quantity]" placeholder="Quantity" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <input type="number" name="ingredients[{{ count($order->orderDetails) }}][price]" placeholder="Price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded add-ingredient">Add Ingredient</button>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var addIngredientButton = document.querySelector('.add-ingredient');
            var ingredientsContainer = document.getElementById('ingredients');
            var ingredientRowIndex = {{ count($order->orderDetails) }};

            addIngredientButton.addEventListener('click', function() {
                var newIngredientRow = document.createElement('div');
                newIngredientRow.className = 'ingredient-row mb-2';
                newIngredientRow.innerHTML = `
                    <select name="ingredients[${ingredientRowIndex}][id]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Select Ingredient</option>
                        @foreach ($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="ingredients[${ingredientRowIndex}][quantity]" placeholder="Quantity" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <input type="number" name="ingredients[${ingredientRowIndex}][price]" placeholder="Price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded remove-ingredient">Remove</button>
                `;
                ingredientsContainer.appendChild(newIngredientRow);
                ingredientRowIndex++;

                var removeIngredientButtons = document.querySelectorAll('.remove-ingredient');
                removeIngredientButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        this.parentNode.remove();
                    });
                });
            });
        });
    </script>
@endsection