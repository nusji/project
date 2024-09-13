<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'id_card_number' => 'required|string|max:13|unique:employees',
                'phone_number' => 'required|string|max:10',
                'username' => 'required|string|unique:employees',
                'password' => 'required|string|min:8',
                'employment_status' => 'required|string',
                'start_date' => 'required|date',
                'salary' => 'required|numeric',
            ],
            [
                'first_name.required' => 'จำเป็นต้องกรอกชื่อ',
                'last_name.required' => 'จำเป็นต้องกรอกนามสกุล',
                'id_card_number.unique' => 'พบหมายเลขบัตรประชาชนนี้ในระบบแล้ว',
                'username.unique' => 'รหัสผู้ใช้นี้มีอยู่ในระบบแล้ว',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
                'salary.required' => 'จำเป็นต้องกรอกเงินเดือน',
            ]
        );

        Employee::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'id_card_number' => $validatedData['id_card_number'],
            'phone_number' => $validatedData['phone_number'],
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'employment_status' => $validatedData['employment_status'],
            'start_date' => $validatedData['start_date'],
            'salary' => $validatedData['salary'],
        ]);

        return redirect()->route('employees.index')->with('success', 'เพิ่มข้อมูลเบื้องต้นพนักงานใหม่เรียบร้อยแล้ว กรุณาแจ้งพนักงานให้เพิ่มข้อมูลส่วนอื่นๆ');
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:employees,username,' . $employee->id,
            'role' => 'required|in:employee,owner',
            'id_card_number' => 'required|string|max:13|unique:employees,id_card_number,' . $employee->id,
            'phone_number' => 'required|string|max:20',
            'employment_status' => 'required|string',
            'start_date' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'previous_experience' => 'nullable|string',
            'bank_account' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($employee->profile_picture) {
                Storage::delete($employee->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = $path;
        }

        $employee->update($validatedData);

        return redirect()->route('employees.show', $employee->id)->with('success', 'ข้อมูลพนักงานถูกอัปเดตเรียบร้อยแล้ว');
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // ลบรูปโปรไฟล์ถ้ามี
        if ($employee->profile_picture) {
            Storage::delete('public/' . $employee->profile_picture);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'พนักงานถูกลบออกจากระบบเรียบร้อยแล้ว');
    }
}
