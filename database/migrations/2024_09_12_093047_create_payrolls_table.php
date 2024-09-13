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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained(); // Keep foreign key without onDelete
            $table->decimal('bonus', 10, 2)->default(0); // Any bonuses
            $table->decimal('deductions', 10, 2)->default(0); // Deductions
            $table->decimal('net_salary', 10, 2); // Final net salary after additions and deductions
            $table->date('payment_date'); // Date when the salary is paid
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
