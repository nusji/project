@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">สั่งผลิตใหม่</h1>
    <div class="bg-white shadow-md rounded-md p-6">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="order_date" class="block font-medium text-sm text-gray-700">Order Date</label>
                <input type="date" id="order_date" name="order_date" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
            </div>
            <div class="mb-4">
                <label for="order_detail" class="block font-medium text-sm text-gray-700">Order Detail</label>
                <textarea id="order_detail" name="order_detail" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required></textarea>
            </div>
            <div class="mb-4">
                <label for="order_receipt" class="block font-medium text-sm text-gray-700">Order Receipt</label>
                <input type="text" id="order_receipt" name="order_receipt" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
            </div>
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Ingredients</label>
                <div id="ingredients">
                    <div class="ingredient-row mb-2 flex items-center">
                        <select name="ingredients[0][id]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700 flex-1 mr-2" required>
                            <option value="">Select Ingredient</option>
                            @foreach ($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}">{{ $ingredient->ingredient_name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="ingredients[0][quantity]" placeholder="Quantity" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm flex-1 mr-2" required>
                        <input type="number" name="ingredients[0][price]" placeholder="Price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm flex-1 mr-2" required>
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded add-ingredient">Add Ingredient</button>
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Order</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var addIngredientButton = document.querySelector('.add-ingredient');
        var ingredientsContainer = document.getElementById('ingredients');
        var ingredientRowIndex = 1;
        var ingredients = @json($ingredients);

        addIngredientButton.addEventListener('click', function() {
            var newIngredientRow = document.createElement('div');
            newIngredientRow.className = 'ingredient-row mb-2 flex items-center';
            newIngredientRow.innerHTML = `
                <select name="ingredients[${ingredientRowIndex}][id]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-gray-700 flex-1 mr-2" required>
                    <option value="">Select Ingredient</option>
                    @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="ingredients[${ingredientRowIndex}][quantity]" placeholder="Quantity" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm flex-1 mr-2" required>
                <input type="number" name="ingredients[${ingredientRowIndex}][price]" placeholder="Price" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm flex-1 mr-2" required>
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