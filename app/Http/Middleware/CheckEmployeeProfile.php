<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckEmployeeProfile
{
    public function handle($request, Closure $next)
    {
        $employee = Auth::user();

        // Check if required profile fields are empty
        if (
            !$employee->address || 
            !$employee->date_of_birth || 
            !$employee->profile_picture || 
            !$employee->previous_experience || 
            !$employee->relevant_education || 
            !$employee->bank_account || 
            !$employee->bank_account_number || 
            !$employee->emergency_contact || 
            !$employee->health_info || 
            !$employee->religion
        ) {
            // Redirect to the profile completion page
            return redirect()->route('employees.completeProfile');
        }

        return $next($request);
    }
}
