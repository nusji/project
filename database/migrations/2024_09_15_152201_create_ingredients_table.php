<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ingredient_types', function (Blueprint $table) {
            $table->id();
            $table->string('ingredient_type_name');
            $table->string('ingredient_type_detail')->nullable();
            $table->timestamps();
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id(); // เปลี่ยนชื่อคอลัมน์ให้ชัดเจน
            $table->string('ingredient_name');
            $table->string('ingredient_detail')->nullable();
            $table->string('ingredient_unit');
            $table->double('ingredient_stock', 10, 2)->default(0.00);
            $table->integer('minimum_quantity')->default(0); // เพิ่มคอลัมน์เก็บค่าขั้นต่ำของวัตถุดิบ
            // คอลัมน์นี้ใช้สำหรับอ้างอิงไปยังตาราง ingredients_type
            $table->foreignId('ingredient_type_id')->constrained('ingredient_types')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_types');
        Schema::dropIfExists('ingredients');
    }
};
