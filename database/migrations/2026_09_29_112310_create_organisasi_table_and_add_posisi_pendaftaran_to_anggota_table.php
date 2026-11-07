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
        if (! Schema::hasTable('organisasi')) {
            Schema::create('organisasi', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('slug')->unique();
                $table->string('singkatan')->nullable();
                $table->text('deskripsi')->nullable();
                $table->string('logo')->nullable();
                $table->text('visi')->nullable();
                $table->json('misi')->nullable();
                $table->unsignedBigInteger('ketua_id')->nullable();
                $table->unsignedBigInteger('wakil_ketua_id')->nullable();
                $table->timestamps();

                $table->foreign('ketua_id')->references('id')->on('anggota')->nullOnDelete();
                $table->foreign('wakil_ketua_id')->references('id')->on('anggota')->nullOnDelete();
            });
        }

        if (Schema::hasTable('anggota') && ! Schema::hasColumn('anggota', 'posisi_pendaftaran')) {
            Schema::table('anggota', function (Blueprint $table) {
                $table->string('posisi_pendaftaran')->default('anggota')->after('status_pendaftaran');
            });
        }

        // Inisialisasi data organisasi / banom existing
        $existingBanoms = [
            [
                'slug' => 'ansor',
                'nama' => 'Gerakan Pemuda Ansor',
                'singkatan' => 'GP Ansor',
                'deskripsi' => 'Badan Otonom NU yang membina pemuda dan Barisan Ansor Serbaguna (Banser) se-Kecamatan Wangon.',
            ],
            [
                'slug' => 'fatayat',
                'nama' => 'Fatayat Nahdlatul Ulama',
                'singkatan' => 'Fatayat NU',
                'deskripsi' => 'Badan Otonom NU untuk kalangan perempuan muda (pemudi) dalam mengemban amanah keagamaan dan sosial.',
            ],
            [
                'slug' => 'muslimat',
                'nama' => 'Muslimat Nahdlatul Ulama',
                'singkatan' => 'Muslimat NU',
                'deskripsi' => 'Badan Otonom NU untuk kalangan kaum ibu dan perempuan Nahdliyin yang berkhidmat pada umat.',
            ],
            [
                'slug' => 'ipnu',
                'nama' => 'Ikatan Pelajar Nahdlatul Ulama',
                'singkatan' => 'IPNU',
                'deskripsi' => 'Badan Otonom NU wadah kaderisasi, intelektual, dan keterpelajaran generasi muda putra.',
            ],
            [
                'slug' => 'ippnu',
                'nama' => 'Ikatan Pelajar Putri Nahdlatul Ulama',
                'singkatan' => 'IPPNU',
                'deskripsi' => 'Badan Otonom NU wadah keterpelajaran, kaderisasi kepemimpinan, dan ukhuwah pelajar putri.',
            ],
            [
                'slug' => 'pagar_nusa',
                'nama' => 'Pencak Silat Nahdlatul Ulama Pagar Nusa',
                'singkatan' => 'Pagar Nusa',
                'deskripsi' => 'Badan Otonom NU pelestari dan pembina seni bela diri pencak silat warisan para kiai.',
            ],
        ];

        foreach ($existingBanoms as $banom) {
            $now = now();
            // Cari apakah ada anggota aktif dengan jabatan 'ketua' untuk organisasi ini
            $ketua = DB::table('anggota')
                ->where('organisasi', $banom['slug'])
                ->where('status', 'aktif')
                ->where(function ($q) {
                    $q->where('jabatan', 'ketua')
                        ->orWhere('jabatan', 'Ketua')
                        ->orWhere('jabatan', 'like', 'Ketua%');
                })
                ->value('id');

            $exists = DB::table('organisasi')->where('slug', $banom['slug'])->exists();
            if (! $exists) {
                DB::table('organisasi')->insert([
                    'nama' => $banom['nama'],
                    'slug' => $banom['slug'],
                    'singkatan' => $banom['singkatan'],
                    'deskripsi' => $banom['deskripsi'],
                    'ketua_id' => $ketua,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('anggota') && Schema::hasColumn('anggota', 'posisi_pendaftaran')) {
            Schema::table('anggota', function (Blueprint $table) {
                $table->dropColumn('posisi_pendaftaran');
            });
        }

        Schema::dropIfExists('organisasi');
    }
};
