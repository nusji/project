<aside class="h-full w-64 fixed top-0 left-0 bg-[#FFFFFF] shadow-lg z-10 transition-transform duration-500 pt-16 md:pt-20">
    <div class="flex flex-col p-4 space-y-2">
        <a href="{{ Auth::user()->role === 'owner' ? route('dashboard.owner') : route('dashboard.employee') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('dashboard.owner') || request()->routeIs('dashboard.employee') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            แดชบอร์ด
        </a>

        <a href="#"
            class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            จัดการขาย
        </a>
        
        <hr>

        <a href="{{ route('ingredients.index') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('ingredients.index') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            วัตถุดิบ
        </a>

        <a href="{{ route('orders.index') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('orders.index') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            สั่งซื้อวัตถุดิบ
        </a>

        <a href="{{ route('menus.index') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('menus.index') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 3h2a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zM9 4h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V4a1 1 0 011-1zM14 4h2a1 1 0 011 1v9a1 1 0 01-1 1h-2a1 1 0 01-1-1V4a1 1 0 011-1zM18 15a3 3 0 103 3 3 3 0 00-3-3z">
                </path>
            </svg>
            เมนูข้าวแกง
        </a>

        <a href="{{ route('productions.index') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('productions.index') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 5h16v14H4V5zm4 0v14m8-14v14"></path>
            </svg>
            การผลิต
        </a>

        <!-- Employee Management -->
        @if (Auth::user()->role === 'owner')
            <a href="{{ route('employees.index') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('employees.index') ? 'bg-[#E2725B] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14.5c-1.933 0-3.5-1.567-3.5-3.5S10.067 7.5 12 7.5 15.5 9.067 15.5 11 13.933 14.5 12 14.5zm0-1.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM21 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2h18zM2 21v-2a6 6 0 0 1 6-6h12a6 6 0 0 1 6 6v2">
                    </path>
                </svg>
                ข้อมูลพนักงาน
            </a>
        @elseif (Auth::user()->role === 'employee')
            <a href="{{ route('employees.index') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('employees.index') ? 'bg-[#E2725B] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14.5c-1.933 0-3.5-1.567-3.5-3.5S10.067 7.5 12 7.5 15.5 9.067 15.5 11 13.933 14.5 12 14.5zm0-1.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4zM21 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2h18zM2 21v-2a6 6 0 0 1 6-6h12a6 6 0 0 1 6 6v2">
                    </path>
                </svg>
                ข้อมูลตัวเอง
            </a>
        @endif

    </div>
</aside>
