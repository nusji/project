<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Create Menu</h1>

    <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded shadow-md">
        @csrf

        <div class="mb-4">
            <label for="menu_name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="menu_name" name="menu_name"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
        </div>

        <div class="mb-4">
            <label for="menu_detail" class="block text-sm font-medium text-gray-700">Detail</label>
            <textarea id="menu_detail" name="menu_detail" rows="4"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
        </div>
        <div class="mb-4">
            <label for="menu_type_id" class="block text-sm font-medium text-gray-700">Menu Type</label>
            <select id="menu_type_id" name="menu_type_id"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
                <option value="">Select Menu Type</option>
                @foreach ($menuTypes as $menuType)
                    <option value="{{ $menuType->id }}">{{ $menuType->menu_type_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="menu_price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" id="menu_price" name="menu_price" step="0.01"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
        </div>

        <div class="mb-4">
            <label for="menu_status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="menu_status" name="menu_status"
                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                required>
                <option value="1">Available</option>
                <option value="0">Not Available</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="menu_image" class="block text-sm font-medium text-gray-700">Image</label>
            <input type="file" id="menu_image" name="menu_image"
                class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
</div>
</body>

</html>
