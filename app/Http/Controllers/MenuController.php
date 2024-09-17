<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuType;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('menuType')->paginate(10);
        return view('menus.index', compact('menus'));
    }
    public function create()
    {
        $menuTypes = MenuType::all();
        $ingredients = Ingredient::all();
        return view('menus.create', compact('menuTypes', 'ingredients'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_detail' => 'nullable|string',
            'menu_type_id' => 'required|exists:menu_types,id',
            'menu_price' => 'required|numeric|min:0',
            'menu_status' => 'required|boolean',
            'menu_image' => 'nullable|image|max:2048',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:0',
        ]);

        // Check if a menu with the same name exists and is not soft-deleted
        $existingMenu = Menu::where('menu_name', $validatedData['menu_name'])
            ->whereNull('deleted_at')
            ->first();

        if ($existingMenu) {
            // If a menu exists and is soft-deleted, reactivate it
            if ($existingMenu->deleted_at) {
                $existingMenu->deleted_at = null; // Reactivate the menu
                $existingMenu->save();
            }

            // Return success message for reactivated menu
            return redirect()->route('menus.index')->with('success', 'เมนูที่มีชื่อเดียวกันได้ถูกเปิดใช้งานใหม่แล้ว');
        }

        // If no existing menu, create a new menu
        $menu = Menu::create($validatedData);

        // Handle image upload
        if ($request->hasFile('menu_image')) {
            $imagePath = $request->file('menu_image')->store('menu_images', 'public');
            $menu->menu_image = $imagePath;
            $menu->save();
        }

        // Store ingredients in the menu_recipes table
        foreach ($validatedData['ingredients'] as $ingredient) {
            $menu->recipes()->create([
                'ingredient_id' => $ingredient['id'],
                'amount' => $ingredient['amount'],
            ]);
        }

        return redirect()->route('menus.index')->with('success', 'เมนูถูกสร้างเรียบร้อยแล้ว');
    }
    public function show(Menu $menu)
    {
        $menu->load('menuType', 'recipes.ingredient');
        return view('menus.show', compact('menu'));
    }
    public function edit($id)
    {
        $menu = Menu::with('recipes')->findOrFail($id);
        $ingredients = Ingredient::all(); // Retrieve all ingredients for the select box
        $menuTypes = menuType::all();
        return view('menus.edit', compact('menu', 'ingredients', 'menuTypes'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_detail' => 'nullable|string',
            'menu_type_id' => 'required|exists:menu_types,id',
            'menu_price' => 'required|numeric|min:0',
            'menu_status' => 'required|boolean',
            'menu_image' => 'nullable|image|max:2048',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:0',
        ]);

        $menu = Menu::findOrFail($id);

        // Update menu details
        $menu->update([
            'menu_name' => $validatedData['menu_name'],
            'menu_detail' => $validatedData['menu_detail'],
            'menu_type_id' => $validatedData['menu_type_id'],
            'menu_price' => $validatedData['menu_price'],
            'menu_status' => $validatedData['menu_status'],
        ]);

        // Handle image upload
        if ($request->hasFile('menu_image')) {
            $imagePath = $request->file('menu_image')->store('menu_images', 'public');
            $menu->menu_image = $imagePath;
            $menu->save();
        }

        // Update ingredients
        $menu->recipes()->delete(); // Remove existing ingredients
        foreach ($validatedData['ingredients'] as $ingredient) {
            $menu->recipes()->create([
                'ingredient_id' => $ingredient['id'],
                'amount' => $ingredient['amount'],
            ]);
        }

        return redirect()->route('menus.index')->with('success', 'เมนูถูกอัปเดตเรียบร้อยแล้ว');
    }
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        // Soft delete the menu
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'เมนูถูกลบเรียบร้อยแล้ว');
    }
}
