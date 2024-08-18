<?php

// app/Http/Controllers/MenuController.php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('menuType')->get();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $menuTypes = MenuTypes::all();
        return view('menus.create', compact('menuTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_detail' => 'nullable|string',
            'menu_type_id' => 'required|exists:menu_types,id',
            'menu_price' => 'required|numeric|min:0',
            'menu_status' => 'required|boolean',
            'menu_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('menu_image')) {
            $image = $request->file('menu_image');
            $filename = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/menu_pictures', $filename);
            $data['menu_image'] = $filename;
        }

        Menu::create($data);
        return redirect()->route('menus.index')->with('success', 'เมนูถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function edit(Menu $menu)
    {
        $menuTypes = MenuTypes::all();
        return view('menus.edit', compact('menu', 'menuTypes'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_detail' => 'nullable|string',
            'menu_type_id' => 'required|exists:menu_types,id',
            'menu_price' => 'required|numeric|min:0',
            'menu_status' => 'required|boolean',
            'menu_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('menu_image')) {
            // Delete old image
            if ($menu->menu_image) {
                Storage::delete('public/menu_pictures/' . $menu->menu_image);
            }

            $image = $request->file('menu_image');
            $filename = Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/menu_pictures', $filename);
            $data['menu_image'] = $filename;
        }

        $menu->update($data);
        return redirect()->route('menus.index')->with('success', 'เมนูถูกแก้ไขเรียบร้อยแล้ว');
    }

    public function destroy(Menu $menu)
    {
        // Delete image if exists
        if ($menu->menu_image) {
            Storage::delete('public/menu_pictures/' . $menu->menu_image);
        }
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'เมนูถูกลบเรียบร้อยแล้ว');
    }
}
