<!-- resources/views/feedbacks/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200 font-bold text-xl">
                Submit Your Feedback
            </div>

            <div class="p-6">
                <form id="feedbackForm" action="{{ route('feedbacks.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="menu-search" class="block text-gray-700 text-sm font-bold mb-2">Search Menu:</label>
                        <input type="text" id="menu-search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Type to search...">
                    </div>

                    <div class="mb-4">
                        <label for="menu" class="block text-gray-700 text-sm font-bold mb-2">Select Menu:</label>
                        <select name="menu_id" id="menu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Select a menu</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <span class="block text-gray-700 text-sm font-bold mb-2">Rating:</span>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden" required />
                                <label for="star{{ $i }}" class="text-3xl text-gray-300 hover:text-yellow-400 cursor-pointer transition-colors duration-150">★</label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">Comment (Optional):</label>
                        <textarea name="comment" id="comment" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Submit Feedback
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <h5 class="text-lg font-semibold mb-2">Recommended Menus:</h5>
                    <div id="recommended-menus" class="flex flex-wrap"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuSearch = document.getElementById('menu-search');
        const menuSelect = document.getElementById('menu');
        const recommendedMenus = document.getElementById('recommended-menus');
    
        const allMenus = @json($menus);
    
        function filterMenus() {
            const searchTerm = menuSearch.value.toLowerCase();
            const filteredMenus = allMenus.filter(menu => 
                menu.menu_name.toLowerCase().includes(searchTerm)
            );
    
            // Update select options
            menuSelect.innerHTML = '<option value="">Select a menu</option>';
            filteredMenus.forEach(menu => {
                const option = new Option(menu.menu_name, menu.id);
                menuSelect.add(option);
            });
    
            // Update recommended menus
            updateRecommendedMenus(filteredMenus.slice(0, 5));
        }
    
        function updateRecommendedMenus(menus) {
            recommendedMenus.innerHTML = menus.map(menu => 
                `<button type="button" onclick="selectMenu(${menu.id}, '${menu.menu_name}')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-full mr-2 mb-2 transition-colors duration-150">
                    ${menu.menu_name}
                </button>`
            ).join('');
        }
    
        function selectMenu(id, name) {
            menuSelect.value = id;
            // Trigger change event if you have any listeners on the select element
            menuSelect.dispatchEvent(new Event('change'));
        }
    
        menuSearch.addEventListener('input', filterMenus);
    
        // Initial population of recommended menus
        updateRecommendedMenus(allMenus.slice(0, 5));
    
        // Make selectMenu function global so it can be called from inline onclick
        window.selectMenu = selectMenu;
    });
    </script>
    <style>
        /* Custom styles for star rating */
        input[name="rating"]:checked ~ label {
            color: #FBBF24; /* yellow-400 */
        }
    </style>
@endsection