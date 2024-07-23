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
        Schema::create('masterprices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlets_id');
            $table->integer('armada')->comment('1: Darat, 2: Laut, 3: Laut')->default(1);
            $table->unsignedBigInteger('destinations_id');
            $table->integer('price')->nullable();
            $table->integer('minweights')->default(10);
            $table->integer('nextweightprices');
            $table->integer('minimumprice');
            $table->integer('estimation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masterprices');
    }
};
