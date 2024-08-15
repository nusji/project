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
            ],
            [
                'first_name.required' => 'จำเป็นต้องกรอกชื่อ',
                'last_name.required' => 'จำเป็นต้องกรอกนามสกุล',
                'id_card_number.unique' => 'พบหมายเลขบัตรประชาชนนี้ในระบบแล้ว',
                'username.unique' => 'รหัสผู้ใช้นี้มีอยู่ในระบบแล้ว',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
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
        ]);

        return redirect()->route('employees.index')->with('success', 'เพิ่มข้อมูลเบื้องต้นพนักงานใหม่เรียบร้อยแล้ว กรุณาแจ้งพนักงานให้เพิ่มข้อมูลส่วนอื่นๆ');
    }

    // แสดงฟอร์มสำหรับกรอกข้อมูลเพิ่มเติม หลังจากที่ล็อกอินครั้งแรก หรือเมื่อพนักงานยังไม่ได้กรอกข้อมูลเพิ่มเติม
    public function showProfileCompletionForm()
    {
        return view('employees.complete_profile');
    }

    public function updateProfile(Request $request)
{
    // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
    $validatedData = $request->validate([
        'address' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'previous_experience' => 'nullable|string',
        'relevant_education' => 'nullable|string',
        'bank_account' => 'nullable|string',
        'bank_account_number' => 'nullable|string',
        'emergency_contact' => 'nullable|string',
        'health_info' => 'nullable|string',
        'religion' => 'nullable|string',
    ]);

    // ค้นหาพนักงานจากผู้ใช้งานที่เข้าสู่ระบบ
    $employee = Employee::where('user_id', auth()->id())->first();

    if ($employee) {
        // อัปเดตข้อมูลพนักงาน
        $employee->update([
            'address' => $validatedData['address'] ?? $employee->address,
            'date_of_birth' => $validatedData['date_of_birth'] ?? $employee->date_of_birth,
            'previous_experience' => $validatedData['previous_experience'] ?? $employee->previous_experience,
            'relevant_education' => $validatedData['relevant_education'] ?? $employee->relevant_education,
            'bank_account' => $validatedData['bank_account'] ?? $employee->bank_account,
            'bank_account_number' => $validatedData['bank_account_number'] ?? $employee->bank_account_number,
            'emergency_contact' => $validatedData['emergency_contact'] ?? $employee->emergency_contact,
            'health_info' => $validatedData['health_info'] ?? $employee->health_info,
            'religion' => $validatedData['religion'] ?? $employee->religion,
        ]);

        // จัดการกับการอัปโหลดรูปภาพโปรไฟล์
        if ($request->hasFile('profile_picture')) {
            // ลบรูปภาพเดิมถ้ามี
            if ($employee->profile_picture) {
                Storage::delete($employee->profile_picture);
            }

            // อัปโหลดรูปภาพใหม่และอัพเดทข้อมูลในฐานข้อมูล
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures');
            $employee->profile_picture = $profilePicturePath;
            $employee->save();
        }

        return redirect()->route('employees')
            ->with('success', 'ข้อมูลพนักงานถูกอัปเดตเรียบร้อยแล้ว');
    } else {
        return redirect()->route('employees')
            ->with('error', 'ไม่พบข้อมูลพนักงานที่ต้องการอัปเดต');
    }
}

    
}
