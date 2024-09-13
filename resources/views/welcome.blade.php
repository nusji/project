<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยินดีต้อนรับสู่ KAOKANG</title>
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
            <a href="#" class="text-2xl font-bold text-red-500">KAOKANG</a>
            <div class="space-x-4">
                <a href="#" class="text-gray-800 hover:text-red-500 transition duration-300">หน้าแรก</a>
                <a href="#" class="text-gray-800 hover:text-red-500 transition duration-300">เมนู</a>
                <a href="#" class="text-gray-800 hover:text-red-500 transition duration-300">ติดต่อเรา</a>
                <a href="login" class="text-gray-800 hover:text-red-500 transition duration-300">เข้าสู่ระบบ</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col items-center justify-center text-white text-center p-4 mt-20">
        <!-- เพิ่ม mt-20 เพื่อให้เนื้อหาหลักไม่ถูกบังโดย navigation bar -->
        <header class="mb-12">
            <h1 class="text-6xl font-bold mb-4">ยินดีต้อนรับสู่ KAOKANG</h1>
            <p class="text-2xl">อร่อยเร็ว อิ่มไว ในราคาสบายกระเป๋า</p>
        </header>

        <div class="flex flex-wrap justify-center gap-8 mb-12">
            @foreach(['เบอร์เกอร์', 'พิซซ่า', 'เฟรนช์ฟรายส์'] as $item)
                <div class="bg-white rounded-lg p-6 shadow-lg text-center">
                    <img src="{{ asset('images/' . strtolower($item) . '.svg') }}" alt="{{ $item }}" class="w-24 h-24 mx-auto mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{ $item }}</h2>
                </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="#" class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300 mb-4 inline-block">
                สั่งอาหารเลย!
            </a>
            <br>
            <a href="login" class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300 inline-block">
                เฉพาะพนักงาน!
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white text-gray-800 py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} KAOKANG. สงวนลิขสิทธิ์.</p>
            <p>ออกแบบโดย <a href="#" class="text-red-500 hover:underline">ทีมพัฒนา</a></p>
        </div>
    </footer>
</body>
</html>
