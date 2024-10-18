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
<body class="bg-slate-700 h-screen flex items-center justify-center">
    <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden flex flex-col md:flex-row">
        <!-- Illustration -->
        <div class="flex-1 p-8 flex items-center justify-center">
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
                <div class="mb-6 relative">
                    <input id="password" type="password" 
                        class="form-input w-full border border-gray-300 rounded-lg py-3 px-4 @error('password') border-red-500 @enderror" 
                        name="password" required autocomplete="current-password" placeholder="รหัสผ่าน">
                    <button type="button" 
                        class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 focus:outline-none" 
                        onclick="togglePassword()">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 9a9 9 0 100-18 9 9 0 000 18z" />
                        </svg>
                    </button>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
       
                <div class="mb-6">
                    <button type="submit" class="w-full bg-[#F97316] hover:bg-[#EA580C] text-white font-semibold py-3 px-4 rounded-lg">
                        {{ __('เข้าใช้งาน') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
                    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 9a9 9 0 100-18 9 9 0 000 18z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3 9a9 9 0 100-18 9 9 0 000 18z" />
                `;
            }
        }
        </script>
 
</body>

</html>
