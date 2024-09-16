<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionMenu;
use App\Models\ProductionIngredient;
use App\Models\MenuIngredient;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\MenuRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with('productionMenus.menu')->paginate(10);
        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        $menus = Menu::all();
        return view('productions.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_code' => 'required|unique:productions',
            'menus' => 'required|array',
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.produced_quantity' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $production = Production::create([
                'order_code' => $request->order_code,
                'production_date' => now(),
            ]);
            
            //สร้าง production_menu และ production_ingredient ตาม request ที่ส่งมา
            foreach ($request->menus as $menu) {
                $producedQuantity = $menu['produced_quantity'];
                $sellingQuantity = $producedQuantity * 10;

                //สร้าง production_menu ใหม่ โดยมี production_id, menu_id, produced_quantity, selling_quantity ที่ส่งมาจาก request
                $productionMenu = ProductionMenu::create([
                    'production_id' => $production->id,
                    'menu_id' => $menu['menu_id'],
                    'produced_quantity' => $producedQuantity,
                    'selling_quantity' => $sellingQuantity,
                ]);

                $menuIngredients = MenuRecipe::where('menu_id', $menu['menu_id'])->get();
                foreach ($menuIngredients as $menuIngredient) {
                    $usedQuantity = $menuIngredient->quantity_per_unit * $producedQuantity;

                    ProductionIngredient::create([
                        'production_menu_id' => $productionMenu->id,
                        'ingredient_id' => $menuIngredient->ingredient_id,
                        'used_quantity' => $usedQuantity,
                    ]);

                    $ingredient = Ingredient::find($menuIngredient->ingredient_id);
                    $ingredient->ingredient_quantity -= $usedQuantity;
                    $ingredient->save();
                }
            }

            DB::commit();
            return redirect()->route('productions.index')->with('success', 'Production created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating production: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $production = Production::with('productionMenus.menu', 'productionMenus.productionIngredients.ingredient')->findOrFail($id);
        return view('productions.show', compact('production'));
    }

    public function edit($id)
    {
        $production = Production::with('productionMenus.menu')->findOrFail($id);
        $menus = Menu::all();
        return view('productions.edit', compact('production', 'menus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_code' => 'required|unique:productions,order_code,' . $id,
            'menus' => 'required|array',
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.produced_quantity' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $production = Production::findOrFail($id);
            $production->update(['order_code' => $request->order_code]);

            // Reverse previous ingredient usage
            foreach ($production->productionMenus as $oldProductionMenu) {
                foreach ($oldProductionMenu->productionIngredients as $oldProductionIngredient) {
                    $ingredient = Ingredient::find($oldProductionIngredient->ingredient_id);
                    $ingredient->stock_quantity += $oldProductionIngredient->used_quantity;
                    $ingredient->save();
                }
            }

            // Delete old production menus and ingredients
            $production->productionMenus()->delete();

            // Create new production menus and ingredients
            foreach ($request->menus as $menu) {
                $producedQuantity = $menu['produced_quantity'];
                $sellingQuantity = $producedQuantity * 10;

                $productionMenu = ProductionMenu::create([
                    'production_id' => $production->id,
                    'menu_id' => $menu['menu_id'],
                    'produced_quantity' => $producedQuantity,
                    'selling_quantity' => $sellingQuantity,
                ]);

                $menuIngredients = MenuRecipe::where('menu_id', $menu['menu_id'])->get();
                foreach ($menuIngredients as $menuIngredient) {
                    $usedQuantity = $menuIngredient->quantity_per_unit * $producedQuantity;

                    ProductionIngredient::create([
                        'production_menu_id' => $productionMenu->id,
                        'ingredient_id' => $menuIngredient->ingredient_id,
                        'used_quantity' => $usedQuantity,
                    ]);

                    $ingredient = Ingredient::find($menuIngredient->ingredient_id);
                    $ingredient->stock_quantity -= $usedQuantity;
                    $ingredient->save();
                }
            }

            DB::commit();
            return redirect()->route('productions.index')->with('success', 'Production updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating production: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $production = Production::findOrFail($id);

            foreach ($production->productionMenus as $productionMenu) {
                foreach ($productionMenu->productionIngredients as $productionIngredient) {
                    $ingredient = Ingredient::find($productionIngredient->ingredient_id);
                    $ingredient->stock_quantity += $productionIngredient->used_quantity;
                    $ingredient->save();
                }
            }

            $production->delete();
            DB::commit();
            return redirect()->route('productions.index')->with('success', 'Production canceled and ingredients restored!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error canceling production: ' . $e->getMessage());
        }
    }
}
