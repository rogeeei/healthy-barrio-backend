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
            $table->id('user_id');
            $table->text('firstname');
            $table->text('middle_name')->nullable();
            $table->text('lastname');
            $table->text('suffix')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->date('birthdate')->nullable();
            $table->string('brgy');
            $table->string('role');
            $table->text('image_path')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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
