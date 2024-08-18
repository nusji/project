@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">รายชื่อพนักงาน</h1>
            <a href="{{ route('employees.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 transform hover:scale-105 shadow-md">
                เพิ่มข้อมูลพนักงานใหม่
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($employees as $employee)
                <div
                    class="bg-white shadow-lg rounded-lg overflow-hidden transition duration-300 hover:shadow-xl transform hover:-translate-y-1">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-3">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </h2>
                        <p class="text-gray-600 mb-2 flex items-center">
                            <span class="font-medium mr-2">ประเภท:</span>
                            <span
                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">{{ $employee->employment_status }}</span>
                        </p>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <span class="font-medium mr-2">เบอร์โทร:</span>
                            <span class="text-sm">{{ $employee->phone_number }}</span>
                        </p>
                        <div class="flex space-x-2">
                            <a href="{{ route('employees.show', $employee->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">ดูข้อมูล</a>

                            @if ($employee->role !== 'owner')
                                <a href="{{ route('employees.edit', $employee->id) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">แก้ไข</a>

                                <button type="button"
                                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300"
                                    onclick="confirmDelete()">
                                    ลบพนักงาน
                                </button>

                                <form id="delete-form" action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
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

    <script>
        function confirmDelete() {
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
                preConfirm: (input) => {
                    if (input !== 'delete') {
                        Swal.showValidationMessage('โปรดพิมพ์ "delete" เพื่อยืนยันการลบ')
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            })
        }
    </script>
@endsection
