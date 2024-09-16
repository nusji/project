<!-- resources/views/productions/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Production Details</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Order Code: {{ $production->order_code }}</h5>
            <p class="card-text">Created at: {{ $production->created_at }}</p>
        </div>
    </div>

    <h2 class="mt-4">Production Menus</h2>
    @foreach($production->productionMenus as $productionMenu)
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">{{ $productionMenu->menu->name }}</h5>
                <p>Produced Quantity: {{ $productionMenu->produced_quantity }}</p>
                <p>Selling Quantity: {{ $productionMenu->selling_quantity }}</p>

                <h6>Ingredients Used:</h6>
                <ul>
                    @foreach($productionMenu->productionIngredients as $productionIngredient)
                        <li>{{ $productionIngredient->ingredient->name }}: {{ $productionIngredient->used_quantity }} {{ $productionIngredient->ingredient->unit }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach

    <a href="{{ route('productions.index') }}" class="btn btn-primary mt-3">Back to List</a>
</div>
@endsection