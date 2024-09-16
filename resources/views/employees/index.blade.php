@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[['label' => 'ระบบจัดการพนักงาน', 'url' => route('employees.index')], ['label' => '']]" />

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">ระบบจัดการพนักงาน</h2>
            <a href="{{ route('employees.create') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                เพิ่มข้อมูลพนักงานใหม่
            </a>
        </div>

        <div class="mt-8 mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">กะการทำงานวันนี้</h3>
                <p class="text-gray-700">08:00 - 16:00</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">ยอดขายของคุณ</h3>
                <p class="text-2xl font-bold text-green-600">฿5,280</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">คะแนนความพึงพอใจ</h3>
                <p class="text-2xl font-bold text-blue-600">4.8/5</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($employees as $employee)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ $employee->name }}</h3>
                        <div class="mb-4 space-y-2">
                            <p class="text-gray-600 flex items-center">
                                <span class="font-medium mr-2">ประเภท:</span>
                                <span
                                    class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">{{ $employee->employment_type }}</span>
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <span class="font-medium mr-2">เบอร์โทร:</span>
                                <span class="text-sm">{{ $employee->phone_number }}</span>
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('employees.show', $employee->id) }}"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center font-medium py-2 px-4 rounded-md transition duration-300">
                                ดูข้อมูล
                            </a>
                            @if ($employee->role !== 'owner')
                                <a href="{{ route('employees.edit', $employee->id) }}"
                                    class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center font-medium py-2 px-4 rounded-md transition duration-300">
                                    แก้ไข
                                </a>
                                <button type="button" onclick="confirmDelete('{{ $employee->id }}')"
                                    class="flex-1 bg-red-500 hover:bg-red-600 text-white text-center font-medium py-2 px-4 rounded-md transition duration-300">
                                    ลบ
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
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
                        confirmButton: 'bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded'
                    },
                    buttonsStyling: false
                });
            </script>
        @endif

        <script>
            function confirmDelete(employeeId) {
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณกำลังจะลบข้อมูลพนักงานคนนี้ การกระทำนี้ไม่สามารถย้อนกลับได้!",
                    icon: 'warning',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    inputPlaceholder: 'พิมพ์ "delete" เพื่อยืนยัน',
                    showCancelButton: true,
                    confirmButtonText: 'ลบ',
                    cancelButtonText: 'ยกเลิก',
                    showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mr-2',
                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded'
                    },
                    buttonsStyling: false,
                    preConfirm: (input) => {
                        if (input !== 'delete') {
                            Swal.showValidationMessage('โปรดพิมพ์ "delete" เพื่อยืนยันการลบ')
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/employees/${employeeId}`;
                        form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                })
            }
        </script>
    </div>
@endsection
