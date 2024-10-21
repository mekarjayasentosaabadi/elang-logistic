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
        Schema::create('history_vehicle', function (Blueprint $table) {
            $table->id();
            $table->integer('vehicle_id');
            $table->integer('user_id');
            $table->string('status');
            $table->integer('outlet_id');
            $table->integer('destination_id');
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_vehicle');
    }
};
