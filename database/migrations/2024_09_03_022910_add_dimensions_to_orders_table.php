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
        Schema::table('orders', function (Blueprint $table) {
            $table->double('panjang_volume')->nullable()->after('volume');
            $table->double('lebar_volume')->nullable()->after('panjang_volume');
            $table->double('tinggi_volume')->nullable()->after('lebar_volume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['panjang_volume', 'lebar_volume', 'tinggi_volume']);
        });
    }
};
