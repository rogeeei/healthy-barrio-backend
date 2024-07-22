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
        Schema::create('citizen_history', function (Blueprint $table) {
            $table->id('citizen_history_id');
            $table->unsignedBigInteger('citizen_id');
            $table->unsignedBigInteger('diagnostic_id');
            $table->date('date');
            $table->timestamps();

            $table->foreign('diagnostic_id')->references('diagnostic_id')->on('diagnostic');
            $table->foreign('citizen_id')->references('citizen_id')->on('citizen_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizen_history');
    }
};
