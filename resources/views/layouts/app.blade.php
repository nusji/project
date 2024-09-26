<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KAOKANG MIS')</title>
    <link rel="icon" href="{{ asset('kaokang.ico') }}" type="image/x-icon">

    <!-- Assets and libraries -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!--sweetalert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>



    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    @livewireStyles

</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <x-navbar :name="Auth::user()->name" />

    <!-- Sidebar -->
    <x-sidebar :userRole="auth()->user()->role" />
        
    <!-- Main Content -->
    <main class="ml-64 mt-16 p-6 ">
        @if (session('errors'))
            <div class="text-red-500 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

        @livewireScripts

    </main>
    <!-- เรียกใช้ alert component -->
    <x-alert />

</body>

</html>
