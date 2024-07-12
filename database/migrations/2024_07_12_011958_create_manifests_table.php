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
        Schema::create('manifests', function (Blueprint $table) {
            $table->id();
            $table->string('manifestno', 10)->unique();
            $table->unsignedBigInteger('orders_id');
            $table->unsignedBigInteger('destination_from_id');
            $table->unsignedBigInteger('destination_to_id');
            $table->unsignedBigInteger('outlets_id');
            $table->timestamp('receive_date_time')->nullable();
            $table->string('carier');
            $table->integer('commodity')->comment('1: LV, 2: HV, 3: FE, 4: MIX')->nullable();
            $table->string('flight_no')->nullable();
            $table->string('no_bags')->nullable();
            $table->string('flight_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifests');
    }
};
