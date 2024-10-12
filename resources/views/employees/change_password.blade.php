@extends('layouts.app')
@section('content')
<div class="container mx-auto mt-10 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4">
            <h1 class="text-3xl font-bold text-gray-800">เปลี่ยนรหัสผ่าน</h1>
        </div>
        <div class="p-6">
            <form action="{{ route('profile.update_password', $employee->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700">รหัสผ่านปัจจุบัน:</label>
                    <input type="password" id="current_password" name="current_password" class="w-full p-2 border border-gray-300 rounded" required>
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
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition duration-300 ease-in-out transform hover:-translate-y-1">ยืนยันการเปลี่ยนรหัสผ่าน</button>
                    <a href="{{ route('profile.profile', $employee->id) }}" class="ml-4 text-blue-500">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
