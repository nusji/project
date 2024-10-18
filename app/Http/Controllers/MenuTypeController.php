<?php

// app/Http/Controllers/MenuTypeController.php
namespace App\Http\Controllers;

use App\Models\MenuType;
use Illuminate\Http\Request;

class MenuTypeController extends Controller
{
    public function index()
    {
        $menuTypes = MenuType::all();
        return view('menus.menu_types.index', compact('menuTypes'));
    }

    public function create()
    {
        return view('menus.menu_types.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'menu_type_name' => 'required|string|max:255',
            'menu_type_detail' => 'nullable|string',
        ], [
            'menu_type_detail.require' => 'กรุณากรอกรายละเอียดประเภทเมนู',

        ]);
        // ตรวจสอบเฉพาะข้อมูลที่ยังไม่ถูกลบ
        $exists = MenuType::where('menu_type_name', $request->menu_type_name)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['menu_type_name' => 'พบประเภทเมนูนี้ในระบบแล้ว']);
        }
        // ตรวจสอบว่ามีข้อมูลที่ถูกลบแล้วหรือไม่
        $deletedMenuType = MenuType::onlyTrashed()
            ->where('menu_type_name', $request->menu_type_name)
            ->first();
        if ($deletedMenuType) {
            // ถ้าพบข้อมูลที่ถูกลบแล้ว ให้เปิดใช้งานใหม่
            $deletedMenuType->restore();
            $deletedMenuType->update($validatedData);
            return redirect()->route('menu_types.index')->with('success', 'เปิดใช้งานและอัปเดตประเภทเมนูเรียบร้อยแล้ว');
        }
        // ถ้าไม่พบข้อมูลที่ซ้ำ ให้สร้างใหม่
        MenuType::create($validatedData);
        return redirect()->route('menu_types.index')->with('success', 'ประเภทเมนูถูกเพิ่มเรียบร้อยแล้ว');
    }

    public function edit(MenuType $menuType)
    {
        return view('menus.menu_types.edit', compact('menuType'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate(
            [
                'menu_type_name' => 'required|string|max:255',
                'menu_type_detail' => 'nullable|string',
            ],
            [
                'menu_type_detail.required' => 'กรุณากรอกรายละเอียดประเภทเมนู',
            ]
        );

        $menuType = MenuType::findOrFail($id);
        if ($menuType->menu_type_name !== $request->menu_type_name) {
            $exists = MenuType::where('menu_type_name', $request->menu_type_name)
                ->where('id', '!=', $id)
                ->exists();
            if ($exists) {
                return redirect()->back()->withErrors(['menu_type_name' => 'พบประเภทเมนูนี้ในระบบแล้ว']);
            }
            $deletedmenuType = MenuType::onlyTrashed()
                ->where('menu_type_name', $request->menu_type_name)
                ->where('id', '!=', $id)
                ->first();
            if ($deletedmenuType) {
                return redirect()->back()->withErrors(['menu_type_name' => 'มีประเภทเมนูนี้ในระบบที่ถูกลบไปแล้ว กรุณาใช้ชื่ออื่น']);
            }
        }
        $menuType->update($validatedData);
        return redirect()->route('menu_types.index')->with('success', 'อัปเดตประเภทเมนูเรียบร้อยแล้ว');
    }


    public function destroy(MenuType $menuType)
    {
        $menuType->delete();
        return redirect()->route('menu_types.index')->with('success', 'ลบประเภทเมนูเรียบร้อยแล้ว');
    }
}
