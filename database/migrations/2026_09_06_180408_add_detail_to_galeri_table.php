<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->string('kategori')->default('Umum')->after('judul');
            $table->string('lokasi')->nullable()->after('deskripsi');
            $table->enum('status', ['draft', 'published'])->default('published')->after('jumlah_peserta');
        });
    }

    public function down(): void
    {
        Schema::table('galeri', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'lokasi', 'status']);
        });
    }
};
