<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // ตรวจสอบว่า role ของผู้ใช้ตรงกับ role ที่กำหนดไว้หรือไม่
        if (!in_array($user->role, $roles)) {
            return redirect('/no-access');
        }

        return $next($request);
    }
}
