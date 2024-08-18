<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class CheckProfileComplete
{
        public function handle(Request $request, Closure $next)
        {
            if (Auth::check() && !Auth::user()->is_profile_complete) {
                return redirect()->route('employees.complete_profile');
            }
    
            return $next($request);
        }

}
