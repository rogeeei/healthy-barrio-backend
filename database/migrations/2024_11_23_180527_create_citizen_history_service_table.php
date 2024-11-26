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
        Schema::create('citizen_history_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('citizen_history_id'); // Reference to citizen_history table
            $table->unsignedBigInteger('service_id'); // Reference to services table
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('citizen_history_id')
                ->references('citizen_history_id') // Match the existing primary key
                ->on('citizen_history') // Match the existing table name
                ->onDelete('cascade');

            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citizen_history_service');
    }
};
