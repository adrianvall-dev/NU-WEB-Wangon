<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Massal</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; margin: 0; padding: 15px; background-color: #fff; }
        .card-container { margin-bottom: 15px; page-break-inside: avoid; }
        .card { width: 320px; border: 2px solid #008542; border-radius: 10px; overflow: hidden; }
        .header { background-color: #008542; color: #ffffff; padding: 8px 12px; font-size: 11px; font-weight: bold; }
        .body { padding: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; font-size: 10px; color: #333; line-height: 1.4; }
        .foto { width: 65px; height: 65px; object-fit: cover; border-radius: 6px; }
        .qr { width: 65px; height: 65px; }
    </style>
</head>
<body>
    @foreach ($anggotaList as $anggota)
        <div class="card-container">
            <div class="card">
                <div class="header">
                    KARTU TANDA ANGGOTA<br>
                    <span style="font-weight: normal; font-size: 10px;">{{ ucfirst($anggota->organisasi) }} NU Wangon</span>
                </div>
                <div class="body">
                    <table>
                        <tr>
                            <td style="width: 70px;">
                                @if ($anggota->foto && file_exists(public_path('storage/' . $anggota->foto)))
                                    <img class="foto" src="{{ public_path('storage/' . $anggota->foto) }}" alt="Foto">
                                @else
                                    <div class="foto" style="background-color: #eee; text-align: center; line-height: 65px; font-size: 8px; color: #777;">No Photo</div>
                                @endif
                            </td>
                            <td>
                                <strong style="font-size: 11px; color: #000;">{{ $anggota->nama }}</strong><br>
                                No. Anggota: {{ $anggota->nomor_anggota ?? '-' }}<br>
                                NIK: {{ $anggota->nik_masked ?? '-' }}<br>
                                HP: {{ $anggota->no_hp_masked ?? '-' }}
                            </td>
                            <td style="width: 70px; text-align: right;">
                                @php
                                    $massVerifKode = $anggota->kode_verifikasi ?? ($anggota->kode_pendaftaran ?? 'NU-' . $anggota->id);
                                    $massQrCacheDir = storage_path('app/public/qr');
                                    if (!file_exists($massQrCacheDir)) {
                                        @mkdir($massQrCacheDir, 0755, true);
                                    }
                                    $massQrFile = $massQrCacheDir . '/' . md5($massVerifKode) . '.svg';
                                    if (!file_exists($massQrFile)) {
                                        $remoteQr = @file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=120x120&format=svg&data=" . urlencode(route('verifikasi.show', $massVerifKode)));
                                        if ($remoteQr && str_contains($remoteQr, '<svg')) {
                                            @file_put_contents($massQrFile, $remoteQr);
                                        }
                                    }
                                @endphp
                                @if (file_exists($massQrFile))
                                    <img class="qr" src="{{ $massQrFile }}" alt="QR">
                                @else
                                    <div class="qr" style="border: 1px solid #008542; font-size: 8px; text-align: center; line-height: 65px; color: #008542; font-weight: bold;">QR</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>