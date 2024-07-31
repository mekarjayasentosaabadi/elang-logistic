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
        Schema::table('traveldocuments', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->integer('status_traveldocument')->default(1)->comment('0: Cancel, 1: Process, 2: On The Way, 3: Transit, 4: Finish');
            $table->timestamp('start')->nullable();
            $table->timestamp('finish_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traveldocuments', function (Blueprint $table) {
            //
        });
    }
};
