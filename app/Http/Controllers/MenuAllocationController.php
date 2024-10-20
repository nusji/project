<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuAllocation;
use App\Models\MenuAllocationDetail;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\DB;

class MenuAllocationController extends Controller
{
    // แสดงรายการการจัดสรรเมนูทั้งหมด
    public function index()
    {
        $allocations = MenuAllocation::with('allocationDetails.menu')
            ->orderBy('allocation_date', 'desc')
            ->paginate(10);

        return view('allocations.index', compact('allocations'));
    }
    // แสดงฟอร์มให้ผู้ใช้กรอกข้อมูลการจัดสรรเมนู
    public function create()
    {
        return view('allocations.create');
    }

    // บันทึกการจัดสรรเมนู
    public function store(Request $request)
    {
        $allocationDate = $request->input('allocation_date');  // วันที่เริ่มต้น
        $days = $request->input('days');  // จำนวนวันในการจัดสรร
        $bestSellingCount = $request->input('best_selling_count');  // จำนวนเมนูขายดีที่ต้องการคงไว้
        $totalMenus = $request->input('total_menus');  // จำนวนเมนูทั้งหมดต่อวัน

        $recentlyAllocatedMenus = [];  // เก็บรายการเมนูที่ถูกจัดสรรในวันก่อนหน้า

        for ($i = 0; $i < $days; $i++) {
            // คำนวณวันที่จัดสรรในแต่ละวัน
            $currentDate = date('Y-m-d', strtotime("$allocationDate +$i days"));

            // ดึงเมนูขายดีตามจำนวนที่ผู้ใช้กำหนด
            $bestSellingMenus = $this->getBestSellingMenus($bestSellingCount);

            // สุ่มเมนูที่เหลือ โดยหลีกเลี่ยงเมนูที่ถูกจัดไปในวันก่อนหน้า
            $remainingMenusCount = $totalMenus - $bestSellingCount;
            $randomMenus = $this->getRandomMenus($bestSellingMenus->pluck('menu_id')->toArray(), $remainingMenusCount, $recentlyAllocatedMenus);

            // บันทึกการจัดสรรใหม่สำหรับวันปัจจุบัน
            $menuAllocation = MenuAllocation::create([
                'allocation_date' => $currentDate,
            ]);

            // บันทึกเมนูขายดี
            foreach ($bestSellingMenus as $menu) {
                MenuAllocationDetail::create([
                    'menu_allocation_id' => $menuAllocation->id,
                    'menu_id' => $menu->menu_id,
                ]);
            }

            // บันทึกเมนูที่สุ่ม
            foreach ($randomMenus as $menu) {
                MenuAllocationDetail::create([
                    'menu_allocation_id' => $menuAllocation->id,
                    'menu_id' => $menu->id,
                ]);
            }

            // อัปเดตเมนูที่ถูกสุ่มในวันนี้ เพื่อไม่ให้ซ้ำในวันถัดไป
            $recentlyAllocatedMenus = $randomMenus->pluck('id')->toArray();
        }

        return redirect()->route('allocations.index')->with('success', 'การจัดสรรเมนูสำเร็จ');
    }

    // ดึงเมนูขายดี
    public function getBestSellingMenus($limit = 10)
    {
        // ดึงเมนูขายดีที่สามารถซ้ำได้บ่อยขึ้น
        return SaleDetail::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderBy('total_sold', 'desc')
            ->take($limit)
            ->with('menu')
            ->get();
    }

    // ดึงเมนูที่เหลือ โดยตรวจสอบให้ไม่ซ้ำกับวันก่อนหน้า
    public function getRandomMenus($excludedMenus = [], $limit = 5, $recentlyAllocatedMenus = [])
    {
        return Menu::whereNotIn('id', $excludedMenus)
            ->whereNotIn('id', $recentlyAllocatedMenus)  // ตรวจสอบว่าเมนูไม่ถูกจัดไปในวันก่อนหน้า
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    public function show(MenuAllocation $allocation)
    {
        // โหลดรายละเอียดการจัดสรรเมนูพร้อมเมนูที่เกี่ยวข้อง
        $allocation->load('allocationDetails.menu');

        $missingIngredients = [];  // เก็บข้อมูลวัตถุดิบที่ขาด

        // ตรวจสอบวัตถุดิบของแต่ละเมนู
        foreach ($allocation->allocationDetails as $detail) {
            foreach ($detail->menu->recipes as $recipe) {
                $ingredient = $recipe->ingredient;
                $requiredAmount = $recipe->amount * 1;  // ปริมาณที่ต้องใช้ตามสูตร

                // ตรวจสอบว่าวัตถุดิบเพียงพอหรือไม่
                if ($ingredient->ingredient_stock < $requiredAmount) {
                    $missingAmount = $requiredAmount - $ingredient->ingredient_stock;

                    // เก็บข้อมูลวัตถุดิบที่ขาด
                    $missingIngredients[] = [
                        'menu_name' => $detail->menu->menu_name,
                        'ingredient_name' => $ingredient->ingredient_name,
                        'missing_amount' => $missingAmount,
                        'required_amount' => $requiredAmount,
                    ];
                }
            }
        }

        // ส่งข้อมูลไปยัง View
        return view('allocations.show', compact('allocation', 'missingIngredients'));
    }
}
