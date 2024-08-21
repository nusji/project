@extends('layouts.app')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">Create New Production</h1>
    <form action="{{ route('productions.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="production_date">
                Production Date
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="production_date" name="production_date" type="date" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="comment">
                Comment
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="comment" name="comment"></textarea>
        </div>
        <h2 class="text-2xl font-bold mb-4">Production Details</h2>
        <div id="production-details">
            <div class="form-row mb-4" data-index="0">
                <div class="mb-4 w-full md:w-1/2 pr-2">
                    <label class="block text-gray-700 font-bold mb-2" for="menu_id_0">
                        Menu
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="menu_id_0" name="menu_id[]" required>
                        <option value="">Select Menu</option>
                        @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4 w-full md:w-1/2 pl-2">
                    <label class="block text-gray-700 font-bold mb-2" for="quantity_0">
                        Quantity
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="quantity_0" name="quantity[]" type="number" required>
                </div>
            </div>
        </div>
        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="addProductionDetail()">
            Add More
        </button>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create
        </button>
    </form>
</div>
@endsection