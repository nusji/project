<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientTypeController;
use App\Http\Controllers\MenuTypeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SalaryController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/no-access', function () {
    return view('no-access');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('employees.complete_profile');
Route::post('/profile/complete', [ProfileController::class, 'completeProfile'])->name('employees.update_profile');

// พนักงานเข้าถึงได้ในกรอบนี้
Route::middleware(['auth', 'role:employee', 'check.profile'])->group(function () {
    Route::get('/employee', [DashboardController::class, 'employee'])->name('dashboard.employee');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('employees.profile_show');
});

// เจ้าของร้านเข้าถึงได้ในกรอบนี้
Route::middleware(['auth', 'role:owner', 'check.profile'])->group(function () {
    Route::get('/owner', [DashboardController::class, 'owner'])->name('dashboard.owner');
});


//ทุกคนเข้าถึงได้ในกรอบนี้
Route::middleware(['auth', 'check.profile'])->group(function () {

    //จัดการพนักงาน แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    Route::resource('employees', EmployeeController::class);

    //จัดการประเภทวัตถุดิบ แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    Route::resource('ingredient_types', IngredientTypeController::class);

    //จัดการวัตถุดิบ แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    Route::resource('ingredients', IngredientController::class);
    Route::post('/ingredients/update-quantity', [IngredientController::class, 'updateQuantity'])->name('ingredients.updateQuantity');

    //จัดการวัตถุดิบ แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    Route::resource('orders', OrderController::class);

    //จัดการประเภทเมนู แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    Route::resource('menu_types', MenuTypeController::class)->except(['show']);;

    //จัดการเมนู แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    Route::resource('menus', MenuController::class);

    //จัดการการผลิต แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    Route::resource('productions', ProductionController::class);
        // สำหรับการดึงรายละเอียดเมนู
        Route::post('menus/details', [MenuController::class, 'getMenuDetails'])->name('menus.details');
        Route::get('/menus/search', [MenuController::class, 'search'])->name('menus.search');

    Route::resource('payrolls', PayrollController::class);
    Route::get('payrolls/{payroll}/print-slip', [PayrollController::class, 'printSlip'])->name('payrolls.print-slip');

    Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');
    Route::get('salaries/{employee}/edit', [SalaryController::class, 'edit'])->name('salaries.edit');
    Route::put('salaries/{employee}', [SalaryController::class, 'update'])->name('salaries.update');
    Route::get('salaries/{employee}', [SalaryController::class, 'show'])->name('salaries.show');
    // ไม่มี Route สำหรับ 'create' และ 'store'
});
