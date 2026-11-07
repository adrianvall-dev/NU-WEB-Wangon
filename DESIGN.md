---
name: NU Wangon Community Portal Design System
description: Modern Islamic civic design system blending editorial typography, green heritage tones, and structured bento cards.
colors:
  primary: "#2D4736"
  primary-hover: "#23382B"
  background: "#f9f8f4"
  surface: "#fcfcfb"
  surface-tint: "#f3f2ec"
  text-primary: "#191E1A"
  text-secondary: "#636B65"
  border: "#E7E8E1"
  accent-green: "#4E7A5A"
typography:
  font-heading: "'Instrument Serif', 'Fraunces', 'Playfair Display', serif"
  font-body: "'Plus Jakarta Sans', 'Inter', system-ui, sans-serif"
  h1:
    fontFamily: "{typography.font-heading}"
    fontSize: "3.25rem"
    fontWeight: 600
    lineHeight: 1.15
    letterSpacing: "-0.01em"
  h2:
    fontFamily: "{typography.font-heading}"
    fontSize: "2rem"
    fontWeight: 600
    lineHeight: 1.25
  body-md:
    fontFamily: "{typography.font-body}"
    fontSize: "0.9375rem"
    fontWeight: 400
    lineHeight: 1.6
  label-eyebrow:
    fontFamily: "{typography.font-body}"
    fontSize: "0.75rem"
    fontWeight: 700
    letterSpacing: "0.1em"
    textTransform: "uppercase"
rounded:
  sm: "6px"
  md: "10px"
  lg: "16px"
  xl: "24px"
  "2xl": "32px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
  "2xl": "48px"
  "3xl": "72px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#FFFFFF"
    rounded: "{rounded.full}"
    padding: "12px 26px"
    fontWeight: 600
  card-bento:
    backgroundColor: "{colors.surface}"
    borderColor: "{colors.border}"
    rounded: "{rounded.lg}"
    padding: "{spacing.lg}"
  form-input:
    backgroundColor: "{colors.surface}"
    borderColor: "{colors.border}"
    rounded: "{rounded.md}"
    padding: "12px 16px"
---

## 1. Overview
Design system untuk portal MWC NU Wangon dirancang untuk memadukan khidmat keumatan tradisional dengan profesionalisme modern. Pendekatan visual mengedepankan keterbacaan, keterbukaan informasi publik (ZISWAF dan program), serta kemudahan layanan warga (registrasi KARTANU).

## 2. Color System
- **Deep Forest Green (`#2D4736`):** Turunan warna hijau kebanggaan Nahdlatul Ulama yang dimatangkan menjadi *botanical forest green*, memberikan kesan berwibawa, teduh, dan prestisius.
- **Warm Ivory (`#FBFBF9`):** Kanvas utama yang hangat, terhindar dari kesan putih hampa klinis, mencerminkan keramahan pesantren dan balai warga.
- **Pure White (`#FFFFFF`):** Warna card konten untuk memastikan dokumen warta dan formulir memiliki kontras dan keterbacaan maksimal.
- **Deep Charcoal (`#191E1A`):** Teks utama dengan kontras tinggi namun lembut di mata pembaca lintas usia.

## 3. Typography & Hierarchy
- **Editorial Serif:** Digunakan khusus pada H1, H2, dan judul modul untuk mengedepankan nilai hikmah, keagungan dakwah, dan sejarah panjang organisasi.
- **Plus Jakarta Sans / Inter:** Digunakan untuk seluruh navigasi, isi berita, tabel data, dan elemen formulir agar tetap modern dan ramah layar perangkat mobile.

## 4. Layout & Bento Structure
- Bagian pilar khidmat menggunakan struktur **Bento Grid** asimetris guna mengelompokkan berbagai jenis data (berita, statistik transparansi dana, agenda) dalam satu kesatuan visual yang padat dan teratur.
- Spacing 8px konsisten dengan section padding luas untuk menjaga ruang baca yang khidmat.

## 5. Rules to Never Break
- **Jangan gunakan warna hijau neon atau hijau terang silau**; selalu gunakan deep forest green `#2D4736` untuk menjaga kesan teduh dan matang.
- **Jangan gunakan bayangan hitam pekat**; andalkan hairline border `#E7E8E1` dan latar kartu putih bersih.
- **Pertahankan bentuk rounded-full pada tombol aksi** guna memberikan sentuhan ramah dan inklusif bagi seluruh lapisan warga jam'iyyah.