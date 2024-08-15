<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // ตรวจสอบบทบาทของผู้ใช้และเปลี่ยนเส้นทางตามบทบาท
        if ($user->role === 'owner') {
            return view('dashboard.owner', compact('user'));
        } elseif ($user->role === 'employee') {
            return view('dashboard.employee', compact('user'));
        } else {
            return abort(403, 'การเข้าถึงไม่ได้รับอนุญาติ');
        }
    }

    public function owner()
    {
        $user = Auth::user();
        if ($user->role === 'owner') {
            return view('dashboard.owner', compact('user'));
        } else {
            return abort(403, 'การเข้าถึงไม่ได้รับอนุญาติ');
        }
    }

    function employee()
    {
        $user = Auth::user();
        if ($user->role === 'employee') {
            return view('dashboard.employee', compact('user'));
        } else {
            return abort(403, 'การเข้าถึงไม่ได้รับอนุญาติ');
        }
    }
}
