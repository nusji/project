<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class SalaryController extends Controller
{
    public function index()
    {
        $employees = Employee::select('id', 'first_name', 'last_name', 'salary', 'employment_status')->paginate(10);
        return view('payrolls.salaries.index', compact('employees'));
    }

    public function show(Employee $employee)
    {
        return view('payrolls.salaries.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        // ส่งข้อมูลพนักงานไปที่ view เพื่อแก้ไขเงินเดือน
        return view('payrolls.salaries.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        // ตรวจสอบความถูกต้องของข้อมูล
        $validatedData = $request->validate([
            'salary' => 'required|numeric|min:0',
        ]);

        // อัปเดตเฉพาะข้อมูลเงินเดือนในตาราง employees
        $employee->update(['salary' => $validatedData['salary']]);

        // redirect กลับไปที่หน้ารายการพนักงานพร้อมข้อความสำเร็จ
        return redirect()->route('salaries.index')->with('success', 'Employee salary updated successfully.');
    }
}
