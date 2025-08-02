@extends('layouts.guest')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">เลือกเมนูที่ต้องการรีวิว</h1>

        {{-- Search form with real-time search --}}
        <div class="mb-8">
            <div class="relative">
                <input type="text" id="searchInput" name="query" value="{{ $query ?? '' }}"
                    placeholder="พิมพ์เพื่อค้นหาเมนู..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 text-gray-800 focus:border-transparent transition-all"
                    autocomplete="off">
                <div class="absolute right-3 top-3 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Error message --}}
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Menu list with grid layout --}}
        <div id="menuContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @if (isset($menus) && $menus->count() > 0)
                @foreach ($menus as $menu)
                    <div class="menu-item bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-4"
                        data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->menu_name }}">
                        {{-- Assume we have menu image --}}
                        <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}"
                            class="w-full h-48 object-cover rounded-md mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $menu->menu_name }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($menu->menu_detail ?? '', 100) }}</p>
                        <button onclick="selectMenu({{ $menu->id }})"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-gray-100 font-medium py-2 px-4 rounded-md transition-colors">
                            รีวิวเมนูนี้
                        </button>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-lg">ไม่พบเมนูที่ค้นหา</p>
                </div>
            @endif
        </div>

        {{-- Hidden form for submitting selected menu --}}
        <form id="reviewForm" action="{{ route('feedbacks.review') }}" method="GET" class="hidden">
            @csrf
            <input type="hidden" name="menu_id" id="selectedMenuId">
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const menuItems = document.querySelectorAll('.menu-item');

            // Real-time search functionality
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                menuItems.forEach(item => {
                    const menuName = item.dataset.menuName.toLowerCase();
                    if (menuName.includes(searchTerm)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Show/hide no results message
                const visibleItems = document.querySelectorAll('.menu-item:not(.hidden)');
                const noResults = document.querySelector('.col-span-full');
                if (visibleItems.length === 0) {
                    if (!noResults) {
                        const noResultsDiv = document.createElement('div');
                        noResultsDiv.className = 'col-span-full text-center py-8 text-gray-500';
                        noResultsDiv.innerHTML = `
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-lg">ไม่พบเมนูที่ค้นหา</p>
                    `;
                        document.getElementById('menuContainer').appendChild(noResultsDiv);
                    }
                } else if (noResults) {
                    noResults.remove();
                }
            });
        });

        function selectMenu(menuId) {
            document.getElementById('selectedMenuId').value = menuId;
            document.getElementById('reviewForm').submit();
        }
    </script>
@endsection
