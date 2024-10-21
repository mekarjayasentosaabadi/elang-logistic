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
        Schema::table('detailsurattugas', function (Blueprint $table) {
            //
            $table->dropColumn('traveldocuments_id');
            $table->integer('manifest_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detailsurattugas', function (Blueprint $table) {
            //
        });
    }
};
