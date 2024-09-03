<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยินดีต้อนรับสู่ FastBite</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 min-h-screen flex flex-col items-center justify-center text-white">
    <header class="text-center mb-12">
        <h1 class="text-6xl font-bold mb-4">ยินดีต้อนรับสู่ FastBite</h1>
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

    <a href="#" class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300">
        สั่งอาหารเลย!
        <a href="login" class="bg-white text-red-500 font-bold py-3 px-8 rounded-full text-xl hover:bg-red-100 transition duration-300">
          เฉพาะพนักงาน!
      </a>
    </a>
</body>
</html>