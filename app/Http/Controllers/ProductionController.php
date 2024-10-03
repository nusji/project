<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Menu;
use App\Models\MenuRecipe;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with('productionDetails.menu')->get();
        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        $menus = Menu::all(); // Assuming you have a Menu model
        return view('productions.create', compact('menus'));
    }

    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งเข้ามา
        $validatedData = $request->validate([
            'production_date' => 'required|date',
            'production_detail' => 'required|string',
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.quantity' => 'required|numeric|min:1',
        ]);

        // ตรวจสอบวัตถุดิบเพียงพอหรือไม่
        $insufficientIngredients = [];

        foreach ($validatedData['menus'] as $menuData) {
            $menu = Menu::findOrFail($menuData['id']); // ดึงข้อมูลเมนู
            $quantityToProduce = $menuData['quantity']; // จำนวนที่ต้องผลิต

            // ดึงวัตถุดิบที่ต้องใช้สำหรับเมนูนี้
            foreach ($menu->ingredients as $ingredient) {
                $totalRequired = $ingredient->pivot->quantity_required * $quantityToProduce; // ปริมาณที่ต้องใช้ทั้งหมด

                // ตรวจสอบวัตถุดิบในสต็อกเพียงพอหรือไม่
                if ($ingredient->ingredient_stock < $totalRequired) {
                    // เพิ่มรายการวัตถุดิบที่ไม่เพียงพอลงในอาร์เรย์
                    $insufficientIngredients[] = [
                        'menu_name' => $menu->menu_name,
                        'ingredient_name' => $ingredient->ingredient_name,
                        'required' => $totalRequired,
                        'available' => $ingredient->ingredient_stock,
                    ];
                }
            }
        }

        // ถ้าวัตถุดิบไม่พอ ส่งกลับไปพร้อมกับแจ้งเตือน SweetAlert
        if (!empty($insufficientIngredients)) {
            return redirect()->back()->with([
                'insufficientIngredients' => $insufficientIngredients, // ส่งข้อมูลเมนูและวัตถุดิบที่ไม่พอ
            ])->withInput();
        }

        // ถ้าวัตถุดิบพอ ให้ทำการบันทึกรายการผลิต
        $production = new Production();
        $production->production_date = $validatedData['production_date'];
        $production->production_detail = $validatedData['production_detail'];
        $production->save();

        // บันทึกรายการเมนูที่ผลิต
        foreach ($validatedData['menus'] as $menuData) {
            $production->menus()->attach($menuData['id'], ['quantity' => $menuData['quantity']]);

            // หักวัตถุดิบจากสต็อก
            $menu = Menu::findOrFail($menuData['id']);
            foreach ($menu->ingredients as $ingredient) {
                $ingredient->ingredient_stock -= $ingredient->pivot->quantity_required * $menuData['quantity'];
                $ingredient->save();
            }
        }

        return redirect()->route('productions.index')->with('success', 'บันทึกรายการผลิตสำเร็จ');
    }

    public function edit(Production $production)
    {
        return view('productions.edit', compact('production'));
    }

    public function update(Request $request, Production $production)
    {
        $production->update($request->all());
        return redirect()->route('productions.index');
    }

    public function show(Production $production)
    {
        return view('productions.show', compact('production'));
    }

    public function destroy(Production $production)
    {
        foreach ($production->productionDetails as $detail) {
            // คืนจำนวนวัตถุดิบเมื่อยกเลิกการผลิต
            foreach ($detail->menu->menuRecipes as $recipe) {
                $ingredient = Ingredient::find($recipe->ingredient_id);
                $ingredient->stock += $recipe->Amount * $detail->quantity;
                $ingredient->save();
            }
        }

        $production->delete();
        return redirect()->route('productions.index');
    }
}
