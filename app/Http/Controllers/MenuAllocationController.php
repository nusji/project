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
            ->orderBy('id', 'desc')
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
        // โหลดข้อมูลที่เกี่ยวข้องทั้งหมด
        $allocation->load('allocationDetails.menu.recipes.ingredient');

        $ingredientUsage = [];
        $remainingIngredients = [];
        $missingIngredients = [];

        // รับข้อมูลจำนวนการผลิตจากผู้ใช้
        $productionQuantities = $request->input('productionQuantities', []);

        // เริ่มต้นด้วยการกำหนดปริมาณวัตถุดิบคงเหลือเท่ากับสต็อกปัจจุบัน
        $ingredients = Ingredient::all();
        foreach ($ingredients as $ingredient) {
            $remainingIngredients[$ingredient->id] = $ingredient->ingredient_stock;
        }

        // วนลูปผ่านทุกรายการในการจัดสรร
        foreach ($allocation->allocationDetails as $detail) {
            $menuId = $detail->menu->id;
            $productionQuantity = isset($productionQuantities[$menuId]) ? (int)$productionQuantities[$menuId] : 1;

            $ingredientUsage[$menuId] = [];
            $missingIngredients[$menuId] = [];

            // คำนวณการใช้วัตถุดิบสำหรับแต่ละเมนู
            foreach ($detail->menu->recipes as $recipe) {
                $ingredientId = $recipe->ingredient->id;
                $requiredAmount = $recipe->amount * $productionQuantity;

                $availableAmount = $remainingIngredients[$ingredientId];
                $usedAmount = min($requiredAmount, $availableAmount);
                $missingAmount = max(0, $requiredAmount - $usedAmount);

                // บันทึกข้อมูลการใช้วัตถุดิบ
                $ingredientUsage[$menuId][] = [
                    'ingredient_name' => $recipe->ingredient->ingredient_name,
                    'ingredient_unit' => $recipe->ingredient->ingredient_unit,
                    'required_amount' => $requiredAmount,
                    'available_amount' => $availableAmount,
                    'used_amount' => $usedAmount,
                    'missing_amount' => $missingAmount,
                ];

                // บันทึกข้อมูลวัตถุดิบที่ขาด (ถ้ามี)
                if ($missingAmount > 0) {
                    $missingIngredients[$menuId][] = [
                        'ingredient_name' => $recipe->ingredient->ingredient_name,
                        'ingredient_unit' => $recipe->ingredient->ingredient_unit,
                        'missing_amount' => $missingAmount,
                    ];
                }

                // อัปเดตปริมาณวัตถุดิบที่เหลือ
                $remainingIngredients[$ingredientId] -= $usedAmount;
            }
        }

        // คำนวณวัตถุดิบที่ขาดทั้งหมด
        $totalMissingIngredients = [];
        foreach ($missingIngredients as $menuMissing) {
            foreach ($menuMissing as $missing) {
                $ingredientName = $missing['ingredient_name'];
                if (!isset($totalMissingIngredients[$ingredientName])) {
                    $totalMissingIngredients[$ingredientName] = [
                        'missing_amount' => 0,
                        'unit' => $missing['ingredient_unit'],
                    ];
                }
                $totalMissingIngredients[$ingredientName]['missing_amount'] += $missing['missing_amount'];
            }
        }

        // ส่งข้อมูลไปยัง view
        return view('allocations.show', compact('allocation', 'ingredientUsage', 'missingIngredients', 'totalMissingIngredients', 'productionQuantities'));
    }
}
