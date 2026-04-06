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
        // Drop the redundant tables
        Schema::dropIfExists('sensor_data_realtime');
        Schema::dropIfExists('classifications');
        Schema::dropIfExists('actuators');

        // Add corresponding properties to sensor_data
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->string('conditions')->nullable()->after('index_uv');
            $table->boolean('is_sprinkler_on')->default(false)->after('conditions');
            $table->boolean('is_fan_on')->default(false)->after('is_sprinkler_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropColumn(['conditions', 'is_sprinkler_on', 'is_fan_on']);
        });

        Schema::create('actuators', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_sprinkler_on')->default(false);
            $table->boolean('is_fan_on')->default(false);
            $table->timestamps();
        });

        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->string('condition');
            $table->timestamps();
        });

        Schema::create('sensor_data_realtime', function (Blueprint $table) {
            $table->id();
            $table->float('temperature');
            $table->float('humidity');
            $table->float('pressure');
            $table->float('index_uv');
            $table->timestamps();
        });
    }
};
