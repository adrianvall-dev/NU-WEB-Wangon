<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuLayanan;
use App\Models\Pengaturan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaturanSekretariatController extends Controller
{
    /**
     * Tampilkan halaman formulir pengaturan Pusat Layanan & Sekretariat.
     */
    public function edit(): View
    {
        $pengaturan = Pengaturan::ambil();
        $kartuLayanans = KartuLayanan::orderBy('urutan', 'asc')->orderBy('id', 'asc')->get();

        return view('admin.pengaturan.sekretariat', compact('pengaturan', 'kartuLayanans'));
    }

    /**
     * Simpan pembaruan informasi kantor, kontak, petunjuk arah, jam operasional, dan kartu layanan.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // A. Informasi Kantor
            'label_section' => ['required', 'string', 'max:100'],
            'nama_kantor' => ['required', 'string', 'max:200'],
            'alamat' => ['required', 'string', 'max:500'],
            'desa' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],

            // B. Kontak Sekretariat
            'telepon' => ['nullable', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'telepon_alt' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],

            // C. Petunjuk Arah
            'google_maps_url' => ['nullable', 'url', 'max:1000'],
            'petunjuk_arah_url' => ['nullable', 'url', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            // D. Jam Operasional
            'jam_operasional_hari' => ['nullable', 'string', 'max:100'],
            'jam_buka' => ['nullable', 'string', 'max:20'],
            'jam_tutup' => ['nullable', 'string', 'max:20'],
            'jam_operasional_status' => ['nullable', 'string', 'max:100'],
            'jam_operasional_keterangan' => ['nullable', 'string', 'max:255'],

            // E. Kartu Layanan
            'cards' => ['nullable', 'array'],
            'cards.*.id' => ['nullable', 'integer'],
            'cards.*.judul' => ['nullable', 'string', 'max:100'],
            'cards.*.deskripsi' => ['nullable', 'string', 'max:255'],
            'cards.*.urutan' => ['nullable', 'integer'],
            'cards.*.is_active' => ['nullable'],

            // New cards if added inline
            'new_cards' => ['nullable', 'array'],
            'new_cards.*.judul' => ['nullable', 'string', 'max:100'],
            'new_cards.*.deskripsi' => ['nullable', 'string', 'max:255'],
            'new_cards.*.urutan' => ['nullable', 'integer'],
        ], [
            'label_section.required' => 'Label section wajib diisi.',
            'nama_kantor.required' => 'Nama kantor wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'email.email' => 'Format email sekretariat tidak valid.',
            'google_maps_url.url' => 'Format URL Google Maps harus berupa tautan valid (diawali http:// atau https://).',
            'petunjuk_arah_url.url' => 'Format Link Petunjuk Arah harus berupa URL valid.',
            'latitude.between' => 'Nilai latitude harus antara -90 hingga 90.',
            'longitude.between' => 'Nilai longitude harus antara -180 hingga 180.',
        ]);

        // Validasi nomor WhatsApp jika diisi
        if (! empty($validated['whatsapp'])) {
            $normalizedWa = Pengaturan::normalizeWhatsappNumber($validated['whatsapp']);
            if (! $normalizedWa) {
                return back()
                    ->withInput()
                    ->withErrors(['whatsapp' => 'Nomor WhatsApp tidak valid. Masukkan nomor dengan format yang benar (misal: 081234567890).']);
            }
        }

        $pengaturan = Pengaturan::ambil();
        $pengaturan->update([
            'label_section' => $validated['label_section'],
            'nama_kantor' => $validated['nama_kantor'],
            'alamat' => $validated['alamat'],
            'desa' => $validated['desa'] ?? null,
            'kecamatan' => $validated['kecamatan'] ?? null,
            'kabupaten' => $validated['kabupaten'] ?? null,
            'provinsi' => $validated['provinsi'] ?? null,
            'kode_pos' => $validated['kode_pos'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'telepon_alt' => $validated['telepon_alt'] ?? null,
            'email' => $validated['email'] ?? null,
            'google_maps_url' => $validated['google_maps_url'] ?? null,
            'petunjuk_arah_url' => $validated['petunjuk_arah_url'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'jam_operasional_hari' => $validated['jam_operasional_hari'] ?? null,
            'jam_buka' => $validated['jam_buka'] ?? null,
            'jam_tutup' => $validated['jam_tutup'] ?? null,
            'jam_operasional_status' => $validated['jam_operasional_status'] ?? null,
            'jam_operasional_keterangan' => $validated['jam_operasional_keterangan'] ?? null,
        ]);

        // Simpan pembaruan kartu informasi layanan yang sudah ada
        if (! empty($request->cards) && is_array($request->cards)) {
            foreach ($request->cards as $cardId => $cardData) {
                if (! empty($cardData['judul']) && ! empty($cardData['deskripsi'])) {
                    KartuLayanan::where('id', $cardId)->update([
                        'judul' => trim($cardData['judul']),
                        'deskripsi' => trim($cardData['deskripsi']),
                        'urutan' => isset($cardData['urutan']) ? (int) $cardData['urutan'] : 0,
                        'is_active' => ! empty($cardData['is_active']),
                    ]);
                }
            }
        }

        // Tambah kartu baru jika diinput di baris baru
        if (! empty($request->new_cards) && is_array($request->new_cards)) {
            foreach ($request->new_cards as $newCard) {
                if (! empty($newCard['judul']) && ! empty($newCard['deskripsi'])) {
                    KartuLayanan::create([
                        'judul' => trim($newCard['judul']),
                        'deskripsi' => trim($newCard['deskripsi']),
                        'urutan' => isset($newCard['urutan']) ? (int) $newCard['urutan'] : 0,
                        'is_active' => true,
                    ]);
                }
            }
        }

        // Selaraskan kartu 'Jam Operasional' agar selalu konsisten dengan jam operasional kantor
        $jamOperasionalCard = KartuLayanan::whereRaw('LOWER(judul) = ?', ['jam operasional'])->first();
        if ($jamOperasionalCard && ! empty($pengaturan->jam_operasional_formatted)) {
            $jamOperasionalCard->update([
                'deskripsi' => $pengaturan->jam_operasional_formatted,
            ]);
        }

        // Selaraskan kartu 'Layanan KARTANU' jika status pelayanan diatur
        if (! empty($validated['jam_operasional_status'])) {
            $kartanuCard = KartuLayanan::whereRaw('LOWER(judul) = ?', ['layanan kartanu'])->first();
            if ($kartanuCard) {
                $kartanuCard->update([
                    'deskripsi' => $validated['jam_operasional_status'],
                ]);
            }
        }

        // Pastikan cache memori dan cache persistent segera dibersihkan
        Pengaturan::lupakanCache();

        return redirect()
            ->route('admin.pengaturan.sekretariat')
            ->with('success', 'Pengaturan Pusat Layanan & Sekretariat berhasil disimpan.');
    }

    /**
     * Tambah satu kartu layanan baru.
     */
    public function storeCard(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        KartuLayanan::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'urutan' => $validated['urutan'] ?? 0,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        return redirect()
            ->route('admin.pengaturan.sekretariat')
            ->with('success', 'Kartu informasi layanan berhasil ditambahkan.');
    }

    /**
     * Hapus satu kartu layanan.
     */
    public function destroyCard(KartuLayanan $kartu): RedirectResponse
    {
        $kartu->delete();

        return redirect()
            ->route('admin.pengaturan.sekretariat')
            ->with('success', 'Kartu informasi layanan berhasil dihapus.');
    }
}
