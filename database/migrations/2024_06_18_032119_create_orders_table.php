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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('outlet_id')->nullable();
            $table->integer('armada')->default(1)->comment('1 = Darat, 2 = Laut, 3 = Udara');
            $table->integer('destination_id')->nullable();
            $table->string('receiver')->nullable();
            $table->text('address')->nullable();
            $table->integer('price')->nullable();
            $table->integer('estimation')->nullable();
            $table->integer('status')->default(1)->comment('1 = Pending, 2 = Process, 3 = Done,4 Dibatalkan')->nullable();
            $table->string('status_awb')->nullable();
            $table->integer('payment')->default(1)->comment('1 = Cash, 2 = Transfer')->nullable();
            $table->integer('koli')->nullable();
            $table->double('weight')->nullable();
            $table->double('volume')->nullable();
            $table->string('awb', 100)->nullable();
            $table->integer('service')->default(1)->comment('1 = document, 2 = package')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('receiver_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
