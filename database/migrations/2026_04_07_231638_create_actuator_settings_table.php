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
        Schema::create('actuator_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('sprinkler_50_duration')->default(30);
            $table->integer('sprinkler_100_duration')->default(60);
            $table->integer('fan_50_duration')->default(30);
            $table->integer('fan_100_duration')->default(60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actuator_settings');
    }
};
