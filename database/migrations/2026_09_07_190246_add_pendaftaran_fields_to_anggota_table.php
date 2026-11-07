<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('nama');
            $table->enum('status_pendaftaran', ['pending', 'approved', 'rejected'])->default('approved')->after('status');
            $table->string('nomor_anggota')->nullable()->unique()->after('status_pendaftaran');
            $table->string('kode_pendaftaran')->nullable()->unique()->after('nomor_anggota');
            $table->string('kode_verifikasi')->nullable()->unique()->after('kode_pendaftaran');
            $table->text('alasan_penolakan')->nullable()->after('kode_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'status_pendaftaran',
                'nomor_anggota',
                'kode_pendaftaran',
                'kode_verifikasi',
                'alasan_penolakan',
            ]);
        });
    }
};
