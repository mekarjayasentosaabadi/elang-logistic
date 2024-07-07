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
        Schema::create('customer_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('outlet_id');
            $table->integer('armada')->default(1)->comment('1 = Darat, 2 = Laut, 3 = Udara');
            $table->integer('destination_id');
            $table->integer('price');
            $table->integer('estimation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_prices');
    }
};
