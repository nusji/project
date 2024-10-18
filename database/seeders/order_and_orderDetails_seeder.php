<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class order_and_orderDetails_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $orders = [];
        $orderDetails = [];

        // กำหนดไอดีพนักงานที่ต้องการใช้
        $employeeIds = [1, 3, 6, 10, 11];

        // สร้างข้อมูลสำหรับตาราง 'orders' จำนวน 200 รายการ โดยเริ่มที่ id = 3
        for ($i = 3; $i <= 202; $i++) {
            $orders[] = [
                'id' => $i,
                'order_date' => Carbon::now()->subDays(rand(0, 365)),
                'order_detail' => 'รายละเอียดคำสั่งซื้อ ' . $i,
                'order_receipt' => 'ใบเสร็จ ' . $i,
                'employee_id' => $employeeIds[array_rand($employeeIds)], // เลือกไอดีจากไอดีที่กำหนด
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // สำหรับแต่ละ order สร้าง order_details จำนวนสุ่ม 1 ถึง 5 รายการ
            $numberOfDetails = rand(1, 5);
            for ($j = 0; $j < $numberOfDetails; $j++) {
                $orderDetails[] = [
                    'order_id' => $i, // อ้างอิง order_id ของ order ที่สร้าง
                    'ingredient_id' => rand(58, 122), // รหัสวัตถุดิบตั้งแต่ 58 ถึง 122
                    'quantity' => rand(1, 100),
                    'price' => rand(100, 1000) / 10, // ราคาแบบทศนิยม
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // แทรกข้อมูลลงในตาราง 'orders' และ 'order_details'
        DB::table('orders')->insert($orders);
        DB::table('order_details')->insert($orderDetails);
    
    }
}
