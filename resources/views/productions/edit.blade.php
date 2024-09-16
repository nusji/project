<!-- resources/views/productions/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Production</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productions.update', $production->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="order_code">Order Code</label>
            <input type="text" class="form-control" id="order_code" name="order_code" value="{{ $production->order_code }}" required>
        </div>

        <div id="menus">
            @foreach($production->productionMenus as $index => $productionMenu)
                <div class="menu-item">
                    <h3>Menu Item {{ $index + 1 }}</h3>
                    <div class="form-group">
                        <label>Menu</label>
                        <select class="form-control" name="menus[{{ $index }}][menu_id]" required>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" {{ $productionMenu->menu_id == $menu->id ? 'selected' : '' }}>
                                    {{ $menu->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Produced Quantity</label>
                        <input type="number" class="form-control" name="menus[{{ $index }}][produced_quantity]" value="{{ $productionMenu->produced_quantity }}" required min="0" step="0.01">
                    </div>
                </div>
            @endforeach