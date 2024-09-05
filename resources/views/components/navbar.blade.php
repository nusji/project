<nav class="bg-[#1E293B] shadow-sm fixed w-full z-20 top-0 left-0">
    <div class="container mx-auto flex justify-between items-center p-2">
        <!-- Logo -->
        <div class="flex items-center">
            <img src="{{ asset('images/logo.svg') }}" alt="logo" class="h-10 mr-3">
            <a class="text-xl font-semibold text-white" href="#">
                Kaokang MIS
            </a>
        </div>
        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <!-- Display user name -->
            <span class="text-white">สวัสดีคุณ {{ $firstName }} {{ $lastName }}</span>

            <!-- Logout button -->
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

        <!-- Mobile Menu Button
        <button id="menu-button" class="md:hidden text-2xl focus:outline-none text-[#07a189]">
            <i class="fas fa-bars"></i>
        </button>
         -->
    </div>
</nav>
