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
use App\Http\Controllers\SaleController;
use App\Http\Controllers\MenuAllocationController;
use App\Http\Controllers\FinanceController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/no-access', function () {
    return view('no-access');
});

// สร้าง Route สำหรับการล็อกอินและล็อกเอาท์
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// สร้าง Route สำหรับการลงทะเบียนและยืนยันการลงทะเบียน
Route::get('/profile/complete', [ProfileController::class, 'showCompleteForm'])->name('employees.complete_profile')->middleware('auth');
Route::post('/profile/complete', [ProfileController::class, 'completeProfile'])->name('employees.update_profile')->middleware('auth');

//Route for Guest
Route::get('/menu-today', [ProductionController::class, 'showMenuToday'])->name('menu-today');

//สิทธิ์เฉพาะสำหรับพนักงานเท่านั้น
Route::middleware(['auth', 'role:employee', 'check.profile'])->group(function () {
    Route::get('/employee', [DashboardController::class, 'employee'])->name('dashboard.employee');
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.profile');
    Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.profile_edit');
    Route::put('/profile/update/{id}', [ProfileController::class, 'updateProfile'])->name('profile.profile_update');
});

//สิทธิ์เฉพาะสำหรับเจ้าของร้านเท่านั้น
Route::middleware(['auth', 'role:owner', 'check.profile'])->group(function () {
    Route::get('/owner', [DashboardController::class, 'owner'])->name('dashboard.owner');
    Route::resource('finance', FinanceController::class);
});

//ยกเว้น ใช้ได้ทุกคน
Route::get('/sales/manage-sold-out/{production}', [SaleController::class, 'showSoldOutManagement'])->name('sales.manageSoldOut');
Route::post('/sales/update-sold-out/{production}', [SaleController::class, 'updateSoldOutStatus'])->name('sales.updateSoldOut');
Route::post('/payrolls/store-multiple', [PayrollController::class, 'storeMultiple'])->name('payrolls.storeMultiple');
Route::get('/employees/profile', [ProfileController::class, 'showProfile'])->name('employees.showProfile');
Route::get('/profile/{id}/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change_password')->middleware('auth');
Route::post('/profile/{id}/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update_password')->middleware('auth');
Route::get('/password/reset-custom', [ProfileController::class, 'showCustomPasswordResetForm'])->name('profile.reset_custom');
Route::post('/password/reset-custom', [ProfileController::class, 'resetPasswordWithVerification'])->name('profile.reset_custom.post');

use App\Http\Controllers\FeedbackController;

Route::resource('feedbacks', FeedbackController::class);

use App\Http\Controllers\ReportController;
use App\Models\Production;

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

Route::get('/', [ProductionController::class, 'showWelcomePage'])->name('welcome');

Route::get('/sales/menus-by-date', [SaleController::class, 'getMenusByDate'])->name('sales.menusByDate');

//สิทธิ์เฉพาะสำหรับเจ้าของร้านและพนักงานเท่านั้น
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
    Route::post('/productions/check-ingredients', [ProductionController::class, 'checkIngredients'])->name('productions.checkIngredients');


    //จัดการการขาย แบบ Resourceful Routes ประกอบด้วย index, create, store, show, edit, update, destroy
    route::resource('sales', SaleController::class);
    Route::get('sales/menus-by-date', [SaleController::class, 'getMenusByDate'])->name('sales.menus-by-date');
    Route::get('sales/manage-sold', [SaleController::class, 'manage_sold'])->name('sales.manage-sold');

    // สำหรับการดึงรายละเอียดเมนู
    Route::post('menus/details', [MenuController::class, 'getMenuDetails'])->name('menus.details');
    Route::get('/menus/search', [MenuController::class, 'search'])->name('menus.search');

    Route::resource('payrolls', PayrollController::class);
    Route::get('payrolls/{payroll}/print-slip', [PayrollController::class, 'printSlip'])->name('payrolls.print-slip');

    Route::get('salaries', [SalaryController::class, 'index'])->name('salaries.index');
    Route::get('salaries/{employee}/edit', [SalaryController::class, 'edit'])->name('salaries.edit');
    Route::put('salaries/{employee}', [SalaryController::class, 'update'])->name('salaries.update');
    Route::get('salaries/{employee}', [SalaryController::class, 'show'])->name('salaries.show');

    Route::resource('allocations', MenuAllocationController::class);
});
