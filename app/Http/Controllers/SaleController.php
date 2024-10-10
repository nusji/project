<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\Production;
use App\Models\ProductionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('employee', 'saleDetails.menu')
            ->orderBy('sale_date', 'desc')
            ->paginate(20);
        return view('sales.index', compact('sales'));
    }




    public function create()
    {
        $today = Carbon::today();
        $productions = $this->getProductionWithMenus($today);
        $menus = $this->getMenusFromProduction($productions);
        $categories = $menus->pluck('menuType')->unique();

        return view('sales.create', compact('menus', 'categories', 'today'));
    }


    private function getProductionWithMenus($date)
    {
        return Production::whereDate('production_date', $date)
            ->with('productionDetails.menu.menuType')
            ->get();
    }

    private function getMenusFromProduction($productions)
    {
        return $productions->flatMap(function ($production) {
            return $production->productionDetails
                ->where('is_sold_out', false) // Exclude sold-out items
                ->map(function ($detail) {
                    return $detail->menu;
                });
        })->unique('id');
    }

    public function getMenusByDate(Request $request)
    {
        $date = Carbon::parse($request->date);
        $productions = $this->getProductionWithMenus($date);
        $menus = $this->getMenusFromProduction($productions);

        return response()->json([
            'menus' => $menus,
            'date' => $date->format('Y-m-d')
        ]);
    }


    public function showSoldOutManagement(Request $request, Production $production)
    {
        $productionDetails = $production->productionDetails()->with('menu')->get();
        $selectedDate = $production->production_date->format('Y-m-d');
    
        return view('sales.manage_sold_out', compact('productionDetails', 'selectedDate', 'production'));
    }
    

    public function updateSoldOutStatus(Request $request, Production $production)
    {
        $menuIds = $request->input('menu_ids', []);

        // อัปเดตสถานะ "ขายหมด" เฉพาะ ProductionDetail ของ production นี้
        $productionDetails = $production->productionDetails;

        foreach ($productionDetails as $detail) {
            $detail->is_sold_out = in_array($detail->menu_id, $menuIds);
            $detail->save();
        }

        return redirect()->route('sales.manageSoldOut', ['production' => $production->id])
            ->with('success', 'อัปเดตสถานะขายหมดเรียบร้อยแล้ว');
    }
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับ
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_type' => 'required|string'

        ]);

        try {
            DB::beginTransaction();

            // สร้างคำสั่งขาย (Sale)
            $sale = Sale::create([
                'payment_type' => $request->input('payment_type'),
                'sale_date' => now(),
                'employee_id' => \Illuminate\Support\Facades\Auth::user()->id,
            ]);

            // สร้างรายละเอียดคำสั่งขาย (Sale Details)
            foreach ($request->input('items') as $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Sale completed successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error during sale: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        // ดึงข้อมูลคำสั่งขาย และรายละเอียดคำสั่งขายที่เกี่ยวข้อง
        $sale = Sale::with('saleDetails.menu')->findOrFail($id);

        // ส่งข้อมูลไปยัง view
        return view('sales.show', compact('sale'));
    }


    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully!');
    }
}
