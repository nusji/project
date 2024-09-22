@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-0">
    <!-- เรียกใช้ breadcrumb component -->
    <x-breadcrumb :paths="[['label' => 'ระบบจัดการผลิต', 'url' => route('productions.index')], ['label' => 'แก้ไขรายการจ่ายเงินเดือน']]" />
    <h2 class="text-2xl font-bold text-gray-800 mb-4">แก้ไขรายการจ่ายเงินเดือน</h2>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form action="{{ route('payrolls.update', $payroll->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT') <!-- ใช้เพื่อระบุว่าเป็นการอัปเดตข้อมูล -->

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">พนักงาน</label>
                    <select name="employee_id" id="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">เลือกพนักงาน</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}" {{ $payroll->employee_id == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">วันที่จ่าย</label>
                    <input type="date" name="payment_date" id="payment_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $payroll->payment_date->format('Y-m-d') }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bonus" class="block text-sm font-medium text-gray-700 mb-2">โบนัส</label>
                    <input type="number" name="bonus" id="bonus" class="w-full px-3 py-2 bg-green-100 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01" min="0" value="{{ $payroll->bonus }}">
                </div>
                <div>
                    <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">ฐานเงินเดือน</label>
                    <input type="number" name="salary" id="salary" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01" min="0" value="{{ $payroll->salary }}" readonly>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="deductions" class="block text-sm font-medium text-gray-700 mb-2">หักเงิน</label>
                    <input type="number" name="deductions" id="deductions" class="w-full px-3 py-2 bg-red-100 border border-red-300  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01" min="0" value="{{ $payroll->deductions }}">
                </div>
                <div>
                    <label for="net_salary" class="block text-sm font-medium text-gray-700 mb-2">เงินเดือนสุทธิ</label>
                    <input type="number" name="net_salary" id="net_salary" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01" min="0" value="{{ $payroll->net_salary }}" readonly>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-300">
                    บันทึก
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const employeeSelect = document.getElementById('employee_id');
        const salaryInput = document.getElementById('salary');
        const bonusInput = document.getElementById('bonus');
        const deductionsInput = document.getElementById('deductions');
        const netSalaryInput = document.getElementById('net_salary');

        function calculateNetSalary() {
            const salary = parseFloat(salaryInput.value) || 0;
            const bonus = parseFloat(bonusInput.value) || 0;
            const deductions = parseFloat(deductionsInput.value) || 0;
            const netSalary = salary + bonus - deductions;
            netSalaryInput.value = netSalary.toFixed(2);
        }

        employeeSelect.addEventListener('change', function () {
            const selectedEmployee = employeeSelect.options[employeeSelect.selectedIndex];
            const salary = selectedEmployee.getAttribute('data-salary');
            salaryInput.value = salary || 0;
            calculateNetSalary();
        });

        bonusInput.addEventListener('input', calculateNetSalary);
        deductionsInput.addEventListener('input', calculateNetSalary);
    });
</script>
@endsection
