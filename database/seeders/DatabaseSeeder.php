<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // 1. AKUN ADMIN
        // ============================
        User::create([
            'name' => 'Admin Muslimat NU',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // Password ini HANYA untuk development, jangan dipakai di production
            'role' => 'admin',
        ]);

        // ============================
        // 2. AKUN USER CONTOH
        // ============================
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Fatimah Zahra',
            'email' => 'fatimah@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // ============================
        // 3. BERITA CONTOH
        // ============================
        News::create([
            'title' => 'Pengajian Rutin Bulanan Dimulai Kembali',
            'slug' => Str::slug('Pengajian Rutin Bulanan Dimulai Kembali'),
            'content' => "Muslimat NU Desa Sindang kembali mengadakan pengajian rutin bulanan.\n\nKegiatan ini terbuka untuk seluruh ibu-ibu di lingkungan Desa Sindang.",
            'status' => 'published',
            'published_at' => now(),
        ]);

        News::create([
            'title' => 'Santunan Anak Yatim Tahun Ini',
            'slug' => Str::slug('Santunan Anak Yatim Tahun Ini'),
            'content' => "Alhamdulillah, kegiatan santunan anak yatim tahun ini berjalan lancar.\n\nTerima kasih kepada seluruh anggota yang telah berpartisipasi.",
            'status' => 'published',
            'published_at' => now()->subDays(3),
        ]);

        News::create([
            'title' => 'Rencana Kegiatan Semester Depan',
            'slug' => Str::slug('Rencana Kegiatan Semester Depan'),
            'content' => "Berikut adalah rencana kegiatan Muslimat NU Desa Sindang untuk semester mendatang.\n\nAkan diinformasikan lebih lanjut melalui pengumuman resmi.",
            'status' => 'draft',
            'published_at' => null,
        ]);

        // ============================
        // 4. KEGIATAN CONTOH
        // ============================
        Kegiatan::create([
            'title' => 'Pengajian Rutin Muslimat NU',
            'description' => "Pengajian rutin bulanan membahas kajian fiqih wanita.\n\nDihadiri oleh seluruh anggota Muslimat NU Desa Sindang.",
            'event_date' => now()->addDays(4)->format('Y-m-d'),
            'start_time' => '19:30:00',
            'end_time' => '21:00:00',
            'location' => 'Balai Desa Sindang',
            'status' => 'published',
        ]);

        Kegiatan::create([
            'title' => 'Santunan Anak Yatim',
            'description' => 'Kegiatan santunan rutin bagi anak yatim di lingkungan Desa Sindang.',
            'event_date' => now()->addDays(14)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'location' => 'Aula Masjid Desa Sindang',
            'status' => 'published',
        ]);

        Kegiatan::create([
            'title' => 'Rapat Koordinasi Pengurus',
            'description' => 'Rapat internal membahas program kerja tahunan pengurus Muslimat NU Desa Sindang.',
            'event_date' => now()->addDays(30)->format('Y-m-d'),
            'start_time' => '13:00:00',
            'end_time' => null,
            'location' => 'Sekretariat Muslimat NU',
            'status' => 'published',
        ]);
    }
}
