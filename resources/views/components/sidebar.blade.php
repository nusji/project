<div>
<aside 
id="sidebar"
    class="h-full w-64 fixed top-0 left-0 bg-slate-700 shadow-lg z-10 transition-transform duration-500 pt-16 md:pt-20">
    <button id="sidebarToggle" class="absolute top-14 right-2 p-2 text-white hover:bg-slate-600 rounded-full"
        aria-label="Toggle Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <div class="flex flex-col p-4 space-y-2">
        <a href="{{ $userRole === 'owner' ? route('dashboard.owner') : route('dashboard.employee') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('dashboard.owner') || request()->routeIs('dashboard.employee') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
            </svg>
            แดชบอร์ด
        </a>

        <a href="{{route('sales.index')}}"
            class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('sales.*') || request()->routeIs('sales.*') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            จัดการการขาย
        </a>

        <hr>
        <h2 class="text-center font-italic text-white">ระบบจัดการ</h2>

        <a href="{{ route('ingredients.index') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('ingredients.*') || request()->routeIs('ingredient_types.*') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            จัดการวัตถุดิบ
        </a>

        <a href="{{ route('orders.index') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('orders.*') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            สั่งซื้อวัตถุดิบ
        </a>

        <a href="{{ route('menus.index') }}"
            class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('menus.*') || request()->routeIs('menu_types.*') ? 'bg-[#E2725B] text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 3h2a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zM9 4h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V4a1 1 0 011-1zM14 4h2a1 1 0 011 1v9a1 1 0 01-1 1h-2a1 1 0 01-1-1V4a1 1 0 011-1zM18 15a3 3 0 103 3 3 3 0 00-3-3z">
                </path>
            </svg>
            จัดการเมนูข้าวแกง
        </a>

        @if ($userRole === 'owner')
            <a href="{{ route('productions.index') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('productions.*') ? 'bg-[#E2725B] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5h16v14H4V5zm4 0v14m8-14v14"></path>
                </svg>
                จัดการการผลิต
            </a>
        @endif
        <!-- Employee Management -->
        @if ($userRole === 'owner')
            <a href="{{ route('employees.index') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('employees.*') ? 'bg-[#E2725B] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 14.25c2.485 0 4.5-2.015 4.5-4.5S14.485 5.25 12 5.25 7.5 7.265 7.5 9.75s2.015 4.5 4.5 4.5Zm0 0c2.692 0 5.25 1.343 5.25 2.684v1.316H6.75v-1.316c0-1.341 2.558-2.684 5.25-2.684Z" />
                </svg>
                จัดการพนักงาน
            </a>
            <a href="{{ route('payrolls.index') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('payrolls.*') || request()->routeIs('salaries.*') ? 'bg-[#E2725B] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                จัดการเงินเดือน
            </a>
            <a href="#"
                class="py-2.5 px-4 text-lg font-medium text-[#F1F5F9] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('.*') ? 'bg-[#E2725B] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                </svg>
                รายงานและสถิติ
            </a>
        @endif

    </div>
</aside>
<button
        id="sidebarRecall"
        class="fixed top-16 left-4 p-2 bg-slate-700 text-white rounded-md shadow-lg hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-700 focus:ring-white"
        aria-label="Show Sidebar">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarRecall = document.getElementById('sidebarRecall');
        const mainContent = document.querySelector('main');
    
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            mainContent.classList.toggle('ml-0');
            mainContent.classList.toggle('ml-64');
            sidebarRecall.classList.toggle('hidden');
        }
    
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
    
        if (sidebarRecall) {
            sidebarRecall.addEventListener('click', toggleSidebar);
        }
    });
    </script>
