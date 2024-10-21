<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\MenuAllocation;
use App\Models\MenuAllocationDetail;
use App\Models\SaleDetail;
use App\Models\Ingredient;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    // ฟังก์ชันแสดงหน้าแดชบอร์ดรายงาน
    public function index()
    {
        // เรียกใช้ฟังก์ชันรายงานแต่ละส่วน
        $topSellingMenus = $this->getTopSellingMenus();
        $leastSellingMenus = $this->getLeastSellingMenus();
        $mostUsedIngredients = $this->getMostUsedIngredients();
        $dailySales = $this->getDailySales();
        $feedbackAnalysis = $this->getFeedbackAnalysis();
        $lowStockIngredients = $this->getLowStockIngredients();
        $payrollSummary = $this->getPayrollSummary();
        $profitAnalysis = $this->getProfitAnalysis();
        $ingredientUsage = $this->getIngredientUsage();
        $salesByPaymentType = $this->getSalesByPaymentType();
        $employeePerformance = $this->getEmployeePerformance();
        $orderTrends = $this->getOrderTrends();

        return view('reports.index', compact(
            'topSellingMenus',
            'leastSellingMenus',
            'mostUsedIngredients',
            'dailySales',
            'feedbackAnalysis',
            'lowStockIngredients',
            'payrollSummary',
            'profitAnalysis',
            'ingredientUsage',
            'salesByPaymentType',
            'employeePerformance',
            'orderTrends'

        ));
    }

    public function getTopSellingMenus()
    {
        return Cache::remember('top_selling_menus', 60, function () {
            return SaleDetail::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('menu_id')
                ->orderBy('total_sold', 'desc')
                ->take(10)
                ->with(['menu' => function($query) {
                    $query->select('id', 'menu_name');
                }])
                ->get();
        });
    }
    
    protected function getLeastSellingMenus()
    {
        return Cache::remember('least_selling_menus', 60, function () {
            return SaleDetail::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('menu_id')
                ->orderBy('total_sold', 'asc')
                ->take(5)
                ->with(['menu' => function($query) {
                    $query->select('id', 'menu_name');
                }])
                ->get();
        });
    }
    
    

    protected function getMostUsedIngredients()
    {
        // ดึงข้อมูลเมนูที่ถูกผลิตจาก production_details
        return DB::table('production_details')
            ->join('menu_recipes', 'production_details.menu_id', '=', 'menu_recipes.menu_id') // เชื่อมต่อกับ menu_recipes
            ->join('ingredients', 'menu_recipes.ingredient_id', '=', 'ingredients.id') // เชื่อมต่อกับ ingredients
            ->select('ingredients.ingredient_name', DB::raw('count(production_details.menu_id) as total_used'))
            ->groupBy('ingredients.ingredient_name')
            ->orderBy('total_used', 'desc')
            ->take(5)
            ->get();
    }

    protected function getDailySales()
    {
        return DB::table('sale_details')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id') // เชื่อมกับตาราง sales เพื่อดึงข้อมูลวันที่
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id') // เชื่อมกับตาราง menus เพื่อดึงข้อมูลเมนู
            ->select(
                DB::raw('DATE(sales.sale_date) as date'),
                DB::raw('sum(sale_details.quantity) as total_sold'),
                DB::raw('sum(sale_details.menu_id) as daily_sales'),
                DB::raw('sum(sale_details.quantity * menus.menu_price) as total_revenue') // คำนวณยอดขายรวมต่อวัน
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();
    }

    public function getFeedbackAnalysis()
    {
        return DB::table('feedbacks')
            ->join('menus', 'feedbacks.menu_id', '=', 'menus.id')
            ->select(
                'menus.menu_name',
                DB::raw('AVG(feedbacks.rating) as average_rating'),
                DB::raw('COUNT(feedbacks.id) as total_feedback')
            )
            ->groupBy('menus.menu_name')
            ->orderBy('average_rating', 'desc')
            ->take(10)
            ->get();
    }

    public function getLowStockIngredients()
    {
        return Ingredient::whereColumn('ingredient_stock', '<', 'minimum_quantity')
            ->get(['ingredient_name', 'ingredient_stock', 'minimum_quantity']);
    }
    public function getEmployeePerformance()
    {
        return DB::table('employees')
            ->leftJoin('sales', 'employees.id', '=', 'sales.employee_id')
            ->leftJoin('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->leftJoin('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->select(
                'employees.name',
                DB::raw('COUNT(sales.id) as total_sales'),
                DB::raw('SUM(sale_details.quantity * menus.menu_price) as total_revenue')
            )
            ->whereMonth('sales.sale_date', '=', date('m'))
            ->whereYear('sales.sale_date', '=', date('Y'))
            ->groupBy('employees.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    public function getPayrollSummary()
    {
        return Payroll::with('employee')
            ->orderBy('payment_date', 'desc')
            ->take(12) // แสดงข้อมูลเงินเดือน 12 เดือนล่าสุด
            ->get();
    }
    public function getOrderTrends()
    {
        return DB::table('orders')
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('COUNT(id) as total_orders')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();
    }

    public function getSalesByPaymentType()
    {
        return DB::table('sales')
            ->select('payment_type', DB::raw('SUM(sale_details.quantity * menus.menu_price) as total_revenue'))
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->whereMonth('sales.sale_date', '=', date('m'))
            ->whereYear('sales.sale_date', '=', date('Y'))
            ->groupBy('payment_type')
            ->get();
    }

    //ไม่ได้ใช้งาน
    public function getIngredientUsage()
    {
        return DB::table('menu_recipes')
            ->join('menus', 'menu_recipes.menu_id', '=', 'menus.id')
            ->join('ingredients', 'menu_recipes.ingredient_id', '=', 'ingredients.id')
            ->join('sale_details', 'menus.id', '=', 'sale_details.menu_id')
            ->join('production_details', 'menus.id', '=', 'production_details.menu_id')
            ->select(
                'ingredients.ingredient_name',
                DB::raw('SUM(menu_recipes.amount * production_details.quantity) as total_used')
            )
            ->whereMonth('sale_details.created_at', '=', date('m'))
            ->whereYear('sale_details.created_at', '=', date('Y'))
            ->groupBy('ingredients.ingredient_name')
            ->orderBy('total_used', 'desc')
            ->take(10)
            ->get();
    }
    // ไม่ได้ใช้งาน

    /**
     * คำนวณกำไรจากยอดขายและยอดสั่งซื้อในช่วง 30 วันที่ผ่านมา
     */
    public function getProfitAnalysis()
    {
        // กำหนดช่วงวันที่ย้อนหลัง 30 วัน
        $startDate = Carbon::today()->subDays(29); // รวมวันนี้ด้วย
        $endDate = Carbon::today();

        // ดึงข้อมูลยอดขายรวมต่อวัน
        $salesData = DB::table('sale_details')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('menus', 'sale_details.menu_id', '=', 'menus.id')
            ->select(
                DB::raw('DATE(sales.sale_date) as date'),
                DB::raw('SUM(sale_details.quantity * menus.menu_price) as total_sales')
            )
            ->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        // ดึงข้อมูลยอดสั่งซื้อรวมต่อวัน
        $purchaseData = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->select(
                DB::raw('DATE(orders.order_date) as date'),
                DB::raw('SUM(order_details.quantity * order_details.price) as total_purchase')
            )
            ->whereBetween(DB::raw('DATE(orders.order_date)'), [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        // รวมข้อมูลยอดขายและยอดสั่งซื้อ
        $profitData = DB::table(DB::raw("(
            SELECT DATE(sale_date) as date, SUM(sale_details.quantity * menus.menu_price) as total_sales
            FROM sale_details
            JOIN sales ON sale_details.sale_id = sales.id
            JOIN menus ON sale_details.menu_id = menus.id
            WHERE DATE(sales.sale_date) BETWEEN '{$startDate}' AND '{$endDate}'
            GROUP BY DATE(sales.sale_date)
        ) as sales"))
            ->leftJoin(DB::raw("(
            SELECT DATE(order_date) as date, SUM(order_details.price) as total_purchase
            FROM order_details
            JOIN orders ON order_details.order_id = orders.id
            WHERE DATE(orders.order_date) BETWEEN '{$startDate}' AND '{$endDate}'
            GROUP BY DATE(orders.order_date)
        ) as purchases"), 'sales.date', '=', 'purchases.date')
            ->select(
                'sales.date',
                'sales.total_sales',
                DB::raw('IFNULL(purchases.total_purchase, 0) as total_purchase'),
                DB::raw('sales.total_sales - IFNULL(purchases.total_purchase, 0) as profit')
            )
            ->orderBy('sales.date', 'asc')
            ->get();

        // เติมวันที่ที่ขาดหายไปในข้อมูลกำไร
        $profitData = $this->fillMissingDates($profitData, $startDate, $endDate);

        return $profitData;
    }

    /**
     * เติมวันที่ที่ขาดหายไปในข้อมูลกำไร
     */
    private function fillMissingDates($data, $startDate, $endDate)
    {
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[$date->toDateString()] = [
                'date' => $date->toDateString(),
                'total_sales' => 0,
                'total_purchase' => 0,
                'profit' => 0
            ];
        }

        foreach ($data as $record) {
            $dates[$record->date] = [
                'date' => $record->date,
                'total_sales' => $record->total_sales,
                'total_purchase' => $record->total_purchase,
                'profit' => $record->profit
            ];
        }

        return collect($dates)->values();
    }
}
