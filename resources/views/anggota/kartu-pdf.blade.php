<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Tanda Anggota - {{ $anggota->nama }}</title>
    <style>
        @page {
            size: 202pt 278pt;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: #FFFFFF;
            -webkit-print-color-adjust: exact;
        }
        .page-container {
            width: 202pt;
            height: 278pt;
            position: relative;
            page-break-after: always;
            overflow: hidden;
            background: #FFFFFF;
        }
        .page-container.back-page-container {
            page-break-after: avoid;
        }

        /* Solid Offset Shadow: 3pt offset from card */
        .outer-card-shadow {
            position: absolute;
            top: 28pt;
            left: 21pt;
            width: 164pt;
            height: 226pt;
            background-color: #1B382B;
            border-radius: 14pt;
        }

        /* Outer Card Face: Centered on 202pt x 278pt page with ample safe margins */
        .outer-card-front {
            position: absolute;
            top: 25pt;
            left: 18pt;
            width: 164pt;
            height: 226pt;
            background-color: #FFFFFF;
            border: 1.5pt solid #1B382B;
            border-radius: 14pt;
            overflow: hidden;
        }
        .outer-card-back {
            position: absolute;
            top: 25pt;
            left: 18pt;
            width: 164pt;
            height: 226pt;
            background-color: #F6F4EE;
            border: 1.5pt solid #1B382B;
            border-radius: 14pt;
            overflow: hidden;
        }

        .inner-content {
            width: 146pt;
            margin: 8pt auto 0 auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>
<body>

@php
    $prefix = \App\Support\Organisasi::prefix($anggota->organisasi);
    $tahun = date('Y');
    $nomorFormatted = $anggota->nomor_anggota ?? "NU-{$prefix}-{$tahun}-" . str_pad($anggota->id, 4, '0', STR_PAD_LEFT);
    $verifKode = $anggota->kode_verifikasi ?? ($anggota->kode_pendaftaran ?? 'NU-' . $anggota->id);
    $nikMasked = $anggota->nik_masked ?? ($anggota->nik ? substr($anggota->nik, 0, 3) . '************' : '-');
    $hpMasked = $anggota->no_hp_masked ?? ($anggota->no_hp ?? '-');
    $banomLabel = \App\Support\Organisasi::label($anggota->organisasi);
    $jabatanLabel = strtolower($anggota->jabatan ?? 'anggota');

    // Foto Anggota & Logo Paths
    $fotoPath = ($anggota->foto && file_exists(public_path('storage/' . $anggota->foto))) ? public_path('storage/' . $anggota->foto) : null;
    $logoPath = public_path('images/logo-nu-card.jpg');
    $phoneIconPath = public_path('images/icon-phone-dark.svg');

    // QR Code Vector SVG Caching
    $qrCacheDir = storage_path('app/public/qr');
    if (!file_exists($qrCacheDir)) {
        @mkdir($qrCacheDir, 0755, true);
    }
    $qrFile = $qrCacheDir . '/' . md5($verifKode) . '.svg';
    if (!file_exists($qrFile)) {
        $verifUrl = route('verifikasi.show', $verifKode);
        $remoteQr = @file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=140x140&format=svg&data=" . urlencode($verifUrl));
        if ($remoteQr && str_contains($remoteQr, '<svg')) {
            @file_put_contents($qrFile, $remoteQr);
        } else {
            // Clean vector SVG QR fallback pattern
            $fallbackSvg = '<?xml version="1.0" encoding="UTF-8"?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><rect width="100" height="100" fill="#ffffff"/><rect x="8" y="8" width="28" height="28" fill="#1B382B"/><rect x="14" y="14" width="16" height="16" fill="#ffffff"/><rect x="18" y="18" width="8" height="8" fill="#1B382B"/><rect x="64" y="8" width="28" height="28" fill="#1B382B"/><rect x="70" y="14" width="16" height="16" fill="#ffffff"/><rect x="74" y="18" width="8" height="8" fill="#1B382B"/><rect x="8" y="64" width="28" height="28" fill="#1B382B"/><rect x="14" y="70" width="16" height="16" fill="#ffffff"/><rect x="18" y="74" width="8" height="8" fill="#1B382B"/><rect x="42" y="12" width="12" height="12" fill="#1B382B"/><rect x="42" y="44" width="16" height="16" fill="#1B382B"/><rect x="64" y="44" width="12" height="12" fill="#1B382B"/><rect x="42" y="76" width="12" height="12" fill="#1B382B"/><rect x="76" y="76" width="12" height="12" fill="#1B382B"/></svg>';
            @file_put_contents($qrFile, $fallbackSvg);
        }
    }
@endphp

<!-- ================= PAGE 1 : SISI DEPAN (FRONT) ================= -->
<div class="page-container front-page-container">
    <!-- Solid Offset Shadow -->
    <div class="outer-card-shadow"></div>

    <!-- Main Front Card Face -->
    <div class="outer-card-front">
        <div class="inner-content">
            <!-- Header Table -->
            <table style="width: 100%;">
                <tr>
                    <td style="width: 22pt; vertical-align: middle;">
                        <div style="width: 20pt; height: 20pt; background-color: #F6F4EE; border: 0.8pt solid #E5E1D8; border-radius: 5pt; text-align: center; padding-top: 1.5pt;">
                            <img src="{{ $logoPath }}" style="width: 16pt; height: 16pt; object-fit: contain;" alt="Logo NU">
                        </div>
                    </td>
                    <td style="padding-left: 5pt; vertical-align: middle;">
                        <div style="font-size: 4.8pt; font-weight: bold; color: #1B382B; letter-spacing: 0.4pt; text-transform: uppercase;">
                            NAHDLATUL ULAMA
                        </div>
                        <div style="font-size: 7.2pt; font-weight: 800; color: #1B382B; letter-spacing: 0.1pt;">
                            {{ $banomLabel }} <span style="font-weight: 900;">WANGON</span>
                        </div>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <div style="display: inline-block; background-color: #E8F5E9; border-radius: 8pt; padding: 2pt 5pt; text-align: center;">
                            <span style="display: inline-block; width: 3.5pt; height: 3.5pt; background-color: #10B981; border-radius: 50%; vertical-align: middle; margin-right: 1.5pt;"></span>
                            <span style="color: #065F46; font-size: 4.8pt; font-weight: 800; letter-spacing: 0.4pt; vertical-align: middle; text-transform: uppercase;">AKTIF</span>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Thin Divider Line -->
            <div style="height: 0.6pt; background-color: #EFECE6; margin: 4pt 0 6pt 0;"></div>

            <!-- Centered Photo Frame with Offset Shadow -->
            <div style="position: relative; width: 58pt; height: 66pt; margin: 0 auto 5pt auto;">
                <div style="position: absolute; top: 2.2pt; left: 2.2pt; width: 58pt; height: 66pt; background-color: #1B382B; border-radius: 10pt;"></div>
                <div style="position: absolute; top: 0; left: 0; width: 58pt; height: 66pt; border: 1.3pt solid #1B382B; border-radius: 10pt; background-color: #FFFFFF; overflow: hidden; padding: 1pt;">
                    @if ($fotoPath)
                        <img src="{{ $fotoPath }}" style="width: 54pt; height: 62pt; border-radius: 8pt; object-fit: cover; display: block;" alt="{{ $anggota->nama }}">
                    @else
                        <div style="width: 54pt; height: 62pt; border-radius: 8pt; background-color: #E5E7EB; text-align: center; line-height: 62pt; font-size: 5.5pt; color: #6B7280; font-weight: bold;">
                            FOTO
                        </div>
                    @endif
                </div>
            </div>

            <!-- Identity: Name & Jabatan -->
            <div style="text-align: center; margin-bottom: 4pt;">
                <div style="font-size: 9.2pt; font-weight: 800; color: #111827; letter-spacing: 0.1pt; line-height: 1.2;">
                    {{ $anggota->nama }}
                </div>
                <div style="font-size: 6.2pt; font-weight: 600; color: #4B5563; margin-top: 1pt; text-transform: lowercase;">
                    {{ $jabatanLabel }}
                </div>
            </div>

            <!-- Badges Row -->
            <div style="text-align: center; margin-bottom: 5pt;">
                <span style="display: inline-block; background-color: #DCE7E1; color: #1B382B; font-size: 4.8pt; font-weight: 800; padding: 2pt 5.5pt; border-radius: 8pt; text-transform: uppercase; margin-right: 2pt;">
                    {{ $banomLabel }}
                </span>
                <span style="display: inline-block; background-color: #EFECE6; color: #1B382B; font-size: 4.8pt; font-weight: 800; font-family: monospace; padding: 2pt 5.5pt; border-radius: 8pt;">
                    {{ $nomorFormatted }}
                </span>
            </div>

            <!-- Phone Contact Row -->
            <div style="text-align: center; font-size: 5.8pt; font-weight: 800; font-family: monospace; color: #1B382B; margin-bottom: 6pt;">
                <img src="{{ $phoneIconPath }}" style="width: 6pt; height: 6pt; vertical-align: -0.5pt; margin-right: 2pt;" alt="Phone">
                <span style="vertical-align: middle;">{{ $hpMasked }}</span>
            </div>

            <!-- Official Footer Seal / Status Box (Printable ID Card - NO BUTTON!) -->
            <div style="background-color: #F6F4EE; border: 0.8pt solid #E5E1D8; border-radius: 7pt; padding: 3pt 6pt; text-align: center;">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 4.2pt; font-weight: 800; color: #1B382B; text-transform: uppercase;">
                            KARTU TANDA ANGGOTA RESMI
                        </td>
                        <td style="text-align: right; font-size: 4.2pt; font-weight: 800; color: #4B5563; text-transform: uppercase;">
                            MWC NU WANGON
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= PAGE 2 : SISI BELAKANG (BACK) ================= -->
<div class="page-container back-page-container">
    <!-- Solid Offset Shadow -->
    <div class="outer-card-shadow"></div>

    <!-- Main Back Card Face -->
    <div class="outer-card-back">
        <div class="inner-content">
            <!-- Header Table -->
            <table style="width: 100%;">
                <tr>
                    <td style="width: 16pt; vertical-align: middle;">
                        <img src="{{ $logoPath }}" style="width: 14pt; height: 14pt; object-fit: contain;" alt="Logo NU">
                    </td>
                    <td style="padding-left: 3pt; vertical-align: middle;">
                        <div style="font-size: 4.8pt; font-weight: bold; color: #008542; letter-spacing: 0.4pt; text-transform: uppercase;">
                            E-KARTANU RESMI
                        </div>
                        <div style="font-size: 7.2pt; font-weight: 800; color: #1B382B; text-transform: uppercase; letter-spacing: 0.1pt;">
                            MWC NU WANGON
                        </div>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <span style="font-size: 5.2pt; font-weight: 800; color: #4B5563; letter-spacing: 0.5pt; text-transform: uppercase;">
                            VALIDASI
                        </span>
                    </td>
                </tr>
            </table>

            <!-- Divider -->
            <div style="height: 0.6pt; background-color: #E5E1D8; margin: 5pt 0 6pt 0;"></div>

            <!-- Middle Section: QR, Barcode, Verification Code, Instruction -->
            <div style="text-align: center;">
                <!-- QR Code with white rounded backing -->
                <div style="display: inline-block; background-color: #FFFFFF; border: 1pt solid #E5E1D8; border-radius: 9pt; padding: 3pt; margin-bottom: 5pt;">
                    <img src="{{ $qrFile }}" style="width: 72pt; height: 72pt; display: block; margin: 0 auto;" alt="QR Code">
                </div>

                <!-- 1D Barcode Pattern -->
                <div style="height: 12pt; text-align: center; margin-bottom: 3pt;">
                    <span style="display: inline-block; width: 1.6pt; height: 12pt; background: #1B382B; margin-right: 1pt;"></span>
                    <span style="display: inline-block; width: 0.8pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 2.4pt; height: 12pt; background: #1B382B; margin-right: 1pt;"></span>
                    <span style="display: inline-block; width: 0.8pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 3.0pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 1.2pt; height: 12pt; background: #1B382B; margin-right: 1pt;"></span>
                    <span style="display: inline-block; width: 0.8pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 2.2pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 0.8pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 2.6pt; height: 12pt; background: #1B382B; margin-right: 1pt;"></span>
                    <span style="display: inline-block; width: 1.2pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 2.2pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 0.8pt; height: 12pt; background: #1B382B; margin-right: 1.2pt;"></span>
                    <span style="display: inline-block; width: 1.6pt; height: 12pt; background: #1B382B;"></span>
                </div>

                <!-- Verification Code -->
                <div style="font-family: monospace; font-size: 7.5pt; font-weight: 900; color: #1B382B; letter-spacing: 1.2pt; margin-bottom: 3pt;">
                    {{ $verifKode }}
                </div>

                <!-- Validation Text -->
                <div style="font-size: 4.6pt; color: #4B5563; line-height: 1.25; padding: 0 6pt; margin-bottom: 6pt;">
                    Scan QR untuk memverifikasi keabsahan resmi di portal MWC NU Wangon.
                </div>
            </div>

            <!-- Divider -->
            <div style="height: 0.6pt; background-color: #E5E1D8; margin: 0 0 5pt 0;"></div>

            <!-- Bottom Section: ID & HP in Styled Box (Completing the card vertically) -->
            <div style="background-color: #FFFFFF; border: 0.8pt solid #E5E1D8; border-radius: 7pt; padding: 4pt 6pt;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; text-align: center; vertical-align: middle; border-right: 0.6pt solid #EFECE6;">
                            <div style="font-size: 4.2pt; font-weight: bold; color: #6B7280; text-transform: uppercase;">ID ANGGOTA:</div>
                            <div style="font-size: 5.5pt; font-weight: 800; font-family: monospace; color: #1B382B; margin-top: 1pt;">
                                {{ $nomorFormatted }}
                            </div>
                        </td>
                        <td style="width: 50%; text-align: center; vertical-align: middle;">
                            <div style="font-size: 4.2pt; font-weight: bold; color: #6B7280; text-transform: uppercase;">NOMOR HP:</div>
                            <div style="font-size: 5.5pt; font-weight: 800; font-family: monospace; color: #1B382B; margin-top: 1pt;">
                                {{ $hpMasked }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>