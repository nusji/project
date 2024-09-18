<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Ingredient;

class ProductionMenu extends Component
{
    public $search = '';
    public $menuList = [];
    public $productionDate;
    public $productionDetail;

    public function addMenu($menuId)
    {
        $menu = Menu::find($menuId);
        $this->menuList[] = ['menu_id' => $menu->id, 'menu_name' => $menu->menu_name, 'quantity' => 1];
    }

    public function removeMenu($index)
    {
        unset($this->menuList[$index]);
        $this->menuList = array_values($this->menuList);
    }

    public function searchMenus()
    {
        return Menu::where('menu_name', 'like', '%' . $this->search . '%')->get();
    }

    public function saveProduction()
    {
        // Logic การบันทึกการผลิต
        // ตัดวัตถุดิบที่เกี่ยวข้องจากเมนูที่ผลิต
        // ลดจำนวนวัตถุดิบในคลังตามเมนูที่ผลิต
    }

    public function render()
    {
        return view('livewire.production-menu', [
            'menus' => $this->searchMenus(),
        ]);
    }
}
