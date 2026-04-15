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
            // Ubah tipe kolom dari boolean ke integer (0, 50, 100)
            $table->integer('is_sprinkler_on')->default(0)->change();
            $table->integer('is_fan_on')->default(0)->change();

            // Durasi aktif dalam detik (dihitung dari nilai: 50->30s, 100->60s)
            $table->integer('sprinkler_duration')->default(0)->after('is_sprinkler_on');
            $table->integer('fan_duration')->default(0)->after('is_fan_on');

            // Waktu aktuator dimatikan (diisi setelah timer selesai dari Pi)
            $table->timestamp('sprinkler_off_at')->nullable()->after('sprinkler_duration');
            $table->timestamp('fan_off_at')->nullable()->after('fan_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktuators', function (Blueprint $table) {
            $table->dropColumn([
                'sprinkler_duration',
                'fan_duration',
                'sprinkler_off_at',
                'fan_off_at',
            ]);
            $table->boolean('is_sprinkler_on')->default(false)->change();
            $table->boolean('is_fan_on')->default(false)->change();
        });
    }
};
