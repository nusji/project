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
            z-index: 50; /* Ensure the nav is above other content */
        }
    </style>
</head>
<body class="bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 min-h-screen flex flex-col">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md fixed-nav">
        <div class="container mx-auto flex justify-between items-center p-4">
            <a href="{{route('welcome')}}" class="text-2xl font-bold text-red-500">KAOKANG</a>
            <div class="space-x-4">
                <a href="#" class="text-gray-800 hover:text-red-500 transition duration-300">หน้าแรก</a>
                <a href="#" class="text-gray-800 hover:text-red-500 transition duration-300">เมนู</a>
                <a href="#" class="text-gray-800 hover:text-red-500 transition duration-300">ติดต่อเรา</a>
                <a href="{{ url('login') }}" class="text-gray-800 hover:text-red-500 transition duration-300">เฉพาะพนักงาน</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center text-white text-center p-4 mt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white text-gray-800 py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} KAOKANG. สงวนลิขสิทธิ์.</p>
            <p>ออกแบบโดย <a href="mailto:6420610119@email.psu.ac.th" class="text-red-500 hover:underline">6420610119 AMIN DAOH</a></p>
        </div>
    </footer>
</body>
</html>
