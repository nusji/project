<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'kaokang') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('button.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="bg-[#f5f5f5]">

    <!-- Navbar -->
    <nav class="bg-[#FFFFFF] shadow-sm fixed w-full z-20 top-0 left-0">
        <div class="container mx-auto flex justify-between items-center p-2">
            <!-- Logo -->
            <div class="flex items-center">
                <img src="{{ asset('images/logo.svg') }}" alt="logo" class="h-10 mr-3">
                <a class="text-xl font-semibold text-[#000000]" href="#">
                    Kaokang MIS
                </a>
            </div>
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Display user name -->
                <span class="text-[#000000]">สวัสดีคุณ {{ Auth::user()->first_name }} (รหัสพนักงาน :
                    {{ Auth::user()->id }})</span>

                <!-- Logout button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-[#f03939] text-[#FFFFFF] hover:bg-[#a70b0b] hover:text-[#d4d4d4] border border-[#ff9d9d] py-2 px-3 rounded-lg flex items-center space-x-2 transition duration-300 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        <span>ออกจากระบบ</span>
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-button" class="md:hidden text-2xl focus:outline-none text-[#07a189]">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside
        class="h-full w-64 fixed top-0 left-0 bg-[#FFFFFF] shadow-lg z-10 transition-transform duration-500 pt-16 md:pt-20">
        <div class="flex flex-col p-4 space-y-2">
            <a href="{{ Auth::user()->role === 'owner' ? route('dashboard.owner') : route('dashboard.employee') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
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
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                จัดการวัตถุดิบ
            </a>

            <a href="{{ route('
            ') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                จัดการสั่งซื้อวัตถุดิบ
            </a>

            <a href="{{ route('menus.index') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 3h2a1 1 0 011 1v9a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1zM9 4h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V4a1 1 0 011-1zM14 4h2a1 1 0 011 1v9a1 1 0 01-1 1h-2a1 1 0 01-1-1V4a1 1 0 011-1zM18 15a3 3 0 103 3 3 3 0 00-3-3z">
                    </path>
                </svg>
                จัดการเมนูข้าวแกง
            </a>

            <a href="#"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5h16v14H4V5zm4 0v14m8-14v14"></path>
                </svg>
                รายงาน
            </a>

            <!-- Employee Management -->
            @if (Auth::user()->role === 'owner')
                <a href="{{ route('employees.index') }}"
                    class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
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
                    class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
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




    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="fixed inset-0 bg-white z-20 transform -translate-x-full transition-transform duration-500 ease-in-out">
        <button id="close-menu" class="absolute top-4 right-4 text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <div class="flex flex-col p-4 space-y-2 mt-16">
            <a href="{{ Auth::user()->role === 'owner' ? route('dashboard.owner') : route('dashboard.employee') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                จัดการขาย
            </a>

            <a href="#"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5h16v14H4V5zm4 0v14m8-14v14"></path>
                </svg>
                วัตถุดิบ
            </a>
            <a href="#"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                รายงาน
            </a>
            <a href="{{ route('employees.index') }}"
                class="py-2.5 px-4 text-lg font-medium text-[#07a189] rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4m-2 6v-6m14 6v-6"></path>
                </svg>
                ข้อมูลพนักงาน
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="ml-64 mt-16 p-6">
        @yield('content')
    </main>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: "{{ session('success') }}",
            });
        </script>
    @endif
</body>

</html>
