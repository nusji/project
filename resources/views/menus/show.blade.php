<!-- resources/views/menus/show.blade.php -->
@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-semibold">{{ $menu->menu_name }}</h1>
                        <div class="flex space-x-2">
                            <a href="{{ route('menus.edit', $menu) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this menu?')">Delete</button>
                            </form>
                        </div>
                    </div>
                    


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if($menu->menu_image)
                                <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}" class="w-full h-auto object-cover rounded-lg mb-4">
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg mb-4">
                                    <span class="text-gray-500">No image available</span>
                                </div>
                            @endif
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <p class="text-gray-700 mb-2"><span class="font-semibold">Type:</span> {{ $menu->menuType->name }}</p>
                                <p class="text-gray-700 mb-2"><span class="font-semibold">Price:</span> ฿{{ number_format($menu->menu_price, 2) }}</p>
                                <p class="text-gray-700 mb-2">
                                    <span class="font-semibold">Status:</span> 
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $menu->menu_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $menu->menu_status ? 'Available' : 'Not Available' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold mb-4">Details</h2>
                            <p class="text-gray-700 mb-4">{{ $menu->menu_detail ?: 'No details available.' }}</p>

                            <h2 class="text-2xl font-semibold mb-4">Ingredients</h2>
                            @if($menu->recipes->count() > 0)
                                <ul class="list-disc list-inside space-y-2">
                                    @foreach($menu->recipes as $recipe)
                                        <li class="text-gray-700">
                                            {{ $recipe->ingredient->ingredient_name }}  {{ $recipe->amount }} {{ $recipe->ingredient->ingredient_unit }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-700">No ingredients listed for this menu.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('menus.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Back to Menu List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection