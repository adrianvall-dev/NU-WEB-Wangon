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
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->string('label_section')->default('PUSAT LAYANAN & SEKRETARIAT')->after('tahun_berdiri');
            $table->string('nama_kantor')->default('Kantor MWC Nahdlatul Ulama Wangon')->after('label_section');
            $table->text('alamat')->nullable()->after('nama_kantor');
            $table->string('desa')->nullable()->default('Wangon')->after('alamat');
            $table->string('kecamatan')->nullable()->default('Wangon')->after('desa');
            $table->string('kabupaten')->nullable()->default('Banyumas')->after('kecamatan');
            $table->string('provinsi')->nullable()->default('Jawa Tengah')->after('kabupaten');
            $table->string('kode_pos', 10)->nullable()->default('53176')->after('provinsi');
            $table->string('telepon', 30)->nullable()->default('081234567890')->after('kode_pos');
            $table->string('whatsapp', 30)->nullable()->default('081234567890')->after('telepon');
            $table->string('telepon_alt', 30)->nullable()->after('whatsapp');
            $table->string('email')->nullable()->default('sekretariat@nuwangon.or.id')->after('telepon_alt');
            $table->text('google_maps_url')->nullable()->after('email');
            $table->text('petunjuk_arah_url')->nullable()->after('google_maps_url');
            $table->decimal('latitude', 10, 7)->nullable()->after('petunjuk_arah_url');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('jam_operasional_hari')->nullable()->default('Senin - Ahad')->after('longitude');
            $table->string('jam_buka', 10)->nullable()->default('08.00')->after('jam_operasional_hari');
            $table->string('jam_tutup', 10)->nullable()->default('16.00')->after('jam_buka');
            $table->string('jam_operasional_status')->nullable()->default('Setiap Hari Kerja')->after('jam_tutup');
            $table->string('jam_operasional_keterangan')->nullable()->after('jam_operasional_status');
        });

        // Initialize default address and maps URL on existing records
        DB::table('pengaturan')->whereNull('alamat')->update([
            'alamat' => 'Jl. Raya Wangon - Ajibarang, Dusun Sindurjan',
            'google_maps_url' => 'https://maps.google.com/?q=Wangon+Banyumas',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaturan', function (Blueprint $table) {
            $table->dropColumn([
                'label_section',
                'nama_kantor',
                'alamat',
                'desa',
                'kecamatan',
                'kabupaten',
                'provinsi',
                'kode_pos',
                'telepon',
                'whatsapp',
                'telepon_alt',
                'email',
                'google_maps_url',
                'petunjuk_arah_url',
                'latitude',
                'longitude',
                'jam_operasional_hari',
                'jam_buka',
                'jam_tutup',
                'jam_operasional_status',
                'jam_operasional_keterangan',
            ]);
        });
    }
};
