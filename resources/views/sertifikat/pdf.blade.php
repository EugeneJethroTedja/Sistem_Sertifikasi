<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Times New Roman', Times, serif;
    background: #fff;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

/* ── A4 Landscape Page ── */
.page {
    width: 277mm;
    min-height: 190mm;
    background: #fff;
    position: relative;
    overflow: hidden;
    page-break-after: always;
}
.page:last-child { page-break-after: auto; }

/* ── Border ganda emas ── */
.border-outer {
    position: absolute;
    top: 5mm; left: 5mm; right: 5mm; bottom: 5mm;
    border: 2px solid #b8962e;
    z-index: 2;
}
.border-inner {
    position: absolute;
    top: 8mm; left: 8mm; right: 8mm; bottom: 8mm;
    border: 0.5px solid #d4af5a;
    z-index: 2;
}

/* ── Wave corner (DomPDF: div berlapis, clip-path) ── */
.wave-tl {
    position: absolute;
    top: 0; left: 0;
    width: 95mm; height: 75mm;
    background: #0c2461;
    clip-path: polygon(0 0, 100% 0, 70% 40%, 45% 65%, 22% 85%, 0 100%);
    z-index: 1;
}
.wave-br {
    position: absolute;
    bottom: 0; right: 0;
    width: 95mm; height: 75mm;
    background: #0c2461;
    clip-path: polygon(100% 100%, 0 100%, 30% 60%, 55% 35%, 78% 15%, 100% 0);
    z-index: 1;
}
/* Garis emas di tepi wave */
.wave-tl-line {
    position: absolute;
    top: 0; left: 0;
    width: 97mm; height: 77mm;
    border-right: 6px solid #b8962e;
    clip-path: polygon(0 0, 100% 0, 70% 40%, 45% 65%, 22% 85%, 0 100%);
    z-index: 2;
}
.wave-br-line {
    position: absolute;
    bottom: 0; right: 0;
    width: 97mm; height: 77mm;
    border-left: 6px solid #b8962e;
    clip-path: polygon(100% 100%, 0 100%, 30% 60%, 55% 35%, 78% 15%, 100% 0);
    z-index: 2;
}
/* Garis emas kedua (lebih tipis) */
.wave-tl-line2 {
    position: absolute;
    top: 0; left: 0;
    width: 100mm; height: 80mm;
    border-right: 2.5px solid #f0d47a;
    clip-path: polygon(0 0, 100% 0, 68% 42%, 43% 67%, 20% 87%, 0 100%);
    z-index: 2;
    opacity: 0.65;
}
.wave-br-line2 {
    position: absolute;
    bottom: 0; right: 0;
    width: 100mm; height: 80mm;
    border-left: 2.5px solid #f0d47a;
    clip-path: polygon(100% 100%, 0 100%, 32% 58%, 57% 33%, 80% 13%, 100% 0);
    z-index: 2;
    opacity: 0.65;
}

/* ──────────────────────────────────
   HALAMAN 1 – SERTIFIKAT
────────────────────────────────── */
.cert-body {
    position: relative;
    z-index: 10;
    text-align: center;
    /* padding: top kanan bawah kiri */
    /* bawah 16mm: ruang untuk note di luar border */
    padding: 5mm 24mm 16mm;
}

/* Logo — DIPERBESAR ke 33mm */
.logo-img { height: 33mm; width: auto; display: block; margin: 0 auto 2mm; }
.logo-ph {
    width: 33mm; height: 33mm;
    border: 2px solid #b8962e;
    border-radius: 50%;
    display: inline-block;
    margin-bottom: 2mm;
}

/* Header instansi — DISAMAKAN ukurannya */
.inst-1 { font-size: 12pt; font-weight: bold; color: #0c2461; letter-spacing: 1px; }
.inst-2 { font-size: 12pt; font-weight: bold; color: #0c2461; margin-top: 1px; }
.inst-3 { font-size: 9pt;  font-style: italic; color: #2d6a2d; margin-top: 2px; }

/* Garis emas */
.rule-gold {
    width: 100%; height: 1.5px;
    background: #b8962e;
    margin: 2mm 0;
    border: none;
}
.rule-thin {
    width: 55%; height: 0.5px;
    background: #b8962e;
    margin: 1mm auto;
    border: none;
}

/* Judul SERTIFIKAT — DIPERKECIL dari 28pt → 22pt */
.cert-title {
    font-size: 22pt;
    font-weight: bold;
    color: #0c2461;
    letter-spacing: 8px;
    text-transform: uppercase;
    margin: 0.5mm 0 0;
}
.cert-subtitle {
    font-size: 8.5pt;
    font-weight: bold;
    color: #5a4a1a;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 1mm;
}

/* Nomor */
.cert-nomor {
    display: inline-block;
    background: #0c2461;
    color: #f0d47a;
    font-size: 9.5pt;
    font-weight: bold;
    letter-spacing: 1px;
    padding: 1.5mm 10mm;
    margin-bottom: 2mm;
    border: 1px solid #b8962e;
}

/* "Diberikan kepada" — TAMBAH margin bawah agar tidak tabrakan nama */
.diberikan { font-style: italic; font-size: 10pt; color: #555; margin-bottom: 3mm; }

/* Nama peserta */
.nama-besar { font-size: 26pt; font-weight: bold; color: #0c2461; font-style: italic; }
.nama-ul {
    width: 70%; height: 0.8px;
    background: #b8962e;
    margin: 1mm auto 2mm;
}

/* Tabel data — FONT DIPERBESAR */
.data-tbl { width: 155mm; border-collapse: collapse; text-align: left; font-size: 11.5pt; margin: 0 auto 2mm; }
.data-tbl td { padding: 1mm 2mm; vertical-align: top; }
.data-tbl .lbl { width: 40mm; font-style: italic; color: #444; }
.data-tbl .sep { width: 5mm; color: #b8962e; font-weight: bold; }
.data-tbl .val { font-weight: bold; color: #0c2461; font-size: 12pt; }

/* Teks partisipasi */
.partisipasi {
    font-size: 10pt;
    text-align: justify;
    line-height: 1.7;
    width: 215mm;
    margin: 0 auto 1.5mm;
}

/* ── TTD — disejajarkan di tengah blok, geser dari wave ──
   DomPDF: gunakan text-align:right pada wrap, display:inline-block pada block,
   dan text-align:center di dalam block agar semua elemen (kota, jabatan, ttd, nama) sejajar */
.ttd-wrap {
    text-align: right;
    margin-top: 2mm;
    /* geser sedikit ke kiri dari tepi kanan agar tidak masuk wave */
    padding-right: 22mm;
}
.ttd-block {
    display: inline-block;
    text-align: center;
    width: 65mm;
    font-size: 10pt;
    line-height: 1.5;
}
.ttd-kota    { color: #222; }
.ttd-jabatan { font-weight: bold; color: #0c2461; }

/* Area TTD — diperkecil agar semua muat */
.ttd-space {
    height: 14mm;
    text-align: center;
}
.ttd-space img {
    height: 12mm;
    width: auto;
    display: block;
    margin: 0 auto;
}

/* Nama TTD — tanpa kotak, hanya garis atas emas, warna emas tua */
.ttd-nama {
    font-size: 10.5pt;
    font-weight: bold;
    color: #7a5200;
    border-top: 1.5px solid #b8962e;
    padding-top: 1.5mm;
    display: inline-block;
    margin-top: 1mm;
}

/* NIP & Pangkat — warna gelap agar terlihat */
.ttd-nip { font-size: 10pt; color: #222; font-weight: bold; }

/* ── Catatan masa berlaku ──
   POJOK KIRI BAWAH, di LUAR garis border (border-outer ada di bottom: 5mm)
   Jadi kita taruh di bottom: 1.5mm agar di bawah garis border */
.note-bawah {
    position: absolute;
    bottom: 1.5mm;
    left: 6mm;
    right: auto;
    font-style: italic;
    font-size: 8pt;
    color: #5a3e00;
    font-weight: bold;
    border-left: 2.5px solid #b8962e;
    padding-left: 2.5mm;
    white-space: nowrap;
    z-index: 20;
}

/* ──────────────────────────────────
   HALAMAN 2+ – HASIL TES
────────────────────────────────── */
.hasil-body {
    position: relative;
    z-index: 10;
    display: table;
    width: 100%;
    padding: 12mm 20mm 14mm;
}

.hasil-cols { display: table; width: 100%; table-layout: fixed; }
.hasil-col  { display: table-cell; vertical-align: top; padding: 0 4mm; }
.hasil-col:first-child { padding-left: 0; border-right: 1.5px solid #b8962e; padding-right: 6mm; }
.hasil-col:last-child  { padding-right: 0; padding-left: 6mm; }

.tes-header {
    text-align: center;
    font-size: 10.5pt;
    font-weight: bold;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    background: #0c2461;
    color: #f0d47a;
    padding: 2.5mm 3mm;
}

.tes-table { width: 100%; border-collapse: collapse; font-size: 9pt; }
.tes-table th {
    background: #1e3a70;
    color: #fff;
    font-size: 8.5pt;
    font-weight: bold;
    text-align: center;
    padding: 2mm 2.5mm;
    border: 1px solid #2a4a8a;
}
.tes-table td {
    padding: 1.8mm 2.5mm;
    border: 0.5px solid #c8c8c8;
    vertical-align: middle;
}
.tes-table .even td { background: #f5f7fd; }

.td-no    { text-align: center; width: 10mm; font-weight: bold; color: #0c2461; }
.td-nilai { text-align: center; width: 22mm; font-weight: bold; font-size: 10pt; color: #0c2461; }

.tfoot-sum td {
    background: #0c2461 !important;
    color: #fff !important;
    font-weight: bold;
    font-size: 8.5pt;
    text-align: center;
    padding: 2mm;
    border-color: #0c2461 !important;
}
.tfoot-sum .td-nilai { color: #f0d47a !important; font-size: 11pt; }
.tfoot-sub td { background: #f2f2f2 !important; font-weight: bold; text-align: center; padding: 1.8mm 2.5mm; }
.tfoot-sub .td-lbl { text-align: right !important; padding-right: 3mm !important; color: #333; }

.ket-note {
    font-style: italic;
    font-size: 7.5pt;
    color: #666;
    margin-top: 2mm;
}
</style>
</head>
<body>

{{-- ════════════════════════════════════
     HALAMAN 1 – SERTIFIKAT UTAMA
════════════════════════════════════ --}}
<div class="page">

    <div class="wave-tl"></div>
    <div class="wave-tl-line"></div>
    <div class="wave-tl-line2"></div>
    <div class="wave-br"></div>
    <div class="wave-br-line"></div>
    <div class="wave-br-line2"></div>
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <div class="cert-body">

        {{-- Logo —  diperbesar 33mm --}}
        @if(file_exists(public_path('images/logo.png')))
            <img class="logo-img" src="{{ public_path('images/logo.png') }}" alt="Logo">
        @elseif(file_exists(public_path('images/bkpsdm.png')))
            <img class="logo-img" src="{{ public_path('images/bkpsdm.png') }}" alt="Logo BKPSDM">
        @else
            <div class="logo-ph"></div>
        @endif

        <div class="inst-1">PEMERINTAH KOTA YOGYAKARTA</div>
        <div class="inst-2">BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA</div>
        <div class="inst-3">ꦧꦢꦤ꧀ꦏꦼꦥꦼꦒꦮꦲꦤ꧀ꦭꦤ꧀ꦥꦼꦔꦼꦩ꧀ꦧꦔꦤ꧀ꦱꦸꦩ꧀ꦧꦼꦂꦢꦪꦩꦤꦸꦁꦱ</div>

        <hr class="rule-gold">

        <div class="cert-title">S E R T I F I K A T</div>
        <div class="cert-subtitle">Hasil Penilaian Potensi dan Kompetensi Pegawai</div>

        <hr class="rule-thin">

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

        <div class="partisipasi">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sebagai/atas partisipasinya dalam
            <strong>{{ $kegiatan->nama_kegiatan }}</strong>
            yang diselenggarakan oleh <strong>{{ $kegiatan->penyelenggara }}</strong>
            dari tanggal <strong>{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->isoFormat('D MMMM Y') }}</strong>
            s.d. <strong>{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->isoFormat('D MMMM Y') }}</strong>
            bertempat di <strong>{{ $kegiatan->tempat }}</strong>.
        </div>

        {{-- TTD: text-align:right pada wrap, block di dalam centered --}}
        <div class="ttd-wrap">
            <div class="ttd-block">
                <div class="ttd-kota">
                    {{ $kegiatan->tempat }}, {{ \Carbon\Carbon::parse($peserta->tanggal_sertifikat ?? $kegiatan->tanggal_selesai)->isoFormat('D MMMM Y') }}
                </div>
                <div class="ttd-jabatan">{{ $kegiatan->jabatan_kepala ?? 'Kepala,' }}</div>
                <div class="ttd-space">
                    @if($kegiatan->ttd_base64)
                        <img src="{{ $kegiatan->ttd_base64 }}" alt="TTD">
                    @endif
                </div>
                <div class="ttd-nama">{{ $kegiatan->nama_kepala ?? '.................................' }}</div>
                <div class="ttd-nip">{{ $kegiatan->pangkat_kepala }}</div>
                <div class="ttd-nip">NIP. {{ $kegiatan->nip_kepala }}</div>
            </div>
        </div>

    </div>

    {{-- Masa berlaku: pojok kiri bawah, di luar garis border --}}
    <div class="note-bawah">
        ★ Masa berlaku hasil penilaian ini selama 3 (tiga) tahun dihitung dari tanggal pelaksanaan &nbsp;(berlaku s.d. {{ $masaBerlakuSampai }})
    </div>
</div>

{{-- ════════════════════════════════════
     HALAMAN 2+ – HASIL TES
════════════════════════════════════ --}}
@foreach($pages as $pageIdx => $pagePair)
<div class="page">

    <div class="wave-tl"></div>
    <div class="wave-tl-line"></div>
    <div class="wave-tl-line2"></div>
    <div class="wave-br"></div>
    <div class="wave-br-line"></div>
    <div class="wave-br-line2"></div>
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <div class="hasil-body">
        <div class="hasil-cols">

            @foreach($pagePair as $colIdx => $hasilItem)
            @php
                $jt        = $hasilItem['jenis_tes'];
                $items     = $hasilItem['items'];
                $ringkasan = $hasilItem['ringkasan'];
            @endphp
            <div class="hasil-col">
                <div class="tes-header">{{ $jt->nama }}</div>
                <table class="tes-table">
                    <thead>
                        <tr>
                            <th class="td-no">No.</th>
                            <th style="text-align:left;">{{ $jt->header_col1 }}</th>
                            <th class="td-nilai">{{ $jt->header_col2 }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $item)
                        <tr class="{{ $i % 2 === 1 ? 'even' : '' }}">
                            <td class="td-no">{{ $item->nomor }}</td>
                            <td>{{ $item->nama_item }}</td>
                            <td class="td-nilai">
                                @if($jt->kode === 'potensi')
                                    {{ $item->nilai_text }}
                                @else
                                    {{ $item->nilai_angka !== null ? number_format($item->nilai_angka, 2) : '' }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if($ringkasan)
                    <tfoot>
                        @if($jt->has_rekomendasi && $ringkasan->rekomendasi)
                        <tr class="tfoot-sum">
                            <td colspan="2" style="text-align:center;">Rekomendasi</td>
                            <td class="td-nilai">{{ $ringkasan->rekomendasi }}</td>
                        </tr>
                        @endif
                        @if($jt->has_jpm)
                            @if($ringkasan->jumlah !== null)
                            <tr class="tfoot-sub">
                                <td colspan="2" class="td-lbl">Jumlah</td>
                                <td class="td-nilai" style="color:#0c2461;">{{ number_format($ringkasan->jumlah, 2) }}</td>
                            </tr>
                            @endif
                            @if($ringkasan->jpm !== null)
                            <tr class="tfoot-sum">
                                <td colspan="2">Job Person Match (JPM)</td>
                                <td class="td-nilai">{{ number_format($ringkasan->jpm, 2) }}%</td>
                            </tr>
                            @endif
                            @if($ringkasan->kategori)
                            <tr class="tfoot-sum">
                                <td colspan="2">Kategori</td>
                                <td class="td-nilai">{{ $ringkasan->kategori }}</td>
                            </tr>
                            @endif
                        @endif
                    </tfoot>
                    @endif
                </table>

                @if($jt->kode === 'potensi')
                <div class="ket-note">Ket: KS = Kurang Sekali &nbsp;| K = Kurang &nbsp;| C = Cukup &nbsp;| B = Baik &nbsp;| BS = Baik Sekali</div>
                @endif
            </div>
            @endforeach

            @if(count($pagePair) === 1)
            <div class="hasil-col"></div>
            @endif

        </div>
    </div>

    {{-- Masa berlaku: pojok kiri bawah, di luar garis border --}}
    <div class="note-bawah">
        ★ Masa berlaku hasil penilaian ini selama 3 (tiga) tahun dihitung dari tanggal pelaksanaan &nbsp;(berlaku s.d. {{ $masaBerlakuSampai }})
    </div>
</div>
@endforeach

</body>
</html>