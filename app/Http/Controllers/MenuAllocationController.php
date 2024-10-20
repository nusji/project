<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuAllocation;
use App\Models\MenuAllocationDetail;
use App\Models\SaleDetail;
use App\Models\Ingredient;
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

            // ตรวจสอบและหมุนเวียนเมนู
            $remainingMenusCount = $totalMenus - $bestSellingCount;
            $randomMenus = $this->getRandomMenusWithRotation(
                $bestSellingMenus->pluck('menu_id')->toArray(),
                $remainingMenusCount,
                $recentlyAllocatedMenus
            );

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
            $recentlyAllocatedMenus = array_merge(
                $recentlyAllocatedMenus,
                $randomMenus->pluck('id')->toArray()
            );
        }

        return redirect()->route('allocations.index')->with('success', 'การจัดสรรเมนูสำเร็จ');
    }

    // ดึงเมนูที่เหลือและหมุนเวียนโดยตรวจสอบให้ไม่ซ้ำกับวันก่อนหน้า
    public function getRandomMenusWithRotation($excludedMenus = [], $limit = 5, $recentlyAllocatedMenus = [])
    {
        // พยายามสุ่มเมนูที่ไม่ถูกจัดในวันก่อนหน้า
        $randomMenus = Menu::whereNotIn('id', $excludedMenus)
            ->whereNotIn('id', $recentlyAllocatedMenus)  // ตรวจสอบว่าเมนูไม่ถูกจัดไปในวันก่อนหน้า
            ->inRandomOrder()
            ->take($limit)
            ->get();

        // ถ้าจำนวนเมนูไม่พอ สามารถเลือกเมนูซ้ำจากวันก่อนหน้าได้ แต่ต้องไม่ซ้ำในวันติดกัน
        if ($randomMenus->count() < $limit) {
            $additionalMenus = Menu::whereNotIn('id', $excludedMenus)
                ->whereIn('id', $recentlyAllocatedMenus)  // อนุญาตให้สุ่มเมนูซ้ำ แต่ต้องไม่ใช่เมนูในวันติดกัน
                ->inRandomOrder()
                ->take($limit - $randomMenus->count())
                ->get();

            $randomMenus = $randomMenus->merge($additionalMenus);
        }

        return $randomMenus;
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

    public function show(Request $request, MenuAllocation $allocation)
    {
        // โหลดรายละเอียดการจัดสรรพร้อมเมนูที่เกี่ยวข้องและสูตรอาหารของพวกเขา
        $allocation->load('allocationDetails.menu.recipes.ingredient');

        $missingIngredients = [];        // เก็บวัตถุดิบที่ขาดในแต่ละเมนู
        $totalMissingIngredients = [];   // เก็บวัตถุดิบที่ขาดทั้งหมด

        // รับข้อมูลจำนวนการผลิตจากผู้ใช้
        $productionQuantities = $request->input('productionQuantities', []);

        // สะสมความต้องการวัตถุดิบทั้งหมด
        $totalRequiredIngredients = [];

        foreach ($allocation->allocationDetails as $detail) {
            $menuId = $detail->menu->id;
            // ใช้ค่าที่ผู้ใช้ป้อนถ้ามี มิฉะนั้นใช้ค่าเริ่มต้นเป็น 1
            $productionQuantity = isset($productionQuantities[$menuId]) ? (int)$productionQuantities[$menuId] : 1;

            foreach ($detail->menu->recipes as $recipe) {
                $ingredientId = $recipe->ingredient->id;
                $requiredAmount = $recipe->amount * $productionQuantity;

                if (!isset($totalRequiredIngredients[$ingredientId])) {
                    $totalRequiredIngredients[$ingredientId] = 0;
                }

                $totalRequiredIngredients[$ingredientId] += $requiredAmount;
            }
        }

        // ตรวจสอบว่าวัตถุดิบเพียงพอ
        foreach ($totalRequiredIngredients as $ingredientId => $requiredAmount) {
            $ingredient = Ingredient::findOrFail($ingredientId);

            if ($ingredient->ingredient_stock < $requiredAmount) {
                $missingAmount = $requiredAmount - $ingredient->ingredient_stock;
                $unit = $ingredient->ingredient_unit;

                // เก็บข้อมูลวัตถุดิบที่ขาดทั้งหมด
                if (isset($totalMissingIngredients[$ingredient->ingredient_name])) {
                    $totalMissingIngredients[$ingredient->ingredient_name] += $missingAmount;
                } else {
                    $totalMissingIngredients[$ingredient->ingredient_name] = $missingAmount;
                }

                // เก็บข้อมูลวัตถุดิบที่ขาดในแต่ละเมนูที่ใช้วัตถุดิบนี้
                foreach ($allocation->allocationDetails as $detail) {
                    $menu = $detail->menu;
                    $menuId = $menu->id;
                    $productionQuantity = isset($productionQuantities[$menuId]) ? (int)$productionQuantities[$menuId] : 1;

                    // ตรวจสอบว่าเมนูนี้ใช้วัตถุดิบที่ขาดหรือไม่
                    $recipe = $menu->recipes->firstWhere('ingredient_id', $ingredientId);
                    if ($recipe) {
                        $requiredForMenu = $recipe->amount * $productionQuantity;
                        $available = min($ingredient->ingredient_stock, $requiredForMenu);
                        $missingForMenu = $requiredForMenu - $available;

                        if ($missingForMenu > 0) {
                            $missingIngredients[$menuId][] = [
                                'ingredient_name' => $ingredient->ingredient_name,
                                'ingredient_unit' => $ingredient->ingredient_unit,
                                'missing_amount' => $missingForMenu,
                                'required_amount' => $requiredForMenu,
                            ];
                        }
                    }
                }
            }
        }

        // ส่งข้อมูลไปยัง view
        return view('allocations.show', compact('allocation', 'missingIngredients', 'totalMissingIngredients', 'productionQuantities'));
    }
}
