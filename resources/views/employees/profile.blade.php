@extends('layouts.app')
@section('content')
<div class="container mx-auto mt-5 px-4 sm:px-6 lg:px-8">

        <h1 class="text-3xl font-bold text-gray-800 mb-5">ข้อมูลโปรไฟล์</h1>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">

        <div class="p-6">
            <!-- แสดงรูปโปรไฟล์ -->
            <div class="mb-8 text-center">
                @if($employee->profile_picture)
                    <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="Profile Picture" class="w-40 h-40 rounded-full mx-auto border-4 border-[#E2725B] shadow-lg">
                @else
                    <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Picture" class="w-40 h-40 rounded-full mx-auto border-4 border-[#E2725B] shadow-lg">
                @endif
                <h2 class="mt-4 text-2xl font-semibold text-gray-800">{{ $employee->name }}</h2>
                <p class="text-[#E2725B]">{{ $employee->position }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">ชื่อผู้ใช้:</span> {{ $employee->username }}</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">ประเภทการจ้างงาน:</span> {{ $employee->employment_type }}</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">วันที่เริ่มงาน:</span> {{ $employee->start_date ? \Carbon\Carbon::parse($employee->start_date)->format('d M Y') : '-' }}</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">เงินเดือน:</span> {{ number_format($employee->salary, 2) }} บาท</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">เบอร์โทรศัพท์:</span> {{ $employee->phone_number }}</p>
                </div>
                <div class="space-y-3">
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">ที่อยู่:</span> {{ $employee->address ?? '-' }}</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">วันเกิด:</span> {{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : '-' }}</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">เลขบัตรประชาชน:</span> {{ $employee->id_card_number }}</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">บัญชีธนาคาร:</span> {{ $employee->bank_account ?? '-' }}</p>
                    <p class="flex items-center"><span class="font-semibold w-40 text-[#E2725B]">เลขที่บัญชีธนาคาร:</span> {{ $employee->bank_account_number ?? '-' }}</p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-600"><span class="font-semibold text-[#E2725B]">วันที่เข้าร่วม:</span> {{ $employee->created_at->format('d M Y') }}</p>
            </div>

            <div class="mt-8 flex flex-wrap gap-4">
                @if(Auth::id() === $employee->id)
                    <a href="{{ route('profile.profile_edit', $employee->id) }}" class="bg-[#E2725B] hover:bg-[#D26450] text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out hover:shadow-md flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        แก้ไขข้อมูล
                    </a>
                    <a href="{{ route('profile.change_password', $employee->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out hover:shadow-md flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        เปลี่ยนรหัสผ่าน
                    </a>
                    <a href="{{ route('profile.reset_custom', $employee->id) }}" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out hover:shadow-md flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        ลืมรหัสผ่าน
                    </a>
                @endif
                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition duration-300 ease-in-out hover:shadow-md flex items-center">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    กลับสู่หน้าแดชบอร์ด
                </a>
            </div>
        </div>
    </div>
</div>
@endsection