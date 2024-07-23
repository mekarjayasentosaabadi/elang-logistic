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
        Schema::table('customer_prices', function (Blueprint $table) {
            $table->integer('minweights')->default(10);
            $table->integer('nextweightprices');
            $table->integer('minimumprice');
            $table->unsignedBigInteger('masterprices_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_prices', function (Blueprint $table) {
            //
        });
    }
};
