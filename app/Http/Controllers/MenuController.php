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

        $menu = Menu::create($validatedData);

        if ($request->hasFile('menu_image')) {
            $imagePath = $request->file('menu_image')->store('menu_images', 'public');
            $menu->menu_image = $imagePath;
            $menu->save();
        }

        foreach ($validatedData['ingredients'] as $ingredient) {
            $menu->recipes()->create([
                'ingredient_id' => $ingredient['id'],
                'Amount' => $ingredient['amount'],
            ]);
        }

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function show(Menu $menu)
    {
        $menu->load('menuType', 'recipes.ingredient');
        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $menuTypes = MenuType::all();
        $ingredients = Ingredient::all();
        $menu->load('recipes.ingredient');
        return view('menus.edit', compact('menu', 'menuTypes', 'ingredients'));
    }

    public function update(Request $request, Menu $menu)
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

        $menu->update($validatedData);

        if ($request->hasFile('menu_image')) {
            $imagePath = $request->file('menu_image')->store('menu_images', 'public');
            $menu->menu_image = $imagePath;
            $menu->save();
        }

        $menu->recipes()->delete();
        foreach ($validatedData['ingredients'] as $ingredient) {
            $menu->recipes()->create([
                'ingredient_id' => $ingredient['id'],
                'Amount' => $ingredient['amount'],
            ]);
        }

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}