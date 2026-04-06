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
        Schema::create('actuators', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_sprinkler_on')->default(false);
            $table->boolean('is_fan_on')->default(false);
            $table->timestamps();
        });

        // Insert a default row representing the current system state
        \Illuminate\Support\Facades\DB::table('actuators')->insert([
            'is_sprinkler_on' => false,
            'is_fan_on' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actuators');
    }
};
