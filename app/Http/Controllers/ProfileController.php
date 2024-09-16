<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

}