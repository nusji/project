@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <x-breadcrumb :paths="[
        ['label' => 'ระบบจัดการพนักงาน', 'url' => route('employees.index')],
        ['label' => 'รายการพนักงาน']
    ]" />
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">ข้อมูลพนักงาน</h1>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- รูปภาพพนักงาน -->
                <div class="md:col-span-1">
                    @if($employee->profile_picture)
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">รูปพนักงาน</h2>
                        <a href="{{ asset('storage/' . $employee->profile_picture) }}" data-lightbox="profile_picture" data-title="รูปพนักงาน">
                            <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="รูปพนักงาน" class="w-full h-auto object-cover rounded-lg shadow-md hover:shadow-lg transition duration-300">
                        </a>
                    </div>
                    @endif
                </div>

                <!-- ข้อมูลส่วนตัว -->
                <div class="md:col-span-2 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">ชื่อ-สกุล</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ $employee->name }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">ชื่อผู้ใช้</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ $employee->username }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">บทบาท</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ ucfirst($employee->role) }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">เลขบัตรประจำตัวประชาชน</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ $employee->id_card_number }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">เบอร์โทรศัพท์</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ $employee->phone_number }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">ประเภทการจ้างงาน</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ $employee->employment_type }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">ฐานเงินเดือน</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ number_format($employee->salary, 2) }} บาท</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">วันเริ่มงาน</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ $employee->start_date ? \Carbon\Carbon::parse($employee->start_date)->format('d/m/Y') : 'ไม่ระบุ' }}</p>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-500">วันเกิด</h2>
                            <p class="text-lg font-semibold text-gray-800">{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d/m/Y') : 'ไม่ระบุ' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ข้อมูลเพิ่มเติม -->
            <div class="mt-8 space-y-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">ที่อยู่</h2>
                    <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $employee->address ?: 'ไม่ระบุ' }}</p>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">ข้อมูลบัญชีธนาคาร</h2>
                    <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">
                        {{ $employee->bank_account ? $employee->bank_account . ' - ' . $employee->bank_account_number : 'ไม่ระบุ' }}
                    </p>
                </div>
            </div>

            <!-- ปุ่มดำเนินการ -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('employees.edit', $employee->id) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50">
                    แก้ไขข้อมูล
                </a>
                <a href="{{ route('employees.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    กลับไปหน้ารายชื่อ
                </a>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <script>
        Swal.fire({
            title: 'สำเร็จ!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'ตกลง',
            customClass: {
                container: 'font-sans',
                popup: 'rounded-lg',
                confirmButton: 'bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50'
            },
            buttonsStyling: false
        });
    </script>
@endif
@endsection