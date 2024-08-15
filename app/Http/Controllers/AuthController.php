<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            // ตรวจสอบบทบาทของผู้ใช้ที่ล็อกอินแล้ว
            $user = Auth::user();
            $role = $user->role; // หรือ 'employee_role' ขึ้นอยู่กับโครงสร้างฐานข้อมูลของคุณ

            // เปลี่ยนเส้นทางไปยังหน้าแดชบอร์ดที่ตรงกับบทบาทของผู้ใช้
            switch ($role) {
                case 'owner':
                    return redirect()->route('dashboard.owner');
                case 'employee':
                    return redirect()->route('dashboard.employee');
                default:
                    return redirect()->route('welcome'); // หรือเส้นทางอื่น ๆ ที่คุณต้องการ
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // ตรวจสอบ role ของ user
            $role = Auth::user()->role;

            // Redirect ไปยัง home page ที่เหมาะสมตาม role
            if ($role === 'owner') {
                return redirect()->intended('/owner');
            } elseif ($role === 'employee') {
                return redirect()->intended('/employee');
            } else {
                return redirect()->intended('welcome'); // หรือจะใช้หน้าอื่น ๆ ตามที่ต้องการ
            }
        }

        return back()->withErrors([
            'username' => 'ไม่พบบัญชีผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }




    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:employees',
            'password' => 'required|min:6',
            'name' => 'required',
            'email' => 'required|email|unique:employees',
        ]);

        $employee = Employee::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        Auth::login($employee);
        return redirect('dashboard');
    }
}
