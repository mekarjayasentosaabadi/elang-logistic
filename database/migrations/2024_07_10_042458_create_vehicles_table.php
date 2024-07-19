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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('police_no');
            $table->enum('type', ['1','2','3'])->comment('1: Truck Container, 2: Truck BOX, 3: Pickup');
            $table->string('no_stnk');
            $table->integer('is_active')->default(1)->comment('0: Nonaktif, 1: Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};