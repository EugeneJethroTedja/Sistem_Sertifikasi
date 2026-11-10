<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Preview Sertifikat – {{ $peserta->nama }}</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=EB+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Great+Vibes&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
body { background: #2c2c2c; font-family: 'EB Garamond', 'Times New Roman', serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

.print-bar { background: #0c2461; color: #fff; padding: 10px 24px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 999; box-shadow: 0 2px 10px rgba(0,0,0,.4); }
.print-bar span { font-size: 13px; font-family: sans-serif; opacity: .9; }
.print-bar a { font-family: sans-serif; font-size: 13px; color: #fff; text-decoration: none; padding: 6px 16px; border-radius: 5px; font-weight: 500; }
.btn-bk { background: #495057; } .btn-prnt { background: #1a7a4a; } .btn-grp { display: flex; gap: 8px; }
@media print { .print-bar { display: none !important; } body { background: #fff; } }

@page { size: A4 landscape; margin: 0; }
.page { width: 297mm; height: 210mm; background: #fff; margin: 20px auto; position: relative; overflow: hidden; page-break-after: always; break-after: page; }
.page:last-child { page-break-after: auto; break-after: auto; }
@media print { body { background: #fff; } .page { margin: 0; box-shadow: none; } }

/* DEKORASI */
.border-outer { position: absolute; inset: 5mm; border: 2.5px solid #b8962e; z-index: 2; pointer-events: none; }
.border-inner  { position: absolute; inset: 8mm; border: 0.8px solid #d4af5a; z-index: 2; pointer-events: none; }
.wave-tl, .wave-br { position: absolute; width: 105mm; height: 80mm; z-index: 1; pointer-events: none; }
.wave-tl { top: 0; left: 0; } .wave-br { bottom: 0; right: 0; transform: rotate(180deg); }
.orn-side { position: absolute; top: 50%; transform: translateY(-50%); z-index: 3; pointer-events: none; }
.orn-side.left { left: 5.5mm; } .orn-side.right { right: 5.5mm; }
.watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 80mm; height: 80mm; opacity: 0.035; z-index: 1; pointer-events: none; }

/* ══════════════════════════════════════════════════
   HALAMAN 1 — LAYOUT FINAL
══════════════════════════════════════════════════ */
.cert-body {
    position: relative; z-index: 10;
    display: flex; flex-direction: column; align-items: center;
    height: 210mm;
    padding: 10mm 26mm 9mm;
}

.cert-top    { display: flex; flex-direction: column; align-items: center; width: 100%; }
.cert-middle { display: flex; flex-direction: column; align-items: center; width: 100%; margin-top: 5mm; }
/* cert-bottom: increased margin-top so it doesn't crowd "berlaku" text */
.cert-bottom { display: flex; flex-direction: column; align-items: center; width: 100%; margin-top: 3mm; }

/* Logo */
.logo-wrap { margin-bottom: 1mm; }
.logo-wrap img { height: 23mm; width: auto; }
.logo-ph { width: 23mm; height: 23mm; border: 2px solid #b8962e; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

/* Institusi */
.inst-1 { font-family: 'Cinzel', serif; font-size: 11.5pt; font-weight: 900; color: #0c2461; letter-spacing: 1.5px; text-align: center; text-transform: uppercase; }
.inst-2 { font-family: 'Cinzel', serif; font-size: 10.5pt; font-weight: 700; color: #0c2461; letter-spacing: 1px; text-align: center; text-transform: uppercase; margin-top: 1px; }
.inst-3 { font-family: 'EB Garamond', serif; font-style: italic; font-size: 9pt; color: #2d6a2d; letter-spacing: 1.5px; text-align: center; margin-top: 1px; }

.rule-gold { width: 100%; height: 1.5px; background: linear-gradient(90deg, transparent, #b8962e 15%, #f0d47a 50%, #b8962e 85%, transparent); margin: 1.5mm 0; }
.rule-thin  { width: 60%; height: 0.6px; background: linear-gradient(90deg, transparent, #b8962e, transparent); margin: 0.5mm auto; }

/* Judul */
.cert-title    { font-family: 'Cinzel', serif; font-size: 22pt; font-weight: 900; color: #0c2461; letter-spacing: 10px; text-align: center; text-transform: uppercase; margin: 0; text-shadow: 1px 1px 0 rgba(184,150,46,.3); }
.cert-subtitle { font-family: 'Cinzel', serif; font-size: 8pt; font-weight: 600; color: #5a4a1a; letter-spacing: 3px; text-align: center; text-transform: uppercase; margin-top: 0.5mm; }

/* Nomor */
.cert-nomor { background: linear-gradient(135deg, #0c2461, #1a3a8c); color: #f0d47a; font-family: 'EB Garamond', serif; font-size: 9.5pt; font-weight: 600; letter-spacing: 1px; padding: 0.8mm 10mm; border-radius: 2px; border: 1px solid #b8962e; }

/* Nama */
.diberikan  { font-family: 'EB Garamond', serif; font-style: italic; font-size: 10pt; color: #555; margin-top: 1mm; }
.nama-besar { font-family: 'Great Vibes', cursive; font-size: 32pt; color: #0c2461; line-height: 1.15; text-align: center; padding: 1mm 0 2mm; }
.nama-ul    { width: 80%; height: 0.8px; background: linear-gradient(90deg, transparent, #b8962e 20%, #b8962e 80%, transparent); margin: 0 auto; }

/* Tabel NIP/Jabatan/Perangkat */
.data-tbl { border-collapse: collapse; width: auto; margin: 1mm auto 0; font-size: 11pt; }
.data-tbl td { padding: 0.5mm 2mm; vertical-align: middle; color: #222; }
.data-tbl .lbl { font-weight: 600; color: #444; font-style: italic; text-align: left; white-space: nowrap; width: 42mm; min-width: 42mm; }
.data-tbl .sep { width: 7mm; color: #b8962e; font-weight: bold; text-align: center; }
.data-tbl .val { font-weight: 700; color: #0c2461; font-size: 11.5pt; text-align: left; }

/* Partisipasi */
.partisipasi { font-size: 10pt; text-align: center; color: #2a2a2a; line-height: 1.45; width: 100%; margin-bottom: 1.5mm; }

/* Masa berlaku */
.berlaku-center { width: 100%; text-align: center; }
.berlaku-strip  { display: inline-block; font-family: 'EB Garamond', serif; font-size: 9.5pt; font-weight: 700; font-style: italic; color: #5a3e00; border: 1px solid #b8962e; border-radius: 3px; padding: 0.8mm 10mm; background: linear-gradient(135deg, #fdf8ed, #faf0d0); }

/* ══════════════════════════════════════
   TTD + BARCODE HALAMAN 1
   - margin-top: auto mendorong TTD ke bawah
   - Tambah padding-top agar ada jarak dari "berlaku"
   - Hanya 1 kolom: TTD center, barcode dihapus
══════════════════════════════════════ */
.ttd-barcode-wrap {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-top: auto;
    padding-top: 5mm; /* <— jarak tambahan dari teks berlaku */
}

.ttd-block   { text-align: center; font-size: 9.5pt; line-height: 1.35; }
.ttd-kota    { font-family: 'EB Garamond', serif; color: #222; font-size: 9.5pt; }
.ttd-jabatan { font-family: 'EB Garamond', serif; font-weight: 700; color: #0c2461; font-size: 9.5pt; }
.ttd-space   { height: 10mm; display: flex; align-items: center; justify-content: center; width: 100%; }
.ttd-nama    { font-family: 'Cinzel', serif; font-size: 9.5pt; font-weight: 700; color: #7a5200; border-top: 1.5px solid #b8962e; padding: 0.8mm 0 0; display: inline-block; margin-top: 0.8mm; }
.ttd-nip     { font-family: 'EB Garamond', serif; font-size: 9.5pt; color: #222; font-weight: 600; }

/* Barcode utama (center, menggantikan TTD digital) */
.barcode-center-box {
    width: 18mm; height: 18mm;
    border: 1.5px solid #b8962e;
    border-radius: 3px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; background: #fff;
    margin: 1mm auto 0;
}
.barcode-center-box img { width: 100%; height: 100%; object-fit: contain; display: block; }

/* ═══════════════════════════════════════
   HALAMAN 2+ ASPEK DETAIL (mode lama)
═══════════════════════════════════════ */
.hasil-body { position: relative; z-index: 10; display: flex; align-items: flex-start; justify-content: center; gap: 8mm; height: 210mm; padding: 11mm 22mm 13mm; }
.tes-col    { flex: 1; display: flex; flex-direction: column; }
.tes-header-title { font-family: 'Cinzel', serif; font-size: 11.5pt; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; background: linear-gradient(135deg, #0c2461, #1a3a8c); color: #f0d47a; text-align: center; padding: 2.5mm 3mm; border: 1.5px solid #0c2461; }
.tes-table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
.tes-table th { background: #1e3a70; color: rgba(255,255,255,.92); font-family: 'Cinzel', serif; font-size: 8.5pt; font-weight: 700; text-align: center; padding: 2mm 2.5mm; border: 1px solid #2a4a8a; }
.tes-table td { padding: 1.8mm 2.5mm; border: 0.5px solid #c8c8c8; vertical-align: middle; color: #1a1a1a; }
.tes-table tbody tr:nth-child(even) td { background: #f5f7fd; }
.td-no    { text-align: center; width: 10mm; font-weight: 700; color: #0c2461; }
.td-nilai { text-align: center; width: 22mm; font-weight: 700; font-size: 10pt; color: #0c2461; border-left: 0.5px solid #aaa !important; }
.tfoot-row td { background: linear-gradient(135deg, #0c2461, #1a3a8c) !important; color: #fff !important; font-family: 'Cinzel', serif; font-size: 8.5pt; font-weight: 700; text-align: center; padding: 2mm; border-color: #0c2461 !important; }
.tfoot-row .td-nilai { color: #f0d47a !important; font-size: 11pt; }
.vdivider { width: 1.5px; background: linear-gradient(180deg, transparent, #b8962e 20%, #b8962e 80%, transparent); flex-shrink: 0; align-self: stretch; }
.ket-note { font-family: 'EB Garamond', serif; font-style: italic; font-size: 8pt; color: #666; margin-top: 2mm; }

/* ═══════════════════════════════════════
   HALAMAN 2 MODE RINGKASAN
═══════════════════════════════════════ */
.rkm-body { position: relative; z-index: 10; display: flex; flex-direction: column; height: 210mm; padding: 11mm 22mm 9mm; }
.rkm-page-title { font-family: 'Cinzel', serif; font-size: 10.5pt; font-weight: 900; color: #0c2461; text-align: center; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 1.5mm; }
.rkm-nama { font-family: 'Great Vibes', cursive; font-size: 24pt; color: #0c2461; text-align: center; line-height: 1.2; margin-bottom: 0.5mm; }
.rkm-info { font-family: 'EB Garamond', serif; font-size: 9.5pt; font-style: italic; color: #555; text-align: center; margin-bottom: 3mm; }
.rkm-rule { width: 100%; height: 1.5px; background: linear-gradient(90deg, transparent, #b8962e 15%, #f0d47a 50%, #b8962e 85%, transparent); margin-bottom: 4mm; }
.rkm-table { width: 100%; border-collapse: collapse; font-family: 'EB Garamond', serif; font-size: 12pt; border: 2px solid #0c2461; }
.rkm-table thead th { background: linear-gradient(135deg, #0c2461, #1a3a8c); color: #f0d47a; font-family: 'Cinzel', serif; font-size: 9.5pt; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; text-align: center; padding: 3mm 4mm; border: 1px solid #1a3a8c; }
.rkm-table thead th.th-left { text-align: left; padding-left: 5mm; }
.rkm-table tbody tr:nth-child(odd)  td { background: #fff; }
.rkm-table tbody tr:nth-child(even) td { background: #f0f4ff; }
.rkm-table tbody td { padding: 4.5mm 5mm; border: 1px solid #c8d4e8; vertical-align: middle; color: #111; font-size: 12pt; }
.rkm-no { text-align: center; width: 14mm; font-weight: 700; font-size: 13pt; color: #0c2461; border-right: 2px solid #0c2461 !important; }
.rkm-penilaian { font-weight: 700; font-size: 12pt; color: #0c2461; width: 95mm; border-right: 2px solid #0c2461 !important; line-height: 1.5; }
.rkm-level-sub { font-size: 10pt; font-weight: 400; font-style: italic; color: #555; display: block; margin-top: 1mm; }
.rkm-hasil { text-align: center; font-size: 12pt; color: #111; line-height: 1.7; }
.rkm-hasil-utama { font-weight: 700; font-size: 13pt; color: #0c2461; display: block; }
.rkm-detail { font-size: 10pt; font-weight: 400; font-style: italic; color: #444; display: block; margin-top: 0.8mm; }

/* ══════════════════════════════════════
   FOOTER HALAMAN 2 — TTD disembunyikan,
   barcode2.jpeg ditampilkan center di atas garis
══════════════════════════════════════ */
.h2-footer {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: auto;
    padding-top: 3mm;
}
.h2-footer-line {
    width: 100%;
    height: 1px;
    background: #d4af5a;
    margin-bottom: 3mm;
}
.h2-barcode-center {
    width: 28mm; height: 28mm;
    border: 2px solid #b8962e;
    border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; background: #fff;
    box-shadow: 0 0 6px rgba(184,150,46,.25);
    margin: 0 auto;
}
.h2-barcode-center img { width: 100%; height: 100%; object-fit: contain; display: block; }
</style>
</head>
<body>

<div class="print-bar">
    <a href="{{ route('peserta.index', $kegiatan) }}" class="btn-bk">← Kembali</a>
    <span>Preview Sertifikat &nbsp;|&nbsp; {{ $peserta->nama }} &nbsp;|&nbsp; {{ $peserta->nomor_sertifikat }}</span>
    <div class="btn-grp">
        <a href="javascript:window.print()" class="btn-prnt">🖨 Cetak</a>
    </div>
</div>

{{-- ════ HALAMAN 1 ════ --}}
<div class="page">
    <div class="wave-tl"><svg viewBox="0 0 378 283" xmlns="http://www.w3.org/2000/svg" width="105mm" height="80mm"><defs><linearGradient id="wg" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#0d3b8c"/><stop offset="100%" stop-color="#0c2461"/></linearGradient></defs><path d="M0,0 L378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283 Z" fill="url(#wg)"/><path d="M378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283" fill="none" stroke="#b8962e" stroke-width="8"/><path d="M378,20 Q305,52 232,103 Q158,163 108,220 Q64,260 8,278" fill="none" stroke="#f0d47a" stroke-width="3" opacity=".65"/><path d="M378,40 Q300,74 222,127 Q147,188 95,240 Q52,274 16,282" fill="none" stroke="#fff" stroke-width="1" opacity=".15"/></svg></div>
    <div class="wave-br"><svg viewBox="0 0 378 283" xmlns="http://www.w3.org/2000/svg" width="105mm" height="80mm"><path d="M0,0 L378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283 Z" fill="#0c2461"/><path d="M378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283" fill="none" stroke="#b8962e" stroke-width="8"/><path d="M378,20 Q305,52 232,103 Q158,163 108,220 Q64,260 8,278" fill="none" stroke="#f0d47a" stroke-width="3" opacity=".65"/></svg></div>
    <div class="border-outer"></div>
    <div class="border-inner"></div>
    <div class="orn-side left"><svg viewBox="0 0 40 200" width="10mm" height="55mm"><g fill="#b8962e" opacity=".8"><ellipse cx="20" cy="8" rx="6" ry="8"/><circle cx="20" cy="22" r="4"/><path d="M8,30 Q4,48 8,68 Q14,78 20,78 Q26,78 32,68 Q36,48 32,30 Q26,25 20,25 Q14,25 8,30Z"/><circle cx="20" cy="95" r="6"/><path d="M8,108 Q4,126 8,146 Q14,156 20,156 Q26,156 32,146 Q36,126 32,108 Q26,103 20,103 Q14,103 8,108Z"/><circle cx="20" cy="172" r="4"/><ellipse cx="20" cy="186" rx="6" ry="8"/></g></svg></div>
    <div class="orn-side right"><svg viewBox="0 0 40 200" width="10mm" height="55mm"><g fill="#b8962e" opacity=".8"><ellipse cx="20" cy="8" rx="6" ry="8"/><circle cx="20" cy="22" r="4"/><path d="M8,30 Q4,48 8,68 Q14,78 20,78 Q26,78 32,68 Q36,48 32,30 Q26,25 20,25 Q14,25 8,30Z"/><circle cx="20" cy="95" r="6"/><path d="M8,108 Q4,126 8,146 Q14,156 20,156 Q26,156 32,146 Q36,126 32,108 Q26,103 20,103 Q14,103 8,108Z"/><circle cx="20" cy="172" r="4"/><ellipse cx="20" cy="186" rx="6" ry="8"/></g></svg></div>
    @if(file_exists(public_path('images/logo.png')))<img class="watermark" src="{{ public_path('images/logo.png') }}" alt="">@endif

    <div class="cert-body">

        {{-- ══ GRUP ATAS ══ --}}
        <div class="cert-top">
            <div class="logo-wrap">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo">
                @elseif(file_exists(public_path('images/bkpsdm.png')))
                    <img src="{{ asset('images/bkpsdm.png') }}" alt="Logo BKPSDM">
                @else
                    <div class="logo-ph"><span style="font-size:7pt;color:#b8962e;font-weight:bold;">LOGO</span></div>
                @endif
            </div>
            <div class="inst-1">PEMERINTAH KOTA YOGYAKARTA</div>
            <div class="inst-2">BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA</div>
            <div class="inst-3">ꦧꦢꦤ꧀ꦏꦼꦥꦼꦒꦮꦲꦤ꧀ꦭꦤ꧀ꦥꦼꦔꦼꦩ꧀ꦧꦔꦤ꧀ꦱꦸꦩ꧀ꦧꦼꦂꦢꦪꦩꦤꦸꦁꦱ</div>
            <div class="rule-gold"></div>
            <div class="cert-title">Sertifikat</div>
            <div class="cert-subtitle">Hasil Penilaian Potensi dan Kompetensi Pegawai</div>
            <div class="rule-thin"></div>
        </div>

        {{-- ══ GRUP TENGAH ══ --}}
        <div class="cert-middle">
            <div class="cert-nomor">Nomor : {{ $peserta->nomor_sertifikat }}</div>
            <div class="diberikan">Diberikan kepada :</div>
            <div class="nama-besar">{{ $peserta->nama }}</div>
            <div class="nama-ul"></div>
            <table class="data-tbl">
                <tr>
                    <td class="lbl">NIP</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $peserta->nip }}</td>
                </tr>
                <tr>
                    <td class="lbl">Jabatan</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $peserta->jabatan ?? '–' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Perangkat Daerah</td>
                    <td class="sep">:</td>
                    <td class="val">{{ $peserta->perangkat_daerah ?? '–' }}</td>
                </tr>
            </table>
        </div>

        {{-- ══ GRUP BAWAH ══ --}}
        <div class="cert-bottom">
            <div class="partisipasi">
                Sebagai Peserta Pemetaan/Penilaian Kompetensi Manajerial dan Sosial Kultural
                dengan Metode <strong>{{ $kegiatan->jenis_kegiatan ?? '......' }}</strong>
                yang dilaksanakan pada tanggal
                <strong>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->isoFormat('D MMMM Y') }}</strong>
                s.d. <strong>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->isoFormat('D MMMM Y') }}</strong>
                di <strong>{{ $kegiatan->tempat }}</strong>.
            </div>
            <div class="berlaku-center">
                <span class="berlaku-strip">Sertifikat ini berlaku untuk jangka waktu 3 (tiga) tahun</span>
            </div>

            {{-- ══ TTD + BARCODE HALAMAN 1 ══
                 - Didorong ke bawah dengan margin-top: auto + padding-top di .ttd-barcode-wrap
                 - Barcode kanan DIHAPUS
                 - TTD digital dikomentari, diganti barcode2.jpeg di tengah
                 - Teks TTD tetap center
            ══ --}}
            <div class="ttd-barcode-wrap">
                <div class="ttd-block">
                    <div class="ttd-kota">{{ $kegiatan->tempat }}, {{ \Carbon\Carbon::parse($peserta->tanggal_sertifikat ?? $kegiatan->tanggal_selesai)->isoFormat('D MMMM Y') }}</div>
                    <div class="ttd-jabatan">{{ $kegiatan->jabatan_kepala ?? 'Kepala,' }}</div>

                    {{-- TTD DIGITAL DIKOMENTARI --}}
                    {{-- <div class="ttd-space">
                        @if($kegiatan->ttd_base64)
                            <img src="{{ $kegiatan->ttd_base64 }}" style="height:11mm;width:auto;object-fit:contain;display:block;margin:0 auto;">
                        @endif
                    </div> --}}

                    {{-- BARCODE2.JPEG ditampilkan di tengah sebagai pengganti TTD digital --}}
                    <div class="barcode-center-box">
                        <img src="{{ asset('images/barcode2.jpeg') }}" alt="Barcode">
                    </div>

                    <div class="ttd-nama">{{ $kegiatan->nama_kepala ?? '.................................' }}</div>
                    <div class="ttd-nip" style="color:#7a5200;">{{ $kegiatan->pangkat_kepala }}</div>
                    <div class="ttd-nip" style="color:#7a5200;">NIP. {{ $kegiatan->nip_kepala }}</div>
                </div>

                {{-- BARCODE KANAN DIHAPUS --}}
            </div>
        </div>

    </div>
</div>

{{-- ════ HALAMAN 2+ ════ --}}
@foreach($pages as $pageIdx => $pagePair)
<div class="page">
    <div class="wave-tl"><svg viewBox="0 0 378 283" xmlns="http://www.w3.org/2000/svg" width="105mm" height="80mm"><defs><linearGradient id="wg2{{ $pageIdx }}" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#0d3b8c"/><stop offset="100%" stop-color="#0c2461"/></linearGradient></defs><path d="M0,0 L378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283 Z" fill="url(#wg2{{ $pageIdx }})"/><path d="M378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283" fill="none" stroke="#b8962e" stroke-width="8"/><path d="M378,20 Q305,52 232,103 Q158,163 108,220 Q64,260 8,278" fill="none" stroke="#f0d47a" stroke-width="3" opacity=".65"/></svg></div>
    <div class="wave-br"><svg viewBox="0 0 378 283" xmlns="http://www.w3.org/2000/svg" width="105mm" height="80mm"><path d="M0,0 L378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283 Z" fill="#0c2461"/><path d="M378,0 Q310,28 240,78 Q168,138 120,198 Q75,245 0,283" fill="none" stroke="#b8962e" stroke-width="8"/><path d="M378,20 Q305,52 232,103 Q158,163 108,220 Q64,260 8,278" fill="none" stroke="#f0d47a" stroke-width="3" opacity=".65"/></svg></div>
    <div class="border-outer"></div>
    <div class="border-inner"></div>
    <div class="orn-side left"><svg viewBox="0 0 40 200" width="10mm" height="55mm"><g fill="#b8962e" opacity=".8"><ellipse cx="20" cy="8" rx="6" ry="8"/><circle cx="20" cy="22" r="4"/><path d="M8,30 Q4,48 8,68 Q14,78 20,78 Q26,78 32,68 Q36,48 32,30 Q26,25 20,25 Q14,25 8,30Z"/><circle cx="20" cy="95" r="6"/><path d="M8,108 Q4,126 8,146 Q14,156 20,156 Q26,156 32,146 Q36,126 32,108 Q26,103 20,103 Q14,103 8,108Z"/><circle cx="20" cy="172" r="4"/><ellipse cx="20" cy="186" rx="6" ry="8"/></g></svg></div>
    <div class="orn-side right"><svg viewBox="0 0 40 200" width="10mm" height="55mm"><g fill="#b8962e" opacity=".8"><ellipse cx="20" cy="8" rx="6" ry="8"/><circle cx="20" cy="22" r="4"/><path d="M8,30 Q4,48 8,68 Q14,78 20,78 Q26,78 32,68 Q36,48 32,30 Q26,25 20,25 Q14,25 8,30Z"/><circle cx="20" cy="95" r="6"/><path d="M8,108 Q4,126 8,146 Q14,156 20,156 Q26,156 32,146 Q36,126 32,108 Q26,103 20,103 Q14,103 8,108Z"/><circle cx="20" cy="172" r="4"/><ellipse cx="20" cy="186" rx="6" ry="8"/></g></svg></div>

    @php $punyaAspek = collect($pagePair)->contains(fn($h) => $h['items']->isNotEmpty()); @endphp

    @if($punyaAspek)
    {{-- Mode lama: tabel aspek detail --}}
    <div class="hasil-body">
        @foreach($pagePair as $colIdx => $hasilItem)
        @php $jt=$hasilItem['jenis_tes']; $items=$hasilItem['items']; $ringkasan=$hasilItem['ringkasan']; @endphp
        @if($colIdx > 0)<div class="vdivider"></div>@endif
        <div class="tes-col">
            <div class="tes-header-title">{{ $jt->nama }}</div>
            <table class="tes-table">
                <thead><tr><th class="td-no">No.</th><th style="text-align:left;">{{ $jt->header_col1 }}</th><th class="td-nilai">{{ $jt->header_col2 }}</th></tr></thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td class="td-no">{{ $item->nomor }}</td>
                        <td>{{ $item->nama_item }}</td>
                        <td class="td-nilai">@if($jt->kode==='potensi'||str_starts_with($jt->kode,'teks_')){{ $item->nilai_text }}@else{{ $item->nilai_angka!==null?number_format($item->nilai_angka,2):'' }}@endif</td>
                    </tr>
                    @endforeach
                </tbody>
                @if($ringkasan)
                <tfoot>
                    @if($jt->has_rekomendasi && $ringkasan->rekomendasi)<tr class="tfoot-row"><td colspan="2" style="text-align:center;">Rekomendasi</td><td class="td-nilai">{{ $ringkasan->rekomendasi }}</td></tr>@endif
                    @if($jt->has_jpm)
                        @if($ringkasan->jumlah!==null)<tr><td colspan="2" style="background:#f2f2f2;font-weight:700;color:#333;text-align:right;padding-right:3mm;">Jumlah</td><td style="background:#f2f2f2;font-weight:700;color:#0c2461;text-align:center;">{{ number_format($ringkasan->jumlah,2) }}</td></tr>@endif
                        @if($ringkasan->jpm!==null)<tr class="tfoot-row"><td colspan="2" style="text-align:center;">Job Person Match (JPM)</td><td class="td-nilai">{{ number_format($ringkasan->jpm,2) }}%</td></tr>@endif
                        @if($ringkasan->kategori)<tr class="tfoot-row"><td colspan="2" style="text-align:center;">Kategori</td><td class="td-nilai">{{ $ringkasan->kategori }}</td></tr>@endif
                    @endif
                </tfoot>
                @endif
            </table>
            @if($jt->kode==='potensi')<div class="ket-note">Ket: KS = Kurang Sekali &nbsp;|&nbsp; K = Kurang &nbsp;|&nbsp; C = Cukup &nbsp;|&nbsp; B = Baik &nbsp;|&nbsp; BS = Baik Sekali</div>@endif
        </div>
        @endforeach
        @if(count($pagePair)===1)<div class="vdivider"></div><div style="flex:1;"></div>@endif
    </div>

    @else
    {{-- ══ MODE RINGKASAN ══ --}}
    <div class="rkm-body">
        <div class="rkm-page-title" style="color: #001446">Hasil Pemetaan / Penilaian Kompetensi Manajerial dan Sosial Kultural</div>
        <div class="rkm-nama">{{ $peserta->nama }}</div>
        <div class="rkm-info">
            NIP. {{ $peserta->nip }}
            &nbsp;·&nbsp; {{ $peserta->jabatan ?? '' }}{{ $peserta->level_jabatan ? ' (Level '.$peserta->level_jabatan.')' : '' }}
            &nbsp;·&nbsp; {{ $peserta->perangkat_daerah ?? '' }}
        </div>
        <div class="rkm-rule"></div>
        <table class="rkm-table">
            <thead>
                <tr>
                    <th style="width:14mm; border-right:2px solid #b8962e;">NO.</th>
                    <th class="th-left" style="border-right:2px solid #b8962e;">PENILAIAN</th>
                    <th>
                        KATEGORI / HASIL
                        <span style="display:block; font-size:8pt; font-weight:400; font-style:italic; color:rgba(240,212,122,.85); margin-top:1px; letter-spacing:0;">(Capaian Akhir)</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rowNo = 1;
                    $sortedPair = collect($pagePair)->sortBy(fn($h) => $h['jenis_tes']->kode === 'kompetensi' ? 0 : 1)->values();
                @endphp
                @foreach($sortedPair as $hasilItem)
                @php $jt = $hasilItem['jenis_tes']; $ringkasan = $hasilItem['ringkasan']; @endphp
                @if($ringkasan)
                <tr>
                    <td class="rkm-no">{{ $rowNo++ }}</td>
                    <td class="rkm-penilaian">
                        @if($jt->kode === 'kompetensi')
                            Kompetensi Manajerial<br>dan Sosial Kultural
                            @if($peserta->level_jabatan)
                                <span class="rkm-level-sub">(Level {{ $peserta->level_jabatan }})</span>
                            @endif
                        @elseif($jt->kode === 'potensi')
                            Potensi
                        @else
                            {{ $jt->nama }}
                        @endif
                    </td>
                    <td class="rkm-hasil">
                        @if($jt->kode === 'kompetensi')
                            @if($ringkasan->kategori)<span class="rkm-hasil-utama">{{ $ringkasan->kategori }}</span>@endif
                            @php $standarTampil = $ringkasan->standar ?? 18; @endphp
                            @if($ringkasan->jumlah !== null)<span class="rkm-detail">Skor {{ number_format($ringkasan->jumlah, 2) }} dari standar {{ number_format($standarTampil, 0) }}</span>@endif
                            @if($ringkasan->jpm !== null)<span class="rkm-detail">Job Person Match (JPM) {{ number_format($ringkasan->jpm, 2) }}%</span>@endif
                        @elseif($jt->kode === 'potensi')
                            @if($ringkasan->rekomendasi)<span class="rkm-hasil-utama">{{ $ringkasan->rekomendasi }}</span>@endif
                        @else
                            @if($ringkasan->kategori)<span class="rkm-hasil-utama">{{ $ringkasan->kategori }}</span>@endif
                            @if($ringkasan->rekomendasi)<span class="rkm-detail">{{ $ringkasan->rekomendasi }}</span>@endif
                            @if($ringkasan->jumlah !== null)<span class="rkm-detail">Skor: {{ number_format($ringkasan->jumlah, 2) }}</span>@endif
                            @if($ringkasan->jpm !== null)<span class="rkm-detail">JPM: {{ number_format($ringkasan->jpm, 2) }}%</span>@endif
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        {{-- ══ FOOTER HALAMAN 2 ══
             TTD DISEMBUNYIKAN (dikomentari).
             Barcode2.jpeg ditampilkan center DI ATAS garis.
        ══ --}}
        <div class="h2-footer">
            {{-- BARCODE2.JPEG CENTER DI ATAS GARIS --}}
            {{-- <div class="h2-barcode-center">
                <img src="{{ asset('images/barcode2.jpeg') }}" alt="Barcode">
            </div> --}}

            {{-- GARIS PEMISAH --}}
            <div class="h2-footer-line" style="margin-top: 3mm;"></div>

            {{-- TTD DISEMBUNYIKAN --}}
            {{-- <div style="display: flex; justify-content: space-between; align-items: flex-end; width: 100%;">
                <div class="h2-ttd">
                    <div class="ttd-kota">{{ $kegiatan->tempat }}, {{ \Carbon\Carbon::parse($peserta->tanggal_sertifikat ?? $kegiatan->tanggal_selesai)->isoFormat('D MMMM Y') }}</div>
                    <div class="ttd-jabatan">{{ $kegiatan->jabatan_kepala ?? 'Kepala,' }}</div>
                    <div class="ttd-space">
                        @if($kegiatan->ttd_base64)
                            <img src="{{ $kegiatan->ttd_base64 }}" style="height:10mm;width:auto;object-fit:contain;display:block;margin:0 auto;">
                        @endif
                    </div>
                    <div class="ttd-nama">{{ $kegiatan->nama_kepala ?? '.................................' }}</div>
                    <div class="ttd-nip" style="color:#7a5200;">{{ $kegiatan->pangkat_kepala }}</div>
                    <div class="ttd-nip" style="color:#7a5200;">NIP. {{ $kegiatan->nip_kepala }}</div>
                </div>
                <div class="h2-barcode">
                    @if(file_exists(public_path('images/barcode.png')))
                        <img src="{{ asset('images/barcode.png') }}" alt="Barcode">
                    @else
                        <div style="text-align:center;padding:4mm;"><div style="font-size:20pt;color:#b8962e;">▦</div><div style="font-size:8pt;color:#888;margin-top:1mm;font-family:sans-serif;">barcode.png</div></div>
                    @endif
                </div>
            </div> --}}
        </div>
    </div>
    @endif
</div>
@endforeach

</body>
</html>