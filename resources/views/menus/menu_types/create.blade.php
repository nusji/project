@extends('layouts.app')

@section('title', 'จัดการวัตถุดิบ')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create Menu Type</h1>

    <form action="{{ route('menu_types.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label for="menu_type_name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="menu_type_name" name="menu_type_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label for="menu_type_detail" class="block text-sm font-medium text-gray-700">Detail</label>
            <textarea id="menu_type_detail" name="menu_type_detail" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
</div>
@endsection