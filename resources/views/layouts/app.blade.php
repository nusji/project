<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'kaokang') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Assets and libraries -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('button.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

</head>

<body class="bg-[#ffffff]">
    <!-- Navbar -->
    <x-navbar :firstName="Auth::user()->first_name" :lastName="Auth::user()->last_name" />
    <!-- Sidebar -->
    <x-sidebar :userRole="auth()->user()->role" />
    <!-- Main Content -->
    <main class="ml-64 mt-16 p-6">
        @yield('content')
    </main>
    <!-- เรียกใช้ alert component -->
    <x-alert />
</body>

</html>
