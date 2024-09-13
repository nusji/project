@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <!-- เรียกใช้ breadcrumb component -->
        <x-breadcrumb :paths="[
            ['label' => 'ระบบพนักงาน', 'url' => route('employees.index')],
            ['label' => 'เพิ่มข้อมูลพนักงานใหม่']
        ]" />
            <h1 class="text-2xl font-bold mb-6">เพิ่มข้อมูลพนักงานใหม่</h1>

            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="bg-white border border-gray-300 shadow-lg rounded-lg p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">ชื่อ</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                            @error('first_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="last_name" class="block text-sm font-medium text-gray-700">นามสกุล</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                            @error('last_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="id_card_number"
                                class="block text-sm font-medium text-gray-700">เลขบัตรประชาชน</label>
                            <input type="text" id="id_card_number" name="id_card_number" maxlength="13"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                value="{{ old('id_card_number') }}" required>
                            @error('id_card_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">เบอร์โทร</label>
                            <input type="text" id="phone_number" name="phone_number" maxlength="10"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username" class="block text-sm font-medium text-gray-700">ชื่อเข้าใช้ /
                                username</label>
                            <input type="text" id="username" name="username"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-yellow-50 border-yellow-300"
                                value="{{ old('username') }}" required>
                            @error('username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่านเข้าใช้ /
                                password</label>
                            <input type="password" id="password" name="password" maxlength="20"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-yellow-50 border-yellow-300"
                                required>
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="employment_status"
                                class="block text-sm font-medium text-gray-700">ประเภทการจ้าง</label>
                            <select id="employment_status" name="employment_status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                required>
                                <option value="พนักงานประจำ"
                                    {{ old('employment_status') == 'permanent' ? 'selected' : '' }}>
                                    แผนกครัว (พนักงานประจำ) </option>
                                <option value="พนักงานประจำ"
                                    {{ old('employment_status') == 'permanent' ? 'selected' : '' }}>
                                    แผนกขาย (พนักงานประจำ)</option>
                                <option value="พนักงานชั่วคราว"
                                    {{ old('employment_status') == 'temporary' ? 'selected' : '' }}>
                                    พนักงานชั่วคราว</option>
                            </select>
                            @error('employment_status')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="salary" class="block text-sm font-medium text-gray-700">ฐานเงินเดือน</label>
                            <input type="number" id="salary" name="salary"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                value="{{ old('salary') }}" required min="0">
                            @error('salary')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        

                        <div class="form-group">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">วันที่เริ่มทำงาน</label>
                            <input type="date" id="start_date" name="start_date"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                value="{{ old('start_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="mt-6">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">เพิ่มข้อมูลพนักงานใหม่</button>
                    </div>
                </div>
            </form>
        </div>
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'ตกลง'
                });
            </script>
        @endif
    @endsection
