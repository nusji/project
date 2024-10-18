@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4">
            <h1 class="text-3xl font-bold text-gray-800">รีเซ็ตรหัสผ่าน</h1>
        </div>
        <div class="p-6">
            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('profile.reset_custom.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="id_card_number" class="block text-gray-700">เลขบัตรประชาชน:</label>
                    <input type="text" id="id_card_number" name="id_card_number" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mb-4">
                    <label for="date_of_birth" class="block text-gray-700">วันเดือนปีเกิด:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700">รหัสผ่านใหม่:</label>
                    <input type="password" id="new_password" name="new_password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="block text-gray-700">ยืนยันรหัสผ่านใหม่:</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full p-2 border border-gray-300 rounded" required>
                </div>

                <div class="mt-8">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition duration-300 ease-in-out transform hover:-translate-y-1">ยืนยันการรีเซ็ตรหัสผ่าน</button>
                    <a href="{{ route('login') }}" class="ml-4 text-blue-500">กลับสู่หน้าล็อกอิน</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
