<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Production;
use App\Models\ProductionDetail;
use App\Models\Ingredient;
use App\Models\MenuRecipe;
use Illuminate\Support\Facades\DB;

class ProductionComponent extends Component
{
    public $productionDate;
    public $productionDetail;
    public $search = '';
    public $menuList = [];
    public $menus = [];

    public function render()
    {
        // ค้นหาเมนูตามการพิมพ์
        $this->menus = Menu::where('menu_name', 'like', '%' . $this->search . '%')->get();
        return view('livewire.production-component');
    }

    public function addMenu($menuId)
    {
        $menu = Menu::find($menuId);
        if ($menu) {
            $this->menuList[] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'quantity' => 1, // ค่าเริ่มต้นคือ 1 กิโล
                'quantity_sales' => 10, // 1 กิโลกรัม = 10 ทัพพี
            ];
        }
    }

    public function removeMenu($index)
    {
        unset($this->menuList[$index]);
        $this->menuList = array_values($this->menuList); // จัดเรียง index ใหม่
    }

    public function saveProduction()
    {
        $this->validate([
            'productionDate' => 'required|date',
            'productionDetail' => 'required|string',
            'menuList' => 'required|array|min:1',
        ]);

        DB::transaction(function () {
            // บันทึก production
            $production = Production::create([
                'production_date' => $this->productionDate,
                'production_detail' => $this->productionDetail,
            ]);

            // บันทึกรายละเอียดการผลิตและสั่งตัดสต็อกวัตถุดิบ
            foreach ($this->menuList as $menu) {
                // คำนวณจำนวนทัพพี
                $menu['quantity_sales'] = $menu['quantity'] * 10;

                // บันทึกรายละเอียดการผลิต
                ProductionDetail::create([
                    'production_id' => $production->id,
                    'menu_id' => $menu['id'],
                    'quantity' => $menu['quantity'],
                    'quantity_sales' => $menu['quantity_sales'],
                ]);

                // สั่งตัดสต็อกวัตถุดิบ
                $this->processIngredients($menu['id'], $menu['quantity']);
            }
        });

        session()->flash('success', 'บันทึกการผลิตเรียบร้อยแล้ว');
        return redirect()->route('productions.index');
    }

    private function processIngredients($menuId, $quantity)
    {
        $recipes = MenuRecipe::where('menu_id', $menuId)->get();

        foreach ($recipes as $recipe) {
            $requiredAmount = $recipe->amount * $quantity;
            $ingredient = Ingredient::find($recipe->ingredient_id);

            if ($ingredient->ingredient_stock < $requiredAmount) {
                session()->flash('error', 'วัตถุดิบ ' . $ingredient->ingredient_name . ' ไม่เพียงพอ');
                throw new \Exception('วัตถุดิบไม่เพียงพอ');
            }

            // อัปเดตจำนวนวัตถุดิบ
            $ingredient->update([
                'ingredient_stock' => $ingredient->ingredient_stock - $requiredAmount
            ]);
        }
    }
}
