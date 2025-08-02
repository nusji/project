<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        // Get the search query from the request
        $search = $request->input('search');

        // If there's a search query, filter the results
        if ($search) {
            $employees = Employee::where('name', 'like', '%' . $search . '%')
            ->orWhere('id_card_number', 'like', '%' . $search . '%')
            ->orWhere('phone_number', 'like', '%' . $search . '%')
            ->orWhere('employment_type', 'like', '%' . $search . '%')
            ->paginate(20);
        } else {
            // If no search query, retrieve all records
            $employees = Employee::paginate(20);
        }
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
                'name' => 'required|string|max:255',
                'id_card_number' => 'required|string|max:13|unique:employees,id_card_number,NULL,id,deleted_at,NULL',
                'phone_number' => 'required|string|max:10',
                'employment_type' => 'required|string',
                'username' => 'required|string|unique:employees,username,NULL,id,deleted_at,NULL',
                'password' => 'required|string|min:8',
                'salary' => 'required|numeric',
                'start_date' => 'required|date',
            ],
            [
                'name.required' => 'จำเป็นต้องกรอกชื่อนามสกุล',
                'id_card_number.unique' => 'พบหมายเลขบัตรประชาชนนี้ในระบบแล้ว',
                'phone_number.required' => 'จำเป็นต้องกรอกเบอร์โทรศัพท์',
                'employment_type.required' => 'จำเป็นต้องเลือกประเภทการจ้างงาน',
                'username.unique' => 'รหัสผู้ใช้นี้มีอยู่ในระบบแล้ว',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
                'salary.required' => 'จำเป็นต้องกรอกเงินเดือน',
                'start_date.required' => 'จำเป็นต้องกรอกวันที่เริ่มงาน',
            ]
        );

        // Check for a soft-deleted employee with the same id_card_number or username
        $employee = Employee::withTrashed()
            ->where('id_card_number', $validatedData['id_card_number'])
            ->orWhere('username', $validatedData['username'])
            ->first();

        if ($employee) {
            if ($employee->trashed()) {
                // If employee exists and is soft-deleted, restore the record
                $employee->restore();

                // Update the employee's data with the new input
                $employee->update([
                    'name' => $validatedData['name'],
                    'phone_number' => $validatedData['phone_number'],
                    'employment_type' => $validatedData['employment_type'],
                    'username' => $validatedData['username'],
                    'password' => Hash::make($validatedData['password']),
                    'salary' => $validatedData['salary'],
                    'start_date' => $validatedData['start_date'],
                ]);

                return redirect()->route('employees.index')->with('success', 'ข้อมูลพนักงานที่ถูกลบถูกกู้คืนและอัปเดตเรียบร้อยแล้ว');
            } else {
                return redirect()->back()->withErrors(['id_card_number' => 'พนักงานนี้มีอยู่ในระบบแล้ว']);
            }
        }

        // If no existing record found, create a new employee
        Employee::create([
            'name' => $validatedData['name'],
            'id_card_number' => $validatedData['id_card_number'],
            'phone_number' => $validatedData['phone_number'],
            'employment_type' => $validatedData['employment_type'],
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'salary' => $validatedData['salary'],
            'start_date' => $validatedData['start_date'],
        ]);

        return redirect()->route('employees.index')->with('success', 'เพิ่มข้อมูลเบื้องต้นพนักงานใหม่เรียบร้อยแล้ว<br>กรุณาแจ้งพนักงานให้เพิ่มข้อมูลส่วนอื่นๆ');
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
            'name' => 'required|string|max:255',
            'id_card_number' => 'required|string|max:13|unique:employees,id_card_number,' . $employee->id,
            'phone_number' => 'required|string|max:10',
            'employment_type' => 'required|string',
            'username' => 'required|string|max:255|unique:employees,username,' . $employee->id,
            'salary' => 'required|numeric',
            'start_date' => 'nullable|date',
            'role' => 'required|in:employee,owner,none',

            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'bank_account' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'จำเป็นต้องกรอกชื่อนามสกุล',
            'id_card_number.unique' => 'พบหมายเลขบัตรประชาชนนี้ในระบบแล้ว',
            'phone_number.required' => 'จำเป็นต้องกรอกเบอร์โทรศัพท์',
            'employment_type.required' => 'จำเป็นต้องเลือกประเภทการจ้างงาน',
            'username.unique' => 'รหัสผู้ใช้นี้มีอยู่ในระบบแล้ว',
            'salary.required' => 'จำเป็นต้องกรอกเงินเดือน',
            'start_date.required' => 'จำเป็นต้องกรอกวันที่เริ่มงาน',
            'role.required' => 'จำเป็นต้องเลือกบทบาท',


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
