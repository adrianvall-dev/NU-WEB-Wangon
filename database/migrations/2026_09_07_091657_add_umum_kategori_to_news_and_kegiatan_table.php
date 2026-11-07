<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE news MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat', 'umum') DEFAULT 'muslimat'");
            DB::statement("ALTER TABLE kegiatan MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat', 'umum') DEFAULT 'muslimat'");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE news MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat') DEFAULT 'muslimat'");
            DB::statement("ALTER TABLE kegiatan MODIFY kategori ENUM('ansor', 'fatayat', 'muslimat') DEFAULT 'muslimat'");
        }
    }
};
