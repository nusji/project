<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KAOKANG POS')</title>
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

    <aside
        class="h-full w-40 fixed top-0 left-0 bg-slate-700 shadow-lg z-10 transition-transform duration-500 pt-0 md:pt-0">
        <div class="flex flex-col p-4 space-y-2">
            <span class="text-white">คุณ {{ Auth::user()->name }}</span>
            <hr>
            <h2 class="text-center font-italic text-white">จัดการขาย</h2>
            <a href="{{ Auth::user()->role === 'owner' ? route('dashboard.owner') : route('dashboard.employee') }}"
                class="border-1 py-2.5 px-4 text-lg font-medium text-[#F1F5F9] text-center border-2 bg-orange-500 rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center {{ request()->routeIs('dashboard.owner') || request()->routeIs('dashboard.employee') ? 'bg-[#E2725B] text-white' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                ออก POS
            </a>
            @if (Route::is('sales.create'))
                <a href="{{ route('sales.index') }}"
                    class="border-1 py-2.5 px-4 text-sm font-regular text-[#F1F5F9] text-center border-2 rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    ย้อนกลับ
                </a>
            @elseif (Route::is('sales.manageSoldOut'))
                <a href="{{ route('sales.create') }}"
                    class="border-1 py-2.5 px-4 text-sm font-regular text-[#F1F5F9] text-center border-2 rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    ย้อนกลับ
                </a>
            @endif
            <a href="#" onclick="event.preventDefault(); showManageSoldOutModal();"
                class="border-1 py-2.5 px-4 text-sm font-regular text-[#F1F5F9] text-center border-2 rounded-lg transition duration-300 ease-in-out hover:bg-[#E2725B] hover:shadow-md flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
                </svg>
                จัดการ<br>เมนูเหลือ
            </a>

            <!-- Include modal component -->
            @include('components.manage-sold-out-modal')
            <hr>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="bg-[#E2725B] text-white hover:bg-[#c55a45] border border-[#E2725B] hover:border-[#c55a45] py-2 px-3 rounded-lg flex items-center space-x-2 transition duration-300 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                    <span>ออกจากระบบ</span>
                </button>
            </form>
        </div>
    </aside>
    <!-- Main Content -->
    <main class="ml-40">
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

    <script>
        function showManageSoldOutModal() {
            document.getElementById('manageSoldOutModal').classList.remove('hidden');
        }

        document.getElementById('cancelSoldOut').addEventListener('click', function() {
            document.getElementById('manageSoldOutModal').classList.add('hidden');
        });

        document.getElementById('saveSoldOut').addEventListener('click', function () {
        const form = document.getElementById('manageSoldOutForm');
        const formData = new FormData(form);
        
        axios.post('/sales/update-sold-out-status', formData)
            .then(response => {
                console.log(response.data);
                // ซ่อน modal หลังบันทึก
                document.getElementById('manageSoldOutModal').classList.add('hidden');
            })
            .catch(error => {
                console.error(error);
            });
    });
    </script>
</body>

</html>
