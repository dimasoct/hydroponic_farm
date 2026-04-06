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
        Schema::create('stomata_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('stomata_condition');        // Klasifikasi kondisi stomata
            $table->boolean('is_actuator_on')->default(false); // Aktuator on/off
            $table->string('image_path')->nullable();   // Path gambar stomata
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stomata_classifications');
    }
};
