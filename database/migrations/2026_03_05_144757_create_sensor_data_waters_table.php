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
        Schema::create('sensor_data_waters', function (Blueprint $table) {
            $table->id();
            $table->float('ph')->nullable();
            $table->float('tds')->nullable();
            $table->float('do')->nullable();
            $table->string('early_warning')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data_waters');
    }
};
