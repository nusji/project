<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Ingredient;
use App\Models\Sale;
use App\Models\Employee;
use App\Models\Menu;
use App\Models\Payroll;
use App\Models\Production;
use App\Models\MenuAllocation;
use App\Models\Order;


class DashboardController extends Controller
{

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // รับหรือเซ็ตวันที่เป็นวันพรุ่งนี้ถ้าไม่มีการส่งมา
        $selectedDate = $request->input('selected_date', Carbon::tomorrow()->toDateString());

        // ดึงข้อมูลการจัดสรรเมนู

        $menuAllocations = $this->getMenuAllocationsForDate($selectedDate) ?? [];
        // เรียกใช้ฟังก์ชันที่เราแยกออกมา
        $dailySales = $this->getDailySales();
        $monthlySales = $this->getMonthlySales();
        $paymentMethodSales = $this->getPaymentMethodSales();
        // รับค่าจำนวนเมนูจากผู้ใช้ หากไม่มี ให้ใช้ค่าเริ่มต้นเป็น 5
        $menuLimit = $request->input('menu_limit', 5);

        // เรียกฟังก์ชันพร้อมส่งจำนวนเมนูที่ผู้ใช้ระบุ
        $bestSellingMenu = $this->getBestSellingMenu($menuLimit);

        $lowStockProducts = $this->getLowStockProducts();
        $ingredientsToOrder = $this->getIngredientsToOrder();
        $pendingOrders = $this->getPendingOrders();

        $todayMenus = $this->getTodayMenus();
        $soldOutMenus = $this->getSoldOutMenus();

        $productionSummary = $this->getProductionSummary();

        $currentMonthSalary = $this->getCurrentMonthSalary();
        $lastMonthSalary = $this->getLastMonthSalary();

        $weeklySales = $this->getWeeklySales();
        $topSellingMenus = $this->getTopSellingMenus();

        $tomorrowMenus = $this->getTomorrowMenus();

        // Fetch today's sales for the logged-in employee
        $salesToday = Sale::where('employee_id', $user->id)
            ->whereDate('sale_date', Carbon::today())
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->sum(DB::raw('sale_details.quantity * menus.menu_price'));

        // Fetch the total sales for the current month
        $salesThisMonth = Sale::where('employee_id', $user->id)
            ->whereMonth('sale_date', Carbon::now()->month)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->sum(DB::raw('sale_details.quantity * menus.menu_price'));

        // Fetch the total number of orders
        $totalOrders = Sale::where('employee_id', $user->id)->count();

        // Fetch the latest sales entry by the employee
        $latestSales = Sale::where('employee_id', $user->id)
            ->latest()
            ->take(5) // Fetch the 5 most recent sales
            ->get();

        // Fetch the latest purchase orders by the employee
        $latestOrders = Order::where('employee_id', $user->id)
            ->latest()
            ->take(5) // Fetch the 5 most recent orders
            ->get();

        if ($user->role === 'owner') {
            return view('dashboard.owner', compact(
                'user',
                'dailySales',
                'monthlySales',
                'paymentMethodSales',
                'bestSellingMenu',
                'lowStockProducts',
                'ingredientsToOrder',
                'pendingOrders',
                'todayMenus',
                'soldOutMenus',
                'productionSummary',
                'currentMonthSalary',
                'lastMonthSalary',
                'weeklySales',
                'topSellingMenus',
                'tomorrowMenus',
                'menuAllocations',
                'selectedDate',
                'menuLimit',
                'salesToday',
                'salesThisMonth',
                'totalOrders',
                'latestSales',
                'latestOrders'
            ));
        } elseif ($user->role === 'employee') {
            return view('dashboard.employee', compact(
                'user',
                'dailySales',
                'monthlySales',
                'paymentMethodSales',
                'bestSellingMenu',
                'lowStockProducts',
                'ingredientsToOrder',
                'pendingOrders',
                'todayMenus',
                'soldOutMenus',
                'productionSummary',
                'currentMonthSalary',
                'lastMonthSalary',
                'weeklySales',
                'topSellingMenus',
                'tomorrowMenus',
                'menuAllocations',
                'selectedDate',
                'menuLimit',
                'salesToday',
                'salesThisMonth',
                'totalOrders',
                'latestSales',
                'latestOrders'
            ));
        } else {
            return abort(403, 'การเข้าถึงไม่ได้รับอนุญาติ');
        }
    }
    // 1. ฟังก์ชันดึงข้อมูลยอดขายรายวัน
    protected function getDailySales()
    {
        $today = Carbon::today();
        return Sale::whereDate('sale_date', $today)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->sum(DB::raw('sale_details.quantity * menus.menu_price'));
    }

    // 2. ฟังก์ชันดึงข้อมูลยอดขายรายเดือน พร้อมยอดขายเดือนก่อนหน้า
    protected function getMonthlySales()
    {
        $today = Carbon::today();
        $currentMonthSales = Sale::whereMonth('sale_date', $today->month)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->sum(DB::raw('sale_details.quantity * menus.menu_price'));

        // ยอดขายเดือนก่อนหน้า
        $lastMonth = $today->subMonth();
        $previousMonthSales = Sale::whereMonth('sale_date', $lastMonth->month)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->sum(DB::raw('sale_details.quantity * menus.menu_price'));

        // ส่งค่าทั้งสองกลับ
        return [
            'current' => $currentMonthSales,
            'previous' => $previousMonthSales
        ];
    }

    // 3. ฟังก์ชันดึงข้อมูลยอดขายรายสัปดาห์
    protected function getWeeklySales()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // วันที่เริ่มต้นสัปดาห์
        $endOfWeek = Carbon::now()->endOfWeek(); // วันที่สิ้นสุดสัปดาห์

        return Sale::whereBetween('sale_date', [$startOfWeek, $endOfWeek])
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(sale_details.quantity * menus.menu_price) as total'))
            ->groupBy('date')
            ->get();
    }


    // 3. ฟังก์ชันดึงยอดขายแยกตามช่องทางชำระเงิน
    protected function getPaymentMethodSales()
    {
        $today = Carbon::today();
        return Sale::select('payment_type', DB::raw('SUM(sale_details.quantity * menus.menu_price) as total'))
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->whereDate('sale_date', $today)
            ->groupBy('payment_type')
            ->get();
    }

    // 4. ฟังก์ชันดึงเมนูที่ขายดีสุด
    protected function getBestSellingMenu($limit = 5)
    {
        $today = Carbon::today();
        return Menu::select('menus.*', DB::raw('SUM(sale_details.quantity) as total_quantity'))
            ->join('sale_details', 'menus.id', '=', 'sale_details.menu_id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereDate('sales.sale_date', $today)
            ->groupBy('menus.id')
            ->orderBy('total_quantity', 'desc')
            ->take($limit) // จำกัดจำนวนเมนูตามพารามิเตอร์
            ->get(); // ใช้ get() เพื่อดึงหลายเมนู
    }


    // 5. ฟังก์ชันดึงวัตถุดิบที่เหลือน้อย
    protected function getLowStockProducts()
    {
        return Ingredient::whereColumn('ingredient_stock', '<=', 'minimum_quantity')->get();
    }

    // 6. ฟังก์ชันดึงวัตถุดิบที่ต้องสั่งซื้อเพิ่ม
    protected function getIngredientsToOrder()
    {
        return Ingredient::whereColumn('ingredient_stock', '<=', 'minimum_quantity')->get();
    }

    // 7. ฟังก์ชันดึงคำสั่งซื้อที่ค้างอยู่
    protected function getPendingOrders()
    {
        return DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('ingredients', 'order_details.ingredient_id', '=', 'ingredients.id')
            ->whereBetween('orders.order_date', [Carbon::now()->subDays(7), Carbon::now()])
            ->select('orders.*', 'order_details.*', 'ingredients.*')
            ->get();
    }

    // 8. ฟังก์ชันดึงเมนูที่ผลิตวันนี้
    protected function getTodayMenus()
    {
        $today = Carbon::today();
        return Production::with('productionDetails.menu')
            ->where('production_date', $today)
            ->get();
    }

    // 9. ฟังก์ชันดึงเมนูที่ขายหมดแล้ว
    protected function getSoldOutMenus()
    {
        return Production::whereHas('productionDetails', function ($query) {
            $query->where('is_sold_out', true);
        })->get();
    }

    // 10. ฟังก์ชันดึงสรุปการผลิตวันนี้
    protected function getProductionSummary()
    {
        $today = Carbon::today();
        return Production::with('productionDetails.menu')
            ->whereDate('production_date', $today)
            ->get();
    }

    // 11. ฟังก์ชันดึงข้อมูลเงินเดือนของเดือนปัจจุบัน
    protected function getCurrentMonthSalary()
    {
        $today = Carbon::today();
        return Payroll::whereMonth('payment_date', $today->month)->get();
    }

    // 12. ฟังก์ชันดึงข้อมูลเงินเดือนของเดือนที่แล้ว
    protected function getLastMonthSalary()
    {
        $lastMonth = Carbon::today()->subMonth();
        return Payroll::whereMonth('payment_date', $lastMonth->month)->get();
    }

    // 14. ฟังก์ชันดึงเมนูที่ขายดีที่สุด 5 อันดับ
    protected function getTopSellingMenus()
    {
        return Menu::withCount('saleDetails')
            ->orderBy('sale_details_count', 'desc')
            ->take(5)
            ->get();
    }

    // 15. ฟังก์ชันดึงเมนูสำหรับพรุ่งนี้
    protected function getTomorrowMenus()
    {
        return Production::where('production_date', Carbon::tomorrow())->get();
    }

    public function getMenuAllocationsForDate($selectedDate = null)
    {
        // ตรวจสอบว่ามีการส่งวันที่เข้ามาหรือไม่ ถ้าไม่มีให้ใช้วันพรุ่งนี้
        $selectedDate = $selectedDate ?? Carbon::tomorrow()->toDateString();

        // ดึงข้อมูลการจัดสรรเมนูตามวันที่กำหนด
        $menuAllocations = MenuAllocation::with('allocationDetails.menu')
            ->where('allocation_date', $selectedDate)
            ->get();

        // ส่งตัวแปรไปยัง View
        return $menuAllocations;
    }
}
