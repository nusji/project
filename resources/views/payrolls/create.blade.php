@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <x-breadcrumb :paths="[['label' => 'ระบบเงินเดือน', 'url' => route('payrolls.index')], ['label' => 'จ่ายเงินเดือน']]" />

        <h2 class="text-3xl font-semibold text-gray-800 mb-6">เพิ่มรายการจ่ายเงินเดือน</h2>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <form action="{{ route('payrolls.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">พนักงาน</label>
                        <select name="employee_id" id="employee_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                            required>
                            <option value="">เลือกพนักงาน</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}"
                                    data-bank_account="{{ $employee->bank_account }}"
                                    data-bank_account_number="{{ $employee->bank_account_number }}"
                                    data-employment_type="{{ $employee->employment_type }}"
                                    data-phone_number="{{ $employee->phone_number }}">
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">วันที่จ่าย</label>
                        <input type="date" name="payment_date" id="payment_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                            value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-md mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลพนักงาน</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ธนาคาร</label>
                            <p id="bank_account" class="px-3 py-2  rounded-md text-gray-800">
                                -</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">เลขบัญชีธนาคาร</label>
                            <p id="bank_account_number" class="px-3 py-2  rounded-md text-gray-800">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทการจ้าง</label>
                            <p id="employment_type" class="px-3 py-2  rounded-md text-gray-800">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                            <p id="phone_number" class="px-3 py-2  rounded-md text-gray-800">
                                -</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">ฐานเงินเดือน</label>
                        <input type="number" name="salary" id="salary"
                            class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                            step="0.01" min="0" value="0" readonly>
                    </div>
                    <div>
                        <label for="bonus" class="block text-sm font-medium text-gray-700 mb-2">โบนัส</label>
                        <input type="number" name="bonus" id="bonus"
                            class="w-full px-3 py-2 bg-green-50 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-200"
                            step="0.01" min="0" value="0">
                    </div>
                    <div>
                        <label for="deductions" class="block text-sm font-medium text-gray-700 mb-2">หักเงิน</label>
                        <input type="number" name="deductions" id="deductions"
                            class="w-full px-3 py-2 bg-red-50 border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200"
                            step="0.01" min="0" value="0">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="net_salary" class="block text-sm font-medium text-gray-700 mb-2">เงินเดือนสุทธิ</label>
                    <input type="number" name="net_salary" id="net_salary"
                        class="w-full px-3 py-2 bg-blue-50 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 font-semibold text-lg"
                        step="0.01" min="0" value="0" readonly>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="slip" class="block text-sm font-medium text-gray-700 mb-2">แนบสลิปโอนเงิน</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative">
                            <div class="space-y-1 text-center">
                                <svg id="upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                    fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div id="upload-text" class="flex text-sm text-gray-600">
                                    <label for="file-upload"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>อัปโหลดไฟล์</span>
                                        <input id="file-upload" name="slip" type="file" class="sr-only"
                                            accept="image/*,application/pdf">
                                    </label>
                                    <p class="pl-1">หรือลากและวางที่นี่</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF หรือ PDF สูงสุด 10MB</p>
                            </div>
                            <div id="image-preview" class="absolute inset-0 flex items-center justify-center hidden">
                                <img src="" alt="Preview" class="max-h-full max-w-full object-contain">
                            </div>
                        </div>
                        <div id="file-name" class="mt-2 text-sm text-gray-600"></div>
                    </div>
                    <div>
                        <label for="payment_channel"
                            class="block text-sm font-medium text-gray-700 mb-2">ช่องทางการจ่ายเงินเดือน</label>
                        <select name="payment_channel" id="payment_channel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                            required>
                            <option value="bank_transfer">โอนผ่านธนาคาร</option>
                            <option value="cash">เงินสด</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8">
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-300">
                        บันทึกรายการจ่ายเงินเดือน
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeSelect = document.getElementById('employee_id');
            const salaryInput = document.getElementById('salary');
            const bonusInput = document.getElementById('bonus');
            const deductionsInput = document.getElementById('deductions');
            const netSalaryInput = document.getElementById('net_salary');
            const bankAccountDisplay = document.getElementById('bank_account');
            const bankAccountNumberDisplay = document.getElementById('bank_account_number');
            const employmentTypeDisplay = document.getElementById('employment_type');
            const phoneNumberDisplay = document.getElementById('phone_number');

            function calculateNetSalary() {
                const salary = parseFloat(salaryInput.value) || 0;
                const bonus = parseFloat(bonusInput.value) || 0;
                const deductions = parseFloat(deductionsInput.value) || 0;
                const netSalary = salary + bonus - deductions;
                netSalaryInput.value = netSalary.toFixed(2);
            }

            function updateEmployeeInfo() {
                const selectedEmployee = employeeSelect.options[employeeSelect.selectedIndex];
                if (selectedEmployee.value) {
                    salaryInput.value = selectedEmployee.getAttribute('data-salary') || '0';
                    bankAccountDisplay.innerText = selectedEmployee.getAttribute('data-bank_account') || '-';
                    bankAccountNumberDisplay.innerText = selectedEmployee.getAttribute(
                        'data-bank_account_number') || '-';
                    employmentTypeDisplay.innerText = selectedEmployee.getAttribute('data-employment_type') || '-';
                    phoneNumberDisplay.innerText = selectedEmployee.getAttribute('data-phone_number') || '-';
                } else {
                    // Reset fields when no employee is selected
                    salaryInput.value = '0';
                    bankAccountDisplay.innerText = '-';
                    bankAccountNumberDisplay.innerText = '-';
                    employmentTypeDisplay.innerText = '-';
                    phoneNumberDisplay.innerText = '-';
                }
                calculateNetSalary();
            }

            employeeSelect.addEventListener('change', updateEmployeeInfo);
            bonusInput.addEventListener('input', calculateNetSalary);
            deductionsInput.addEventListener('input', calculateNetSalary);

            // Initialize with default selected employee
            updateEmployeeInfo();

            // เพิ่มโค้ดสำหรับการจัดการอัปโหลดไฟล์
            const fileUpload = document.getElementById('file-upload');
            const fileName = document.getElementById('file-name');
            const dropZone = document.querySelector('.border-dashed');
            const imagePreview = document.getElementById('image-preview');
            const uploadIcon = document.getElementById('upload-icon');
            const uploadText = document.getElementById('upload-text');

            fileUpload.addEventListener('change', handleFileSelect);

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-indigo-500');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-indigo-500');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-indigo-500');
                fileUpload.files = e.dataTransfer.files;
                handleFileSelect();
            });

            function handleFileSelect() {
                if (fileUpload.files.length > 0) {
                    const file = fileUpload.files[0];
                    fileName.textContent = `ไฟล์ที่เลือก: ${file.name}`;

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.querySelector('img').src = e.target.result;
                            imagePreview.classList.remove('hidden');
                            uploadIcon.classList.add('hidden');
                            uploadText.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.classList.add('hidden');
                        uploadIcon.classList.remove('hidden');
                        uploadText.classList.remove('hidden');
                    }
                } else {
                    fileName.textContent = '';
                    imagePreview.classList.add('hidden');
                    uploadIcon.classList.remove('hidden');
                    uploadText.classList.remove('hidden');
                }
            }
        });
    </script>
@endsection
