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
        Schema::table('masterprices', function (Blueprint $table) {
            $table->unsignedBigInteger('origin_id')->nullable()->after('armada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masterprices', function (Blueprint $table) {
            $table->dropColumn('origin_id');
        });
    }
};