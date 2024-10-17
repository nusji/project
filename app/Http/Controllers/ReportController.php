<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\MenuAllocation;
use Illuminate\Support\Facades\DB;

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

        return view('reports.index', compact('topSellingMenus', 'leastSellingMenus', 'mostUsedIngredients', 'dailySales'));
    }

    // เมนูที่ขายดีที่สุด
    protected function getTopSellingMenus()
    {
        return ProductionDetail::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->with('menu')
            ->get();
    }


    // เมนูที่ขายได้น้อยที่สุด
    protected function getLeastSellingMenus()
    {
        return ProductionDetail::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderBy('total_sold', 'asc')
            ->take(5)
            ->with('menu')
            ->get();
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
            ->select(DB::raw('DATE(sales.sale_date) as date'), DB::raw('sum(sale_details.quantity) as daily_sales')) // คำนวณยอดขายต่อวัน
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();
    }
}
