<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('citizen_history', function (Blueprint $table) {
            $table->unsignedBigInteger('diagnostic_id')->nullable()->change(); // Make diagnostic_id nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citizen_history', function (Blueprint $table) {
            $table->unsignedBigInteger('diagnostic_id')->nullable(false)->change(); // Revert to not nullable
        });
    }
};
