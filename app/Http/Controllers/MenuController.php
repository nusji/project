<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuType;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // ดึงข้อมูลประเภทของเมนูสำหรับ chart
        $menuTypes = Menu::select('menu_type_id')
            ->selectRaw('count(*) as count')
            ->groupBy('menu_type_id')
            ->with('menuType') // เพิ่ม eager loading สำหรับ ingredient type
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->menuType->menu_type_name, // สมมติว่า ingredient type มี column 'ingredient_type_name'
                    'count' => $item->count
                ];
            });
        // รับค่าการค้นหาจาก request
        $search = $request->input('search');

        // ดึงเมนู โดยค้นหาตามชื่อหากมีการส่งค่าการค้นหา
        $menus = Menu::with('menuType')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%"); // ค้นหาจากชื่อเมนู
            })
            ->paginate(20);

        return view('menus.index', compact('menus', 'search','menuTypes'));
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
            'menu_taste' => 'nullable|string',
            'menu_image' => 'nullable|image',
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
        $menu->load('menuType', 'recipes.ingredient')->withTrashed();
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
            'menu_taste' => 'nullable|string',
            'menu_price' => 'required|numeric|min:0',
            'menu_status' => 'required|boolean',
            'menu_image' => 'nullable|image',
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
            'menu_taste' => $validatedData['menu_taste'],
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
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'เมนูถูกลบเรียบร้อยแล้ว');
    }


    public function getMenuDetails(Request $request)
    {
        $menuIds = $request->menu_ids;
        $menus = Menu::whereIn('id', $menuIds)->get();

        $html = view('productions.partials.menu-details', compact('menus'))->render();

        return response()->json(['html' => $html]);
    }
}
