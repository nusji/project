@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-2xl font-bold mb-4">แก้ไขข้อมูลโปรไฟล์</h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.profile_update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- ชื่อ -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">ชื่อจริง:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $employee->name) }}"
                        class="w-full p-2 border border-gray-300 rounded">
                </div>

                <!-- เบอร์โทรศัพท์ -->
                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700">เบอร์โทรศัพท์:</label>
                    <input type="text" id="phone_number" name="phone_number"
                        value="{{ old('phone_number', $employee->phone_number) }}"
                        class="w-full p-2 border border-gray-300 rounded">
                </div>

                <!-- ที่อยู่ -->
                <div class="mb-4">
                    <label for="address" class="block text-gray-700">ที่อยู่:</label>
                    <textarea id="address" name="address" rows="3" class="w-full p-2 border border-gray-300 rounded">{{ old('address', $employee->address) }}</textarea>
                </div>

                <!-- วันเกิด -->
                <div class="mb-4">
                    <label for="date_of_birth" class="block text-gray-700">วันเกิด:</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        value="{{ old('date_of_birth', $employee->date_of_birth ? (new \Carbon\Carbon($employee->date_of_birth))->format('Y-m-d') : null) }}"
                        class="w-full p-2 border border-gray-300 rounded">
                </div>


                <!-- รูปโปรไฟล์ -->
                <div class="mb-4">
                    <label for="profile_picture" class="block text-gray-700">รูปโปรไฟล์:</label>
                    @if ($employee->profile_picture)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="Profile Picture"
                                class="w-32 h-32 rounded-full">
                        </div>
                    @endif
                    <input type="file" id="profile_picture" name="profile_picture"
                        class="w-full p-2 border border-gray-300 rounded">
                </div>

                <!-- ปุ่มบันทึก -->
                <div class="mt-6">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">บันทึกการเปลี่ยนแปลง</button>
                    <a href="{{ route('profile.profile') }}" class="ml-4 text-blue-500">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
@endsection
