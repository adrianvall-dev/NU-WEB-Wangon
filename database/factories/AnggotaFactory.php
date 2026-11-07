<?php

namespace Database\Factories;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    protected $model = Anggota::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nik' => fake()->numerify('3302############'),
            'organisasi' => 'ansor',
            'jabatan' => 'Anggota',
            'alamat' => fake()->address(),
            'no_hp' => fake()->phoneNumber(),
            'status' => 'aktif',
            'status_pendaftaran' => 'approved',
            'posisi_pendaftaran' => 'anggota',
            'kode_pendaftaran' => Anggota::buatKodePendaftaran(),
            'kode_verifikasi' => Anggota::buatKodeVerifikasi(),
            'nomor_anggota' => 'ANS-'.fake()->unique()->numerify('###'),
        ];
    }
}
