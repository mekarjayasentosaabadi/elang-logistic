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
        Schema::create('shippingcouriers', function (Blueprint $table) {
            $table->id();
            $table->string('shippingno')->unique();
            $table->unsignedBigInteger('driver_id');
            $table->integer('status_shippingcourier')->comment('0:Cancel, 1: Process, 2: Finish')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippingcouriers');
    }
};
