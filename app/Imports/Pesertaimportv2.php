<?php

namespace App\Imports;

use App\Models\{Kegiatan, Peserta, JenisTes, HasilTes, RingkasanTes};
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Import peserta dari format ringkasan (tanpa aspek detail).
 *
 * Format kolom yang diharapkan (baris ke-4 = header, baris ke-5+ = data):
 *   NO | NAMA | NIP | JABATAN | UNIT KERJA | NAMA KEGIATAN | BULAN | TAHUN
 *   | REKOMENDASI POTENSI | LEVEL JABATAN | SKOR PEROLEHAN | STANDAR | JPM (%) | KATEGORI
 *
 * Baris 1 : Judul
 * Baris 2 : Kosong
 * Baris 3 : Group header (NO, NAMA, ..., HASIL POTENSI, ..., HASIL KOMPETENSI ...)
 * Baris 4 : Sub-header / nama kolom detail  ← dipakai sebagai header
 * Baris 5+ : Data peserta
 */
class PesertaImportV2 implements ToCollection, SkipsOnError
{
    use SkipsErrors;

    public function __construct(protected Kegiatan $kegiatan) {}

    public function collection(Collection $rows)
    {
        // Butuh minimal 5 baris (1 judul + 1 kosong + 1 group header + 1 sub-header + 1 data)
        if ($rows->count() < 5) return;

        // Gabungkan baris ke-3 (index 2) dan baris ke-4 (index 3) sebagai header
        // Kolom identitas (NAMA, NIP, dll) ada di row 2 karena merged cell
        // Kolom detail (REKOMENDASI POTENSI, JPM, dll) ada di row 3
        $groupHeaders = $rows[2]->toArray();
        $subHeaders   = $rows[3]->toArray();

        $rawHeaders = [];
        foreach ($groupHeaders as $idx => $val) {
            $sub   = trim((string) ($subHeaders[$idx] ?? ''));
            $group = trim((string) ($val ?? ''));
            $rawHeaders[$idx] = $sub !== '' ? $sub : $group;
        }

        // Petakan nama kolom ke index
        $map = $this->buildColumnMap($rawHeaders);

        // Pastikan kolom wajib ditemukan
        $required = ['nama', 'nip'];
        foreach ($required as $key) {
            if (!isset($map[$key])) {
                throw new \Exception("Kolom wajib '{$key}' tidak ditemukan di file Excel. Pastikan menggunakan Format Ringkasan yang benar (header di baris ke-4).");
            }
        }

        DB::transaction(function () use ($rows, $map) {
            $this->deduplicateJenisTes();

            $jenisTesPotensi = $this->getOrCreateJenisTes(
                'potensi', 'Hasil Potensi', 'Aspek', 'Skala',
                hasRekomendasi: true, hasJpm: false, urutan: 1
            );
            $jenisTesKomp = $this->getOrCreateJenisTes(
                'kompetensi', 'Kompetensi Manajerial dan Sosial Kultural', 'Aspek', 'Nilai',
                hasRekomendasi: false, hasJpm: true, urutan: 2
            );

            // Baris ke-5+ (index 4+) = data
            foreach ($rows->slice(4) as $row) {
                $rawArr = $row->toArray();

                $nama = trim((string) ($rawArr[$map['nama']] ?? ''));
                $nip  = trim((string) ($rawArr[$map['nip']]  ?? ''));
                if (empty($nama) || empty($nip)) continue;

                // ── 1. Simpan Peserta ──────────────────────────────
                $nomorUrut  = $this->resolveNomorUrut($rawArr, $map);
                // ── FIX: Ambil level_jabatan dari kolom LEVEL JABATAN ──
                $levelJabatan = isset($map['level_jabatan'])
                    ? trim((string) ($rawArr[$map['level_jabatan']] ?? ''))
                    : null;

                $peserta = Peserta::updateOrCreate(
                    [
                        'kegiatan_id' => $this->kegiatan->id,
                        'nip'         => $nip,
                    ],
                    [
                        'nomor_urut'       => $nomorUrut,
                        'nama'             => $nama,
                        'jabatan'          => trim((string) ($rawArr[$map['jabatan']]    ?? '')),
                        'level_jabatan'    => $levelJabatan !== '' ? $levelJabatan : null,  // ← BARU
                        'perangkat_daerah' => trim((string) ($rawArr[$map['unit_kerja']] ?? '')),
                        'pangkat'          => null,
                        'golongan'         => null,
                    ]
                );

                $k = $this->kegiatan;
                $peserta->nomor_sertifikat   = "800.1.14.2/{$nomorUrut}/{$k->bulan_kegiatan}/{$k->tahun_kegiatan}";
                $peserta->tanggal_sertifikat = $k->tanggal_selesai;
                $peserta->save();

                // ── 2. Simpan Ringkasan Potensi ────────────────────
                $rekPotensi = trim((string) ($rawArr[$map['rekomendasi_potensi']] ?? ''));

                if ($rekPotensi !== '') {
                    RingkasanTes::updateOrCreate(
                        ['peserta_id' => $peserta->id, 'jenis_tes_id' => $jenisTesPotensi->id],
                        ['rekomendasi' => $rekPotensi]
                    );
                }

                // ── 3. Simpan Ringkasan Kompetensi ─────────────────
                $skorRaw  = $rawArr[$map['skor_perolehan']] ?? null;
                $standar  = isset($map['standar']) ? ($rawArr[$map['standar']] ?? null) : null;
                $jpmRaw   = $rawArr[$map['jpm']]           ?? null;
                $kategori = trim((string) ($rawArr[$map['kategori']] ?? ''));

                $skor    = is_numeric($skorRaw) ? (float) $skorRaw : null;
                $standarVal = is_numeric($standar) ? (float) $standar : null;
                $jpm     = $this->parseJpm($jpmRaw);

                if ($skor !== null || $jpm !== null || $kategori !== '') {
                    RingkasanTes::updateOrCreate(
                        ['peserta_id' => $peserta->id, 'jenis_tes_id' => $jenisTesKomp->id],
                        [
                            'jumlah'   => $skor,
                            'standar'  => $standarVal,   // ← BARU: simpan nilai standar
                            'jpm'      => $jpm,
                            'kategori' => $kategori,
                        ]
                    );
                }
            }
        });
    }

    // ── Petakan nama kolom (baris header) ke index array ─────────
    private function buildColumnMap(array $headers): array
    {
        $map = [];

        // Daftar pasangan: [key_internal => [kemungkinan nama kolom di excel]]
        // Pakai contains (str_contains) bukan exact match agar lebih fleksibel
        $lookup = [
            'no'                  => ['no', 'no.', 'nomor'],
            'nama'                => ['nama'],
            'nip'                 => ['nip'],
            'jabatan'             => ['jabatan'],
            'unit_kerja'          => ['unit kerja', 'perangkat daerah', 'skpd'],
            'nama_kegiatan'       => ['nama kegiatan'],
            'bulan'               => ['bulan'],
            'tahun'               => ['tahun'],
            'rekomendasi_potensi' => ['rekomendasi potensi', 'rekomendasi'],
            'level_jabatan'       => ['level jabatan', 'level'],   // ← BARU
            'skor_perolehan'      => ['skor perolehan', 'skor', 'jumlah'],
            'standar'             => ['standar'],
            'jpm'                 => ['jpm (%)','jpm(%)', 'jpm'],
            'kategori'            => ['kategori'],
        ];

        foreach ($headers as $idx => $header) {
            // Normalisasi: lowercase, hapus newline, trim, collapse spasi ganda
            $normalized = strtolower(trim(preg_replace('/[\s]+/', ' ', str_replace(["\n", "\r"], ' ', (string) $header))));

            foreach ($lookup as $key => $candidates) {
                if (isset($map[$key])) continue;
                foreach ($candidates as $candidate) {
                    if ($normalized === $candidate || str_contains($normalized, $candidate)) {
                        $map[$key] = $idx;
                        break;
                    }
                }
            }
        }

        return $map;
    }

    // ── Ambil nomor urut dari kolom NO, fallback auto-generate ───
    private function resolveNomorUrut(array $rawArr, array $map): int
    {
        if (isset($map['no'])) {
            $val = preg_replace('/[^0-9]/', '', (string) ($rawArr[$map['no']] ?? ''));
            if ($val !== '' && is_numeric($val)) return (int) $val;
        }
        return $this->kegiatan->fresh()->getNextNomorUrut();
    }

    // ── Hapus duplikat jenis_tes untuk kegiatan ini ───────────────
    private function deduplicateJenisTes(): void
    {
        foreach (['potensi', 'kompetensi'] as $kode) {
            $records = JenisTes::where('kegiatan_id', $this->kegiatan->id)
                               ->where('kode', $kode)
                               ->orderBy('id')
                               ->get();

            if ($records->count() <= 1) continue;

            $dupIds = $records->slice(1)->pluck('id');
            HasilTes::whereIn('jenis_tes_id', $dupIds)->delete();
            RingkasanTes::whereIn('jenis_tes_id', $dupIds)->delete();
            JenisTes::whereIn('id', $dupIds)->delete();
        }
    }

    // ── Ambil atau buat JenisTes ──────────────────────────────────
    private function getOrCreateJenisTes(
        string $kode,
        string $nama,
        string $col1,
        string $col2,
        bool $hasRekomendasi,
        bool $hasJpm,
        int $urutan
    ): JenisTes {
        return JenisTes::firstOrCreate(
            [
                'kegiatan_id' => $this->kegiatan->id,
                'kode'        => $kode,
            ],
            [
                'nama'            => $nama,
                'urutan'          => $urutan,
                'header_col1'     => $col1,
                'header_col2'     => $col2,
                'has_rekomendasi' => $hasRekomendasi,
                'has_jpm'         => $hasJpm,
            ]
        );
    }

    // ── Parse JPM: "97,22%" atau 0.9722 → 97.22 ──────────────────
    private function parseJpm($val): ?float
    {
        if (is_null($val)) return null;
        $clean = str_replace(['%', ',', ' '], ['', '.', ''], (string) $val);
        if (!is_numeric($clean)) return null;
        $num = (float) $clean;
        return $num <= 1 ? $num * 100 : $num;
    }
}