<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ingredient;

class OrderSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // สร้างคำสั่งซื้อ
            $order1 = Order::create([
                'order_date' => now(),
                'order_detail' => 'สั่งซื้อวัตถุดิบสำหรับเมนูแกงเขียวหวาน',
                'order_receipt' => 'receipt_001.pdf',
                'employee_id' => 1, // สมมติว่ามีพนักงานที่มี id = 1 อยู่แล้ว
            ]);

            $order2 = Order::create([
                'order_date' => now(),
                'order_detail' => 'สั่งซื้อวัตถุดิบสำหรับเมนูแกงเผ็ด',
                'order_receipt' => 'receipt_002.pdf',
                'employee_id' => 3, // สมมติว่ามีพนักงานที่มี id = 3 อยู่แล้ว
            ]);

            // สร้างรายละเอียดการสั่งซื้อ
            OrderDetail::create([
                'order_id' => $order1->id, // ใช้ ID ที่สร้างใหม่
                'ingredient_id' => 52, // กระเทียม
                'quantity' => 12.00,
                'price' => 120.00,
            ]);

            OrderDetail::create([
                'order_id' => $order1->id,
                'ingredient_id' => 57, // พริกแกงเขียวหวาน
                'quantity' => 12.00,
                'price' => 200.00,
            ]);

            OrderDetail::create([
                'order_id' => $order2->id, // ใช้ ID ที่สร้างใหม่
                'ingredient_id' => 61, // พริกแกงเผ็ด
                'quantity' => 12.00,
                'price' => 150.00,
            ]);

            OrderDetail::create([
                'order_id' => $order2->id,
                'ingredient_id' => 48, // เนื้อหมู
                'quantity' => 12.00,
                'price' => 300.00,
            ]);

            // อัปเดตสต็อค
            $this->updateStock(52, 12.00); // กระเทียม ลดลง 2.00 กิโลกรัม
            $this->updateStock(57, 12.00); // พริกแกงเขียวหวาน ลดลง 1.00 กิโลกรัม
            $this->updateStock(61, 12.00); // พริกแกงเผ็ด ลดลง 1.00 กิโลกรัม
            $this->updateStock(48, 12.00); // เนื้อหมู ลดลง 3.00 กิโลกรัม
        });
    }

    /**
     * อัปเดตสต็อคของวัตถุดิบ
     *
     * @param int $ingredientId
     * @param float $quantity
     * @return void
     */
    private function updateStock(int $ingredientId, float $quantity)
    {
        $ingredient = Ingredient::find($ingredientId);
        
        if ($ingredient) {
            $ingredient->ingredient_stock += $quantity; // ปรับปรุงสต็อค
            $ingredient->save();
        }
    }
}
