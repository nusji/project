<?php

// app/Livewire/SearchMenu.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu;

class SearchMenu extends Component
{
    public $search = '';
    public $selectedMenus = [];

    public function render()
    {
        $menus = Menu::where('menu_name', 'like', '%' . $this->search . '%')->get();
        return view('livewire.search-menu', ['menus' => $menus]);
    }

    public function addMenu($menuId)
    {
        $menu = Menu::find($menuId);

        // เพิ่มเมนูที่เลือกลงในรายการ
        $this->selectedMenus[] = [
            'id' => $menu->id,
            'name' => $menu->name,
            'quantity' => 1,
            'quantity_sales' => 10, // ตัวอย่าง: 1 กิโล = 10 ทัพพี
        ];
    }
}
