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
        $request->validate([
            'production_date' => 'required|date',
            'production_detail' => 'required|string',
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.quantity' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $insufficientIngredients = $this->checkIngredientsAvailability($request->menus);

            if (!empty($insufficientIngredients)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'วัตถุดิบไม่เพียงพอสำหรับการผลิต',
                    'insufficientIngredients' => $insufficientIngredients
                ], 422);
            }

            $production = Production::create([
                'production_date' => $request->production_date,
                'production_detail' => $request->production_detail,
            ]);

            foreach ($request->menus as $menuData) {
                $menu = Menu::findOrFail($menuData['id']);
                $production->menus()->attach($menu->id, ['quantity' => $menuData['quantity']]);
                $this->updateIngredientStock($menu, $menuData['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'รายการผลิตถูกบันทึกเรียบร้อยแล้ว',
                'redirect' => route('productions.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการบันทึกรายการผลิต: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkIngredientsAvailability($menus)
    {
        $insufficientIngredients = [];

        foreach ($menus as $menuData) {
            $menu = Menu::with('ingredients')->findOrFail($menuData['id']);
            $quantity = $menuData['quantity'];

            foreach ($menu->ingredients as $ingredient) {
                $requiredAmount = $ingredient->pivot->amount * $quantity;
                $availableAmount = $ingredient->stock_quantity;

                if ($availableAmount < $requiredAmount) {
                    if (!isset($insufficientIngredients[$menu->menu_name])) {
                        $insufficientIngredients[$menu->menu_name] = [];
                    }
                    $insufficientIngredients[$menu->menu_name][] = [
                        'name' => $ingredient->ingredient_name,
                        'required' => $requiredAmount,
                        'available' => $availableAmount
                    ];
                }
            }
        }

        return $insufficientIngredients;
    }

    private function updateIngredientStock($menu, $quantity)
    {
        foreach ($menu->ingredients as $ingredient) {
            $usedAmount = $ingredient->pivot->amount * $quantity;
            $ingredient->decrement('stock_quantity', $usedAmount);
        }
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
