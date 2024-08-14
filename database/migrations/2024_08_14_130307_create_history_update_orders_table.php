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
        Schema::create('history_update_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('numberorders', 10)->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->unsignedBigInteger('destinations_id');
            $table->integer('koli')->nullable();
            $table->double('weight')->nullable();
            $table->double('volume')->nullable();
            $table->double('price')->nullable();
            $table->text('content')->nullable();
            $table->text('penerima')->nullable();
            $table->integer('armada')->default(1)->comment('1 = Darat, 2 = Laut, 3 = Udara');
            $table->text('address')->nullable();
            $table->integer('estimation')->nullable();
            $table->integer('status_orders')->default(1)->comment('1 = Pending, 2 = Process, 3 = Done,4 Dibatalkan')->nullable();
            $table->integer('payment_method')->default(1)->comment('1 = Tagih Tujuan, 2 = Tagih Pada Pengirim, 3 = Tunai')->nullable();
            $table->string('status_awb')->nullable();
            $table->integer('service')->default(1)->comment('1 = document, 2 = package')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('photos')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_update_orders');
    }
};
