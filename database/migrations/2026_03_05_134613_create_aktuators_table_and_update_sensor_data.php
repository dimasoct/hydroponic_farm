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
        Schema::create('aktuators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_data_id')->nullable()->constrained('sensor_data')->onDelete('cascade');
            $table->boolean('is_sprinkler_on')->default(false);
            $table->boolean('is_fan_on')->default(false);
            $table->timestamps();
        });

        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn(['is_sprinkler_on', 'is_fan_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->boolean('is_sprinkler_on')->default(false);
            $table->boolean('is_fan_on')->default(false);
        });

        Schema::dropIfExists('aktuators');
    }
};
