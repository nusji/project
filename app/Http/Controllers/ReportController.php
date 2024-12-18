<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Menu;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\MenuAllocationDetail;
use Carbon\Carbon;
use PDF; // หากต้องการสร้าง PDF
use Illuminate\Support\Facades\DB;
use App\Models\Feedback;
use App\Models\Ingredient;

class ReportController extends Controller
{
    /**
     * แสดงหน้า Reports Index
     */
    public function index()
    {
        $profitAnalysis = $this->getProfitAnalysis();
        return view('reports.index', compact('profitAnalysis'));
    }

    public function bestSellingMenus(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $bestSellingMenus = SaleDetail::select('menu_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('sale_date', [$startDate, $endDate]);
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_quantity')
            ->with('menu')
            ->take(10)
            ->get();

        // เตรียมข้อมูลสำหรับกราฟ
        $menuNames = $bestSellingMenus->pluck('menu.menu_name');
        $quantities = $bestSellingMenus->pluck('total_quantity');

        return view('reports.best-selling-menus', compact('bestSellingMenus', 'startDate', 'endDate', 'menuNames', 'quantities'));
    }


    public function unsoldMenus(Request $request)
    {
        $date = $request->input('date', \Carbon\Carbon::today()->toDateString());

        if (\Carbon\Carbon::parse($date)->isFuture()) {
            $date = \Carbon\Carbon::today()->toDateString();
        }

        // ดึงข้อมูลการผลิตในวันที่กำหนด
        $productions = ProductionDetail::whereHas('production', function ($query) use ($date) {
            $query->whereDate('production_date', $date);
        })->with('menu')->get();

        // เมนูที่ขายไม่หมดในวันที่กำหนด
        $unsoldMenus = $productions->filter(function ($item) {
            return $item->remaining_amount > 0;
        });

        // เมนูที่ขายหมดในวันที่กำหนด
        $soldOutMenus = $productions->filter(function ($item) {
            return $item->remaining_amount == 0;
        });

        // รับช่วงเวลาจากผู้ใช้ หรือใช้ค่าเริ่มต้นเป็นเดือนนี้
        $startDate = $request->input('start_date', \Carbon\Carbon::parse($date)->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', \Carbon\Carbon::parse($date)->endOfMonth()->toDateString());

        // สรุปจำนวนครั้งที่เมนูขายหมดในช่วงเวลาที่กำหนด
        $soldOutCounts = ProductionDetail::whereHas('production', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('production_date', [$startDate, $endDate]);
        })
            ->where('remaining_amount', 0)
            ->groupBy('menu_id')
            ->select('menu_id', DB::raw('count(*) as sold_out_times'))
            ->pluck('sold_out_times', 'menu_id');

        // ดึงข้อมูลเมนูทั้งหมดที่มีการขายหมดในช่วงเวลาที่กำหนด
        $menus = Menu::whereIn('id', $soldOutCounts->keys())->get();
        // เตรียมข้อมูลสำหรับกราฟเมนูที่ขายไม่หมด
        $unsoldMenuNames = $unsoldMenus->pluck('menu.menu_name');
        $unsoldMenuQuantities = $unsoldMenus->pluck('remaining_amount');

        // เตรียมข้อมูลสำหรับกราฟเมนูที่ขายหมด
        $soldOutMenuNames = $soldOutMenus->pluck('menu.menu_name');
        $soldOutMenuCounts = $soldOutMenus->pluck('menu_id')->countBy()->values();

        // เตรียมข้อมูลสำหรับกราฟสรุปการขายหมดในเดือนนี้
        $summaryMenuNames = $menus->pluck('menu_name');
        $summarySoldOutCounts = $menus->pluck('id')->map(function ($menuId) use ($soldOutCounts) {
            return $soldOutCounts[$menuId];
        });

        return view('reports.unsold-menus', compact(
            'unsoldMenus',
            'soldOutMenus',
            'soldOutCounts',
            'menus',
            'date',
            'unsoldMenuNames',
            'unsoldMenuQuantities',
            'soldOutMenuNames',
            'soldOutMenuCounts',
            'summaryMenuNames',
            'summarySoldOutCounts'
        ));
    }

    public function menuRotation(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $menuAllocations = MenuAllocationDetail::select('menu_id', DB::raw('COUNT(*) as allocation_count'))
            ->whereHas('menuAllocation', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('allocation_date', [$startDate, $endDate]);
            })
            ->groupBy('menu_id')
            ->with('menu')
            ->orderByDesc('allocation_count')
            ->get();

        // เตรียมข้อมูลสำหรับกราฟ
        $menuNames = $menuAllocations->pluck('menu.menu_name');
        $allocationCounts = $menuAllocations->pluck('allocation_count');

        return view('reports.menu-rotation', compact('menuAllocations', 'startDate', 'endDate', 'menuNames', 'allocationCounts'));
    }


    public function productionForecast(Request $request)
    {
        $forecastDate = $request->input('forecast_date', Carbon::tomorrow()->toDateString());
        $days = $request->input('days', 7);

        $startDate = Carbon::parse($forecastDate)->subDays($days)->toDateString();
        $endDate = Carbon::parse($forecastDate)->subDay()->toDateString();

        $forecastData = SaleDetail::select('menu_id', DB::raw('AVG(quantity) as average_quantity'))
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('sale_date', [$startDate, $endDate]);
            })
            ->groupBy('menu_id')
            ->with('menu')
            ->get();

        // เตรียมข้อมูลสำหรับกราฟ
        $menuNames = $forecastData->pluck('menu.menu_name');
        $averageQuantities = $forecastData->pluck('average_quantity');
        $recommendedQuantities = $averageQuantities->map(function ($quantity) {
            return ceil($quantity * 1.1);
        });

        return view('reports.production-forecast', compact(
            'forecastData',
            'forecastDate',
            'days',
            'menuNames',
            'averageQuantities',
            'recommendedQuantities'
        ));
    }


    public function menuTrends(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $salesData = SaleDetail::select(
            DB::raw('DATE(sales.sale_date) as date'),
            'sale_details.menu_id',
            DB::raw('SUM(quantity) as total_quantity')
        )
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->groupBy('date', 'sale_details.menu_id')
            ->orderBy('date')
            ->get();

        $menus = Menu::pluck('menu_name', 'id');

        $dates = $salesData->pluck('date')->unique()->values()->sort()->toArray();

        $trendData = [];

        foreach ($menus as $menuId => $menuName) {
            $menuSales = $salesData->where('menu_id', $menuId)->keyBy('date');
            $salesQuantities = [];
            foreach ($dates as $date) {
                $salesQuantities[] = isset($menuSales[$date]) ? $menuSales[$date]->total_quantity : 0;
            }
            $trendData[] = [
                'menu_name' => $menuName,
                'sales_data' => $salesQuantities,
                'dates' => $dates,
            ];
        }

        return view('reports.menu-trends', compact('trendData', 'startDate', 'endDate'));
    }



    public function resourceLoss(Request $request)
    {
        $date = $request->input('date', Carbon::yesterday()->toDateString());

        $productions = ProductionDetail::whereHas('production', function ($query) use ($date) {
            $query->whereDate('production_date', $date);
        })->with('menu')->get();

        $lossData = $productions->map(function ($item) {
            $productionAmount = $item->production_amount; // Adjust according to your model's field
            $remainingAmount = $item->remaining_amount;
            $soldAmount = $productionAmount - $remainingAmount;
            $menuPrice = $item->menu->menu_price;
            $portionSize = $item->menu->portion_size;

            // Calculate number of unsold portions
            $numberOfUnsoldPortions = $remainingAmount / $portionSize;

            // Calculate loss
            $loss = $menuPrice * $numberOfUnsoldPortions;

            return [
                'menu_name' => $item->menu->menu_name,
                'production_amount' => $productionAmount,
                'sold_amount' => $soldAmount,
                'remaining_amount' => $remainingAmount,
                'number_of_unsold_portions' => $numberOfUnsoldPortions,
                'loss' => $loss,
            ];
        });

        return view('reports.resource-loss', compact('lossData', 'date'));
    }



    public function customerFeedback(Request $request)
    {
        $feedbacks = Feedback::with('menu')->whereHas('menu')->get();

        $feedbackData = $feedbacks->groupBy('menu_id')->map(function ($item) {
            return [
                'menu_name' => $item->first()->menu->menu_name,
                'average_rating' => $item->avg('rating'),
                'total_feedbacks' => $item->count(),
            ];
        });

        return view('reports.customer-feedback', compact('feedbackData'));
    }



    public function menuProfitability(Request $request)
    {
        $menus = Menu::with('recipes.ingredient')->get();

        $profitData = $menus->map(function ($menu) {
            $totalCost = $menu->recipes->sum(function ($recipe) {
                return $recipe->amount * $recipe->ingredient->cost;
            });

            $salesData = SaleDetail::where('menu_id', $menu->id)
                ->with('sale')
                ->get();

            $totalRevenue = $salesData->sum(function ($saleDetail) use ($menu) {
                return $saleDetail->quantity * $menu->menu_price;
            });

            $profit = $totalRevenue - ($totalCost * $salesData->sum('quantity'));

            return [
                'menu_name' => $menu->menu_name,
                'total_revenue' => $totalRevenue,
                'total_cost' => $totalCost * $salesData->sum('quantity'),
                'profit' => $profit,
            ];
        });

        return view('reports.menu-profitability', compact('profitData'));
    }
    public function ingredientStock(Request $request)
    {
        $ingredients = Ingredient::all();

        return view('reports.ingredient-stock', compact('ingredients'));
    }

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
