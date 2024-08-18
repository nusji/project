@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">แก้ไขข้อมูลพนักงาน</h1>
        </div>

        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">ชื่อจริง</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">นามสกุล</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $employee->username) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">บทบาท</label>
                    <select name="role" id="role" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="employee" {{ $employee->role == 'employee' ? 'selected' : '' }}>พนักงาน</option>
                        <option value="owner" {{ $employee->role == 'owner' ? 'selected' : '' }}>ผู้ดูแลระบบ (เจ้าของร้าน)</option>
                    </select>
                </div>

                <div>
                    <label for="id_card_number" class="block text-sm font-medium text-gray-700">เลขบัตรประจำตัวประชาชน</label>
                    <input type="text" name="id_card_number" id="id_card_number" value="{{ old('id_card_number', $employee->id_card_number) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $employee->phone_number) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="employment_status" class="block text-sm font-medium text-gray-700">สถานะการจ้างงาน</label>
                    <select name="employment_status" id="employment_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="พนักงานประจำ" {{ $employee->employment_status == 'พนักงานประจำ' ? 'selected' : '' }}>พนักงานประจำ (Full-time)</option>
                        <option value="พนักงานชั่วคราว" {{ $employee->employment_status == 'พนักงานชั่วคราว' ? 'selected' : '' }}>พนักงานชั่วคราว (Part-time)</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">วันเริ่มงาน</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $employee->start_date) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">วันเดือนปีเกิด</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div class="mt-6">
                <label for="address" class="block text-sm font-medium text-gray-700">ที่อยู่</label>
                <textarea name="address" id="address" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('address', $employee->address) }}</textarea>
            </div>

            <div class="mt-6">
                <label for="previous_experience" class="block text-sm font-medium text-gray-700">ประวัติการทำงานก่อนหน้า</label>
                <textarea name="previous_experience" id="previous_experience" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('previous_experience', $employee->previous_experience) }}</textarea>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bank_account" class="block text-sm font-medium text-gray-700">ชื่อธนาคาร</label>
                    <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $employee->bank_account) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="bank_account_number" class="block text-sm font-medium text-gray-700">เลขที่บัญชีธนาคาร</label>
                    <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>
            </div>

            <div class="mt-6">
                <label for="profile_picture" class="block text-sm font-medium text-gray-700">รูปโปรไฟล์</label>
                <input type="file" name="profile_picture" id="profile_picture" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @if($employee->profile_picture)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="รูปโปรไฟล์ปัจจุบัน" class="w-32 h-32 object-cover rounded-full">
                        <p class="mt-1 text-sm text-gray-500">รูปโปรไฟล์ปัจจุบัน</p>
                    </div>
                @endif
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    บันทึกการเปลี่ยนแปลง
                </button>
                <a href="{{ route('employees.show', $employee->id) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ยกเลิก
                </a>
            </div>
        </form>
    </div>
</div>
@endsection