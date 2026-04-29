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
        Schema::table('announcements', function (Blueprint $col) {
            $col->string('judul_lulus')->default('Selamat!')->after('status');
            $col->text('pesan_lulus')->nullable()->after('judul_lulus');
            $col->string('judul_tidak_lulus')->default('Informasi Hasil')->after('pesan_lulus');
            $col->text('pesan_tidak_lulus')->nullable()->after('judul_tidak_lulus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $col) {
            $col->dropColumn(['judul_lulus', 'pesan_lulus', 'judul_tidak_lulus', 'pesan_tidak_lulus']);
        });
    }
};
