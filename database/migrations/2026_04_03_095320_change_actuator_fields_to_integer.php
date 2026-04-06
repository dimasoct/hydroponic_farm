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
        Schema::table('aktuators', function (Blueprint $table) {
            $table->integer('is_sprinkler_on')->default(0)->change();
            $table->integer('is_fan_on')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktuators', function (Blueprint $table) {
            $table->boolean('is_sprinkler_on')->default(false)->change();
            $table->boolean('is_fan_on')->default(false)->change();
        });
    }
};
