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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code_customer')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('role_id',['1','2','3','4','5','6'])->default(2)->comment('1: Superadmin 2: Admin 3: Courier 4: Customer 5: Driver 6: Directur');
            $table->integer('is_active')->default(1)->comment('0: Nonaktif, 1: Aktif');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('picures')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
