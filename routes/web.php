<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/no-access', function () {
    return view('no-access');
});



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//เทสระบบเฉยๆ
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// ตรวจสอบการล็อกอินก่อนเข้าถึงหน้านี้
Route::middleware('auth')->group(function () {
    //ตรวจสอบการกรอกข้อมูลเพิ่มเติมก่อนเข้าถึง
    Route::get('/complete-profile', [EmployeeController::class, 'showProfileCompletionForm'])
        ->name('employees.completeProfile');
    Route::post('/complete-profile', [EmployeeController::class, 'updateProfile'])
        ->name('employees.update_profile');

    // พนักงานเข้าถึงได้ในกรอบนี้
    Route::middleware(['auth', 'role:employee', 'check.profile'])->group(function () {
        Route::get('/employee', [DashboardController::class, 'employee'])->name('dashboard.employee');
    });

    // เจ้าของร้านเข้าถึงได้ในกรอบนี้
    Route::middleware(['auth', 'role:owner'])->group(function () {
        Route::get('/owner', [DashboardController::class, 'owner'])->name('dashboard.owner');

        Route::prefix('employees')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
            Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
            Route::post('/store', [EmployeeController::class, 'store'])->name('employees.store');
        });
    });
});
