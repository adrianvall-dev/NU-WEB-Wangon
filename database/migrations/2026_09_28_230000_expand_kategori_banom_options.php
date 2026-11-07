<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            // 1. Expand enum on anggota.organisasi
            DB::statement("ALTER TABLE anggota MODIFY organisasi ENUM('ansor', 'fatayat', 'muslimat', 'ipnu', 'ippnu', 'pagar_nusa') NOT NULL");

            // 2. Expand enum on news.kategori
            DB::statement("ALTER TABLE news MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat', 'ipnu', 'ippnu', 'pagar_nusa', 'umum') DEFAULT 'muslimat'");

            // 3. Expand enum on kegiatan.kategori
            DB::statement("ALTER TABLE kegiatan MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat', 'ipnu', 'ippnu', 'pagar_nusa', 'umum') DEFAULT 'muslimat'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE anggota MODIFY organisasi ENUM('ansor', 'fatayat', 'muslimat') NOT NULL");
            DB::statement("ALTER TABLE news MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat', 'umum') DEFAULT 'muslimat'");
            DB::statement("ALTER TABLE kegiatan MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat', 'umum') DEFAULT 'muslimat'");
        }
    }
};
