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
            $table->unsignedBigInteger('driver_id')->nullable()->after('outlets_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surattugas', function (Blueprint $table) {
            $table->dropColumn('driver_id');
        });
    }

};
