@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-800">ข้อมูลพนักงาน</h1>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">ชื่อ-นามสกุล</h2>
                            <p class="text-gray-600">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">ชื่อผู้ใช้</h2>
                            <p class="text-gray-600">{{ $employee->username }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">บทบาท</h2>
                            <p class="text-gray-600">{{ ucfirst($employee->role) }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">เลขบัตรประจำตัวประชาชน</h2>
                            <p class="text-gray-600">{{ $employee->id_card_number }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">เบอร์โทรศัพท์</h2>
                            <p class="text-gray-600">{{ $employee->phone_number }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">ประเภทการจ้างงาน</h2>
                            <p class="text-gray-600">{{ $employee->employment_status }}</p>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">ฐานเงินเดือน</h2>
                            <p class="text-gray-600">{{ $employee->salary }}</p>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">วันเริ่มงาน</h2>
                            <p class="text-gray-600">{{ $employee->start_date ? \Carbon\Carbon::parse($employee->start_date)->format('d/m/Y') : 'ไม่ระบุ' }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-700">วันเกิด</h2>
                            <p class="text-gray-600">{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d/m/Y') : 'ไม่ระบุ' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">ที่อยู่</h2>
                        <p class="text-gray-600">{{ $employee->address ?: 'ไม่ระบุ' }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">ประวัติการทำงานก่อนหน้า</h2>
                        <p class="text-gray-600">{{ $employee->previous_experience ?: 'ไม่ระบุ' }}</p>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">ข้อมูลบัญชีธนาคาร</h2>
                        <p class="text-gray-600">
                            {{ $employee->bank_account ? $employee->bank_account . ' - ' . $employee->bank_account_number : 'ไม่ระบุ' }}
                        </p>
                    </div>
                </div>

                @if($employee->profile_picture)
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">รูปพนักงาน</h2>
                    <a href="{{ asset('storage/' . $employee->profile_picture) }}" data-lightbox="profile_picture" data-title="ใบเสร็จ">
                        <img src="{{ asset('storage/' . $employee->profile_picture) }}" alt="รูปใบเสร็จ" class="w-32 h-32 object-cover rounded-full cursor-pointer">
                    </a>
                </div>
                @endif
                

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('employees.edit', $employee->id) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        แก้ไขข้อมูล
                    </a>
                    <a href="{{ route('employees.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
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
                    confirmButton: 'bg-green-500 hover:bg-green-600'
                },
                buttonsStyling: false
            });
        </script>
    @endif
@endsection