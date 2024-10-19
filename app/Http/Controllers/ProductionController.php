<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Menu;
use App\Models\MenuType;
use App\Models\MenuRecipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with('productionDetails.menu')
            ->orderBy('id', 'desc')
            ->paginate(20);
        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        $menus = Menu::all(); // Assuming you have a Menu model
        $menuCategories = MenuType::all(); // MenuCategory เป็นโมเดลของตารางประเภทเมนู
        return view('productions.create', compact('menus', 'menuCategories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'production_date' => 'required|date',
                'production_detail' => 'nullable|string',
                'menus' => 'required|array',
                'menus.*.id' => 'required|exists:menus,id',
                'menus.*.quantity' => 'required|numeric|min:1',
            ],
            [
                'production_date.required' => 'กรุณาเลือกวันที่ผลิต',
                'production_date.date' => 'รูปแบบวันที่ผิดพลาด',
                'menus.required' => 'กรุณาเลือกเมนูที่ต้องการผลิต',
                'menus.*.id.required' => 'เมนูไม่ถูกต้อง',
                'menus.*.id.exists' => 'เมนูไม่ถูกต้อง',
                'menus.*.quantity.required' => 'กรุณากรอกจำนวนที่ต้องการผลิต',
                'menus.*.quantity.numeric' => 'จำนวนต้องเป็นตัวเลข',
                'menus.*.quantity.min' => 'จำนวนต้องมากกว่า 0',
            ]
        );


        
        DB::beginTransaction();

        try {
            $insufficientIngredients = [];

            foreach ($validatedData['menus'] as $menuData) {
                $menu = Menu::findOrFail($menuData['id']);
                $quantityToProduce = $menuData['quantity'];

                // ตรวจสอบวัตถุดิบในสูตรของเมนู (menu_recipes)
                foreach ($menu->recipes as $recipe) {
                    $ingredient = $recipe->ingredient;
                    $totalRequired = $recipe->amount * $quantityToProduce;

                    if ($ingredient->ingredient_stock < $totalRequired) {
                        $insufficientIngredients[] = [
                            'menu_name' => $menu->menu_name,
                            'ingredient_name' => $ingredient->ingredient_name,
                            'required' => $totalRequired,
                            'available' => $ingredient->ingredient_stock,
                            'unit' => $ingredient->ingredient_unit,
                        ];
                    }
                }
            }

            // ถ้าวัตถุดิบไม่เพียงพอ ให้ยกเลิกการทำงาน
            if (!empty($insufficientIngredients)) {
                DB::rollBack();
                return redirect()->back()->with([
                    'insufficientIngredients' => $insufficientIngredients,
                ])->withInput();
            }

            // บันทึกการผลิต
            $production = new Production();
            $production->production_date = $validatedData['production_date'];
            $production->production_detail = $validatedData['production_detail'];
            $production->save();

            foreach ($validatedData['menus'] as $menuData) {
                $production->menus()->attach($menuData['id'], [
                    'quantity' => $menuData['quantity'],
                    'remaining_amount' => $menuData['quantity'], // จำนวนที่เหลือ
                ]);

                // หักลบสต็อกวัตถุดิบ
                foreach ($menu->recipes as $recipe) {
                    $ingredient = $recipe->ingredient;
                    $deductAmount = $recipe->amount * $menuData['quantity'];
                    $ingredient->decrement('ingredient_stock', $deductAmount);
                }
            }

            DB::commit();
            return redirect()->route('productions.index')->with('success', 'บันทึกรายการผลิตสำเร็จ');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Production store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกรายการผลิต: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Production $production)
    {
        // Load the related production details and menus using eager loading
        $production->load('productionDetails.menu');

        // Pass the $production object to the view
        return view('productions.show', compact('production'));
    }

    public function destroy($id)
    {
        $production = Production::find($id);

        if (!$production) {
            return redirect()->route('productions.index')->with('error', 'ไม่พบรายการผลิต');
        }

        DB::beginTransaction();

        try {
            // คืนค่าวัตถุดิบ
            foreach ($production->menus as $menu) {
                foreach ($menu->recipes as $recipe) {
                    $ingredient = $recipe->ingredient;
                    $returnAmount = $recipe->amount * $menu->pivot->quantity;
                    $ingredient->increment('ingredient_stock', $returnAmount);
                }
            }

            $production->delete();

            DB::commit();
            return redirect()->route('productions.index')->with('success', 'ยกเลิกรายการผลิตและคืนค่าวัตถุดิบเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Production destroy error: ' . $e->getMessage());
            return redirect()->route('productions.index')->with('error', 'เกิดข้อผิดพลาดในการยกเลิกรายการผลิต: ' . $e->getMessage());
        }
    }
}
