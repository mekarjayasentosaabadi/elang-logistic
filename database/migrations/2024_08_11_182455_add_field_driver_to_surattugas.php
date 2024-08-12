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
        Schema::table('surattugas', function (Blueprint $table) {
            //
            $table->string('driver')->nullable()->after('outlets_id');
            $table->string('vehicle_id')->nullable()->after('driver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surattugas', function (Blueprint $table) {
            //
        });
    }
};
