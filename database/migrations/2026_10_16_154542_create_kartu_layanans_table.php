<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kartu_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 100);
            $table->string('deskripsi', 255);
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed 3 initial service documentation cards matching the current live website
        DB::table('kartu_layanans')->insert([
            [
                'judul' => 'Jam Operasional',
                'deskripsi' => 'Senin - Ahad, 08.00 - 16.00',
                'urutan' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Layanan KARTANU',
                'deskripsi' => 'Setiap Hari Kerja',
                'urutan' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Ruang Rapat Banom',
                'deskripsi' => 'Aula Lt. 2',
                'urutan' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_layanans');
    }
};
