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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // ชื่อจริง
            $table->string('last_name'); // นามสกุล
            $table->string('username')->unique();
            $table->string('password');
            $table->string('role')->default('employee');
            $table->string('id_card_number')->unique(); // เลขบัตรประจำตัวประชาชน
            $table->string('phone_number'); // เบอร์โทรศัพท์
            $table->string('employment_status'); // สถานะการจ้างงาน เช่น ประจำ, ชั่วคราว
            $table->date('start_date')->nullable(); // วันที่เริ่มงาน
            
            $table->text('address')->nullable(); // ที่อยู่ (พนักงานกรอกตอนแรกเข้า)
            $table->date('date_of_birth')->nullable(); // วันเดือนปีเกิด (พนักงานกรอกตอนแรกเข้า)
            $table->string('profile_picture')->nullable(); // รูปถ่าย (พนักงานกรอกตอนแรกเข้า)
            $table->text('previous_experience')->nullable(); // ประวัติการทำงานก่อนหน้า (พนักงานกรอกตอนแรกเข้า)
            $table->string('bank_account')->nullable(); // บัญชีธนาคาร (พนักงานกรอกตอนแรกเข้า)
            $table->string('bank_account_number')->nullable(); // เลขที่บัญชีธนาคาร (พนักงานกรอกตอนแรกเข้า)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
