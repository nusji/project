<!-- resources/views/productions/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Create Production</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productions.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="order_code">
                Order Code
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="order_code" type="text" name="order_code" required>
        </div>

        <div id="menus" class="mb-4">
            <div class="menu-item mb-6">
                <h3 class="text-lg font-semibold mb-2">Menu Item 1</h3>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Menu
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="menus[0][menu_id]" required>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Produced Quantity
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" name="menus[0][produced_quantity]" required min="0" step="0.01">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button type="button" id="add-menu" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add Menu
            </button>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Production
            </button>
        </div>
    </form>
</div>

<script>
    let menuCount = 1;
    document.getElementById('add-menu').addEventListener('click', function() {
        const menuDiv = document.createElement('div');
        menuDiv.className = 'menu-item mb-6';
        menuDiv.innerHTML = `
            <h3 class="text-lg font-semibold mb-2">Menu Item ${++menuCount}</h3>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Menu
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="menus[${menuCount-1}][menu_id]" required>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Produced Quantity
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" name="menus[${menuCount-1}][produced_quantity]" required min="0" step="0.01">
            </div>
        `;
        document.getElementById('menus').appendChild(menuDiv);
    });
</script>
@endsection