@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <x-breadcrumb :paths="[
            ['label' => 'ระบบพนักงาน', 'url' => route('employees.index')],
            ['label' => 'เพิ่มข้อมูลพนักงานใหม่'],
        ]" />

        <h1 class="text-3xl font-semibold text-gray-800 mb-6">แก้ไขข้อมูลพนักงาน</h1>
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-medium text-gray-700">ชื่อ-สกุลจริง</label>
                            <input type="text" id="name" name="name"
                                value="{{ old('first_name', $employee->name) }} " 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="id_card_number" class="text-sm font-medium text-gray-700">เลขบัตรประชาชน</label>
                            <input type="text" id="id_card_number" name="id_card_number" maxlength="13"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('id_card_number', $employee->id_card_number) }}" readonly required>
                            @error('id_card_number')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="phone_number" class="text-sm font-medium text-gray-700">เบอร์โทร</label>
                            <input type="text" id="phone_number" name="phone_number" maxlength="10"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('phone_number', $employee->phone_number) }}" required>
                            @error('phone_number')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="employment_type" class="text-sm font-medium text-gray-700">ประเภทการจ้าง</label>
                            <select id="employment_type" name="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="พนักงานประจำ(แผนกครัว)"
                                    {{ $employee->employment_type == 'พนักงานประจำ (แผนกครัว)' ? 'selected' : '' }}>
                                    แผนกครัว (พนักงานประจำ)
                                </option>
                                <option value="พนักงานประจำ(แผนกขาย)"
                                    {{ $employee->employment_type == 'พนักงานประจำ (แผนกขาย)' ? 'selected' : '' }}>
                                    แผนกขาย (พนักงานประจำ)
                                </option>
                                <option value="พนักงานชั่วคราว"
                                    {{ $employee->employment_type == 'พนักงานชั่วคราว' ? 'selected' : '' }}>
                                    พนักงานชั่วคราว
                                </option>

                            </select>
                            @error('employment_type')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="username" class="text-sm font-medium text-gray-700">ชื่อเข้าใช้ / username</label>
                            <input type="text" id="username" name="username"
                                class="w-full px-3 py-2 border border-yellow-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-yellow-50"
                                value="{{ old('username', $employee->username) }}" required>
                            @error('username')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="role" class="text-sm font-medium text-gray-700">บทบาท/สิทธิ์</label>
                            <select id="employment_type" name="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-yellow-50"
                                required>
                                <option value="employee" {{ $employee->role == 'employee' ? 'selected' : '' }}>
                                    พนักงาน
                                </option>
                                <option value="owner" {{ $employee->role == 'owner' ? 'selected' : '' }}>
                                    เจ้าของ
                                </option>
                                <option value="none" {{ $employee->role == 'none' ? 'selected' : '' }}>
                                    ไม่มีบทบาท (ลาออก)
                                </option>
                                @error('password')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="start_date" class="text-sm font-medium text-gray-700">วันที่เริ่มทำงาน</label>
                            <input type="date" id="start_date" name="start_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('start_date', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="salary" class="text-sm font-medium text-gray-700">ฐานเงินเดือน</label>
                            <input type="number" id="salary" name="salary"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('salary', $employee->salary) }}" required min="0">
                            @error('salary')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="address" class="text-sm font-medium text-gray-700">ที่อยู่ปัจจุบัน</label>
                            <input type="text" id="address" name="address"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('address', $employee->address) }}" required min="0">
                            @error('address')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="salary" class="text-sm font-medium text-gray-700">ฐานเงินเดือน</label>
                            <input type="number" id="salary" name="salary"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('salary', $employee->salary) }}" required min="0">
                            @error('salary')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="salary" class="text-sm font-medium text-gray-700">ฐานเงินเดือน</label>
                            <input type="number" id="salary" name="salary"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('salary', $employee->salary) }}" required min="0">
                            @error('salary')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-300">
                        เพิ่มข้อมูลพนักงานใหม่
                    </button>
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
