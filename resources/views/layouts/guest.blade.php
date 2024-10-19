<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ยินดีต้อนรับสู่ KAOKANG')</title>
    @vite('resources/css/app.css')
    <style>
        .fixed-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            /* Ensure the nav is above other content */
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gradient-to-r from-rose-400 to-red-500 min-h-screen flex flex-col">

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md fixed w-full z-10 transition-all duration-300 ease-in-out">
        <div class="container mx-auto flex justify-between items-center p-4">
            <!-- Logo -->
            <a href="{{ route('welcome') }}"
                class="text-2xl font-bold text-orange-600 flex items-center transform hover:duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 animate-pulse" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                KAOKANG
            </a>
            <!-- Burger Menu Button (for mobile) -->
            <button id="burgerMenu"
                class="text-gray-800 md:hidden focus:outline-none transition-transform duration-300 ease-in-out transform hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <!-- Navigation Links -->
            <div id="navLinks" class="hidden md:flex space-x-6">
                <a href="{{ route('welcome') }}"
                    class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-1 transform group-hover:scale-110 transition-transform duration-300"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="relative overflow-hidden">
                        <span
                            class="inline-block transform transition-transform duration-300 group-hover:-translate-y-full">หน้าแรก</span>
                        <span
                            class="inline-block absolute top-0 left-0 transform translate-y-full transition-transform duration-300 group-hover:-translate-y-0">หน้าแรก</span>
                    </span>
                </a>
                <a href="{{ route('menu-today') }}"
                    class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-1 transform group-hover:scale-110 transition-transform duration-300"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="relative overflow-hidden">
                        <span
                            class="inline-block transform transition-transform duration-300 group-hover:-translate-y-full">เมนู</span>
                        <span
                            class="inline-block absolute top-0 left-0 transform translate-y-full transition-transform duration-300 group-hover:-translate-y-0">เมนู</span>
                    </span>
                </a>
                <a href="#"
                    class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-1 transform group-hover:scale-110 transition-transform duration-300"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    <span class="relative overflow-hidden">
                        <span
                            class="inline-block transform transition-transform duration-300 group-hover:-translate-y-full">ติดต่อเรา</span>
                        <span
                            class="inline-block absolute top-0 left-0 transform translate-y-full transition-transform duration-300 group-hover:-translate-y-0">ติดต่อเรา</span>
                    </span>
                </a>
                <a href="{{ url('login') }}"
                    class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-1 transform group-hover:scale-110 transition-transform duration-300"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="relative overflow-hidden">
                        <span
                            class="inline-block transform transition-transform duration-300 group-hover:-translate-y-full">เฉพาะพนักงาน</span>
                        <span
                            class="inline-block absolute top-0 left-0 transform translate-y-full transition-transform duration-300 group-hover:-translate-y-0">เฉพาะพนักงาน</span>
                    </span>
                </a>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobileMenu"
            class="hidden md:hidden flex flex-col space-y-2 p-4 bg-gray-100 transition-all duration-300 ease-in-out">
            <a href="{{ route('welcome') }}"
                class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center transform hover:translate-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                หน้าแรก
            </a>
            <a href="{{ route('menu-today') }}"
                class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center transform hover:translate-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd"
                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                        clip-rule="evenodd" />
                </svg>
                เมนู
            </a>
            <a href="#"
                class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center transform hover:translate-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
                ติดต่อเรา
            </a>
            <a href="{{ url('login') }}"
                class="text-gray-800 hover:text-red-500 transition duration-300 flex items-center transform hover:translate-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                        clip-rule="evenodd" />
                </svg>
                เฉพาะพนักงาน
            </a>
        </div>
    </nav>
    <script>
        // Toggle the mobile menu visibility with animation
        document.getElementById('burgerMenu').addEventListener('click', function() {
            var mobileMenu = document.getElementById('mobileMenu');
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.classList.remove('opacity-0');
                    mobileMenu.classList.add('opacity-100');
                }, 20);
            } else {
                mobileMenu.classList.remove('opacity-100');
                mobileMenu.classList.add('opacity-0');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 300);
            }
        });

        // Scroll animation for navbar
        window.addEventListener('scroll', function() {
            var nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('bg-opacity-90', 'backdrop-blur-sm');
            } else {
                nav.classList.remove('bg-opacity-90', 'backdrop-blur-sm');
            }
        });
    </script>
    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center text-white text-center p-4 mt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white text-gray-800 py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} KAOKANG. สงวนลิขสิทธิ์</p>
            <p>ออกแบบโดย <a href="mailto:6420610119@email.psu.ac.th" class="text-red-500 hover:underline">6420610119
                    AMIN DAOH</a></p>
        </div>
    </footer>
</body>

</html>
