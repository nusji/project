<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#e6f3f0] h-screen flex items-center justify-center">
    <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden flex flex-col md:flex-row">
        <!-- Illustration -->
        <div class="flex-1 bg-gray-100 p-8 flex items-center justify-center">
            <img src="{{ asset('images/loginlogo.svg') }}" alt="Illustration" class="w-full max-w-md h-auto">
        </div>
        <!-- Login Form -->
        <div class="flex-1 p-8">
            <h3 class="text-2xl font-semibold mb-6 text-center">เข้าสู่ระบบ</h3>
            <form method="POST" action="{{route("login")}}">
                @csrf
                <div class="mb-4">
                    <input id="username" type="text" class="form-input w-full border border-gray-300 rounded-lg py-3 px-4 @error('username') border-red-500 @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="รหัสผู้ใช้งาน">
                    @error('username')
                        <span class="text-red-500 text-sm mt-1 block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-6">
                    <input id="password" type="password" class="form-input w-full border border-gray-300 rounded-lg py-3 px-4 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password" placeholder="รหัสผ่าน">
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-6">
                    <button type="submit" class="w-full bg-[#07a189] hover:bg-[#058d78] text-white font-semibold py-3 px-4 rounded-lg">
                        {{ __('เข้าใช้งาน') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('error'))
    <script>
        window.Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: '{{ session('error') }}'
        });
    </script>
@endif

</body>

</html>
