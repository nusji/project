<?php

// app/Http/Controllers/MenuTypeController.php
namespace App\Http\Controllers;

use App\Models\MenuTypes;
use Illuminate\Http\Request;

class MenuTypeController extends Controller
{
    public function index()
    {
        $menuTypes = MenuTypes::all();
        return view('menus.menu_types.index', compact('menuTypes'));
    }

    public function create()
    {
        return view('menus.menu_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_type_name' => 'required|string|max:255|unique:menu_types',
            'menu_type_detail' => 'nullable|string',
        ],[
            'menu_type_name.unique'=> 'พบวัตถุดิบนี้ในระบบแล้ว',
            'menu_type_detail.require'=> 'กรุณากรอกรายละเอียดประเภทเมนู',
        ]);

        MenuTypes::create($request->all());
        return redirect()->route('menu_types.index');
    }

    public function edit(MenuTypes $menuType)
    {
        return view('menus.menu_types.edit', compact('menuType'));
    }

    public function update(Request $request, MenuTypes $menuType)
    {
        $request->validate([
            'menu_type_name' => 'required|string|max:255|unique:menu_types,menu_type_name,',
            'menu_type_detail' => 'nullable|string',
        ],
        [
            'menu_type_name.unique'=> 'พบประเภทเมนูนี้ในระบบแล้ว',
            'menu_type_detail.require'=> 'กรุณากรอกรายละเอียดประเภทเมนู',
        ]);

        $menuType->update($request->all());
        return redirect()->route('menu_types.index');
    }

    public function destroy(MenuTypes $menuType)
    {
        $menuType->delete();
        return redirect()->route('menu_types.index');
    }
}
