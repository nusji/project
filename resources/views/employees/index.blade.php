@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-0">
        <x-breadcrumb :paths="[['label' => 'ระบบจัดการพนักงาน', 'url' => route('employees.index')], ['label' => '']]" />
        <h2 class="text-2xl font-bold text-gray-800 mb-4">ระบบจัดการพนักงาน</h2>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('employees.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    เพิ่มข้อมูลพนักงานใหม่
                </a>
            </div>
            <!-- เรียกใช้ search form component -->
            <x-search-form :search="$search" />
        </div>
        <!-- ส่วนของตาราง-->
        <div class="p-6">
            <h1 class="text-xl font-bold text-gray-800 mb-4">รายการข้อมูลพนักงาน</h1>
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ชื่อสกุล
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ประเภทการจ้างงาน
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                เบอร์โทร
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                จัดการ
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if ($employees->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    ไม่มีข้อมูลพนักงานในระบบ
                                </td>
                            </tr>
                        @else
                            @foreach ($employees as $employee)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $employee->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-sm leading-8 font-regular rounded-full bg-blue-100 text-blue-800">
                                            {{ $employee->employment_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $employee->phone_number }}
                                    </td>

                                    <!-- ส่วนของการจัดการ -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('employees.show', $employee->id) }}"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            ดูข้อมูล
                                        </a>

                                        @if (auth()->user()->role === 'owner')
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                แก้ไขข้อมูลพนักงาน
                                            </a>
                                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline" id="delete-form-{{ $employee->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $employee->id }})"
                                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    ลบข้อมูลพนักงาน
                                                </button>
                                            </form>
                                            <script>
                                                function confirmDelete(employeeId) {
                                                    Swal.fire({
                                                        title: 'คุณแน่ใจหรือไม่?',
                                                        text: "กรุณาพิมพ์คำว่า 'ลบพนักงาน' เพื่อยืนยันการลบข้อมูล!",
                                                        icon: 'warning',
                                                        input: 'text',
                                                        inputPlaceholder: 'พิมพ์ที่นี่...',
                                                        showCancelButton: true,
                                                        confirmButtonText: 'ใช่, ลบเลย!',
                                                        cancelButtonText: 'ยกเลิก',
                                                        customClass: {
                                                            confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded',
                                                            cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded'
                                                        },
                                                        preConfirm: (inputValue) => {
                                                            if (inputValue !== 'ลบพนักงาน') {
                                                                Swal.showValidationMessage('คำที่พิมพ์ไม่ถูกต้อง! กรุณาพิมพ์ "ลบพนักงาน".');
                                                            }
                                                            return inputValue;
                                                        }
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById(`delete-form-${employeeId}`).submit();
                                                        }
                                                    });
                                                }
                                            </script>                                            
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-4 space-x-2">
                {{ $employees->links() }}
            </div>   
        </div>
    @endsection
