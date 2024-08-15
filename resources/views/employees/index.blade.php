<!-- resources/views/employees/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container ml-auto px-4 py-8 relative z-10">

        <a href="{{ route('employees.create') }}"
            class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 mb-5 inline-block">เพิ่มข้อมูลพนักงานใหม่</a>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($employees as $employee)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h5 class="text-xl font-semibold mb-2">ชื่อสกุล : {{ $employee->first_name }} {{ $employee->last_name }}</h5>
                        <p class="text-gray-600 mb-2">ประเภทพนักงาน : {{ $employee->employment_status }}</p>

                        <p class="text-gray-600 mb-4">เบอร์โทร : {{ $employee->phone_number }}</p>
                        <a href="#"
                            class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 inline-block">ดูข้อมูล</a>
                        @if ($employee->employee_role !== 'owner')
                            <a href="#"
                                class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 inline-block ml-2">แก้ไข</a>
                            <form action="#" method="POST" class="inline-block ml-2">
                                @csrf
                                <button type="submit"
                                    class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">ลบพนักงาน</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
        </script>
    @endif
@endsection
