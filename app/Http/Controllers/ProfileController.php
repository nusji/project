<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function showCompleteForm()
    {
        return view('employees.complete_profile');
    }

    public function completeProfile(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bank_account' => 'required|string',
            'bank_account_number' => 'required|string',
        ], [
            'address.required' => 'จำเป็นต้องกรอกที่อยู่',
            'date_of_birth.required' => 'จำเป็นต้องกรอกวันเกิด',
            'profile_picture.required' => 'จำเป็นต้องอัปโหลดรูปภาพ',
            'bank_account.required' => 'จำเป็นต้องกรอกชื่อธนาคาร',
            'bank_account_number.required' => 'จำเป็นต้องกรอกเลขที่บัญชีธนาคาร',
        ]);

        // ใช้ Model Employee แทน User
        $employee = Employee::find(Auth::id());
        $employee->address = $request->address;
        $employee->date_of_birth = $request->date_of_birth;
        // อัปโหลดรูปภาพและบันทึก URL
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $employee->profile_picture = $path; // บันทึก path ในฐานข้อมูล
        }
        $employee->bank_account = $request->bank_account;
        $employee->bank_account_number = $request->bank_account_number;
        $employee->is_profile_complete = true;
        $employee->save();
        return redirect()->route('dashboard.employee')->with('success', 'ยินดีต้อนรับเข้าสู่ระบบ');
    }

    public function showProfile()
    {
        $employee = Auth::user(); // ดึงข้อมูลของผู้ใช้ที่ล็อกอินอยู่

        return view('employees.profile', compact('employee'));
    }
    // แสดงฟอร์มแก้ไขข้อมูลโปรไฟล์
    public function editProfile()
    {
        // ดึงข้อมูลพนักงานจากไอดีของผู้ใช้ที่ล็อกอิน
        $employee = Employee::findOrFail(Auth::id());

        // ส่งข้อมูลพนักงานไปยัง view
        return view('employees.profile_edit', compact('employee'));
    }


    // อัปเดตข้อมูลโปรไฟล์
    public function updateProfile(Request $request)
    {
        // ดึงข้อมูลพนักงานจาก ID ของผู้ใช้ที่ล็อกอิน
        $employee = Employee::findOrFail(Auth::id());

        // ตรวจสอบสิทธิ์ว่าผู้ใช้ที่ล็อกอินสามารถแก้ไขข้อมูลนี้ได้หรือไม่
        if (Auth::id() !== $employee->id) {
            return redirect()->route('dashboard')->with('error', 'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้');
        }

        // ตรวจสอบข้อมูล (Validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ตรวจสอบรูปภาพ
        ]);

        // อัปเดตข้อมูลพนักงาน
        $data = $request->all();

        // ตรวจสอบว่ามีการอัปโหลดรูปโปรไฟล์ใหม่หรือไม่
        if ($request->hasFile('profile_picture')) {
            // ลบรูปโปรไฟล์เก่า (ถ้ามี)
            if ($employee->profile_picture) {
                Storage::delete('public/' . $employee->profile_picture);
            }

            // อัปโหลดรูปโปรไฟล์ใหม่
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        // อัปเดตข้อมูลพนักงาน
        $employee->update($data);

        // ส่งกลับไปยังแดชบอร์ดพร้อมข้อความแจ้งเตือน
        return redirect()->route('dashboard.employee')->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
    }

    public function changePasswordForm($id)
    {
        $employee = Employee::findOrFail($id);

        if (Auth::id() !== $employee->id) {
            return redirect()->route('dashboard')->with('error', 'คุณไม่มีสิทธิ์เปลี่ยนรหัสผ่านนี้');
        }

        return view('employees.change_password', compact('employee'));
    }

    public function updatePassword(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        if (Auth::id() !== $employee->id) {
            return redirect()->route('dashboard')->with('error', 'คุณไม่มีสิทธิ์เปลี่ยนรหัสผ่านนี้');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // ตรวจสอบรหัสผ่านปัจจุบัน
        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        }

        // อัปเดตรหัสผ่านใหม่
        $employee->password = Hash::make($request->new_password);
        $employee->save();

        return redirect()->route('profile.profile')->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
    
    // ฟังก์ชันแสดงฟอร์มรีเซ็ตรหัสผ่าน
    public function showCustomPasswordResetForm()
    {
        return view('employees.reset_custom');
    }

    // ฟังก์ชันจัดการการรีเซ็ตรหัสผ่าน
    public function resetPasswordWithVerification(Request $request)
    {
        // ตรวจสอบข้อมูลการกรอกฟอร์ม
        $request->validate([
            'id_card_number' => 'required|string',
            'date_of_birth' => 'required|date',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // ตรวจสอบข้อมูลพนักงานจากบัตรประชาชนและวันเดือนปีเกิด
        $employee = Employee::where('id_card_number', $request->id_card_number)
            ->where('date_of_birth', $request->date_of_birth)
            ->first();

        if (!$employee) {
            return back()->with('error', 'ข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง');
        }

        // อัปเดตรหัสผ่านใหม่
        $employee->password = Hash::make($request->new_password);
        $employee->save();

        // ส่งกลับไปยังหน้าล็อกอินพร้อมข้อความแจ้งเตือน
        return redirect()->route('logout')->with('success', 'รีเซ็ตรหัสผ่านเรียบร้อยแล้ว กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่');
    }
}
