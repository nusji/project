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
        // ดึงเมนูขายดี (สามารถปรับแต่งจำนวนได้ตามต้องการ)
        $bestSellingMenus = $this->getBestSellingMenus(10);

        return view('allocations.create', compact('bestSellingMenus'));
    }


    public function store(Request $request)
    {
        $allocationDate = $request->input('allocation_date');  // วันที่เริ่มต้น
        $days = $request->input('days');  // จำนวนวันในการจัดสรร
        $selectedBestSellingMenus = $request->input('best_selling_menus', []);  // รายการเมนูขายดีที่ผู้ใช้เลือก
        $randomMenusCount = $request->input('random_menus_count', 0);  // จำนวนเมนูสุ่มที่ต้องการเพิ่ม
        $totalMenus = $request->input('total_menus');  // จำนวนเมนูทั้งหมดต่อวัน

        // ตรวจสอบว่าจำนวนเมนูขายดีที่เลือกไม่เกินขีดจำกัดและรวมกับเมนูสุ่มไม่เกิน total_menus
        if (count($selectedBestSellingMenus) + $randomMenusCount > $totalMenus) {
            return back()->withErrors(['random_menus_count' => 'จำนวนเมนูสุ่มและเมนูขายดีรวมกันต้องไม่เกินจำนวนเมนูทั้งหมด']);
        }

        $recentlyAllocatedMenus = [];  // เก็บรายการเมนูที่ถูกจัดสรรในวันก่อนหน้า

        for ($i = 0; $i < $days; $i++) {
            // คำนวณวันที่จัดสรรในแต่ละวัน
            $currentDate = date('Y-m-d', strtotime("$allocationDate +$i days"));

            // ดึงเมนูขายดีที่ผู้ใช้เลือก
            $bestSellingMenus = Menu::whereIn('id', $selectedBestSellingMenus)->get();

            // ตรวจสอบและหมุนเวียนเมนูสุ่ม
            $randomMenus = $this->getRandomMenusWithRotation(
                $bestSellingMenus->pluck('id')->toArray(),
                $randomMenusCount,
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
                    'menu_id' => $menu->id,
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
        return SaleDetail::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderBy('total_sold', 'desc')
            ->take($limit)
            ->with('menu')
            ->get()
            ->map(function ($saleDetail) {
                // ตรวจสอบว่าเมนูมีอยู่จริง
                if ($saleDetail->menu) {
                    // เพิ่ม property total_sold ให้กับ Menu model
                    $saleDetail->menu->total_sold = $saleDetail->total_sold;
                    return $saleDetail->menu;
                }
            })
            ->filter(); // ลบค่า null ออกหากเมนูไม่พบ
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
        $missingIngredients = [];
        $totalMissingIngredients = [];

        // รับข้อมูลจำนวนการผลิตจาก request หรือจากฐานข้อมูล
        $productionQuantities = $request->input('productionQuantities', []);

        // ถ้าไม่มีการส่งข้อมูลจาก request ให้ใช้ค่าจากฐานข้อมูล
        if (!$productionQuantities) {
            foreach ($allocation->allocationDetails as $detail) {
                $productionQuantities[$detail->menu->id] = $detail->production_quantity ?? 1;
            }
        }

        // เริ่มต้นด้วยการกำหนดปริมาณวัตถุดิบคงเหลือเท่ากับสต็อกปัจจุบัน
        $ingredients = Ingredient::all();
        $remainingIngredients = [];
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

    public function destroy($id)
    {
        // Find the allocation by ID
        $allocation = MenuAllocation::findOrFail($id);

        // Delete the allocation
        $allocation->delete();

        // Redirect or return a response
        return redirect()->route('allocations.index')->with('success', 'ลบการจัดสรรเมนูสำเร็จ');
    }

    public function updateProduction(Request $request, MenuAllocation $allocation)
    {
        // รับข้อมูล productionQuantities จาก request
        $productionQuantities = $request->input('productionQuantities', []);

        // เริ่มการทำธุรกรรมเพื่อความปลอดภัย
        DB::beginTransaction();

        try {
            foreach ($allocation->allocationDetails as $detail) {
                $menuId = $detail->menu_id;
                if (isset($productionQuantities[$menuId])) {
                    $quantity = (int)$productionQuantities[$menuId];
                    // ตรวจสอบให้แน่ใจว่าจำนวนที่ระบุเป็นค่าบวก
                    if ($quantity < 1) {
                        throw new \Exception("Production quantity must be at least 1 for menu ID {$menuId}");
                    }
                    // อัปเดต production_quantity
                    $detail->production_quantity = $quantity;
                    $detail->save();
                }
            }

            DB::commit();

            return redirect()->route('allocations.show', $allocation)->with('success', 'อัปเดตจำนวนการผลิตสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['update_error' => 'เกิดข้อผิดพลาดในการอัปเดตจำนวนการผลิต: ' . $e->getMessage()]);
        }
    }
}
