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
        Schema::table('menu_allocations', function (Blueprint $table) {
            Schema::table('menu_allocations', function (Blueprint $table) {
                $table->boolean('is_used')->default(false)->after('updated_at');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_allocations', function (Blueprint $table) {
            Schema::table('menu_allocations', function (Blueprint $table) {
                $table->dropColumn('is_used');
            });
        });
    }
};
