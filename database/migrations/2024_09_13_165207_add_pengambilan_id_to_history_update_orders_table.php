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
        Schema::table('history_update_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('pengambilan_id')->nullable()->after('destinations_id')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_update_orders', function (Blueprint $table) {
            $table->dropColumn('pengambilan_id');
        });
    }
};
