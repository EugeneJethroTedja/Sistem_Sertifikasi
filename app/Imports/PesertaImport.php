<?php

namespace App\Imports;

use App\Models\{Kegiatan, Peserta, JenisTes, HasilTes, RingkasanTes};
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PesertaImport implements ToCollection, SkipsOnError
{
    use SkipsErrors;

    public function __construct(protected Kegiatan $kegiatan) {}

    public function collection(Collection $rows)
    {
        if ($rows->count() < 3) return;

        $rawHeaders = $rows[1]->toArray();

        $normalizedHeaders = array_map(function ($h) {
            return $this->normalizeKey((string) $h);
        }, $rawHeaders);

        // ── Cari posisi index penting SEKALI di luar loop ─────────
        $idxRekomendasi  = $this->findHeaderIndex($rawHeaders, 'Rekomendasi');
        $idxLevelJabatan = $this->findHeaderIndex($rawHeaders, 'Level Jabatan');
        $idxJumlah       = $this->findHeaderIndex($rawHeaders, 'Jumlah');
        $idxJpm          = $this->findHeaderIndex($rawHeaders, 'JPM');
        $idxKategori     = $this->findHeaderIndex($rawHeaders, 'Kategori');

        DB::transaction(function () use (
            $rows, $rawHeaders, $normalizedHeaders,
            $idxRekomendasi, $idxLevelJabatan, $idxJumlah, $idxJpm, $idxKategori
        ) {
            // ── FIX UTAMA: Bersihkan duplikat jenis_tes dulu ──────
            $this->deduplicateJenisTes();

            // ── Buat/ambil JenisTes SEKALI sebelum loop peserta ───
            $jenisTesPotensi = $this->getOrCreateJenisTes(
                'potensi', 'Hasil Potensi', 'Aspek', 'Skala',
                hasRekomendasi: true, hasJpm: false, urutan: 1
            );
            $jenisTesKomp = $this->getOrCreateJenisTes(
                'kompetensi', 'Hasil Kompetensi', 'Aspek', 'Nilai',
                hasRekomendasi: false, hasJpm: true, urutan: 2
            );

            foreach ($rows->slice(2) as $row) {
                $rawArr = $row->toArray();

                $n = [];
                foreach ($rawArr as $idx => $val) {
                    $normKey = $normalizedHeaders[$idx] ?? '';
                    if ($normKey !== '') {
                        $n[$normKey] = $val;
                    }
                }

                $nama = trim($n['nama'] ?? '');
                $nip  = trim((string) ($n['nip'] ?? ''));
                if (empty($nama) || empty($nip)) continue;

                // ── 1. Simpan Peserta ──────────────────────────────
                $nomorUrut = $this->extractNomorUrut($n);

                $peserta = Peserta::updateOrCreate(
                    [
                        'kegiatan_id' => $this->kegiatan->id,
                        'nip'         => $nip,
                    ],
                    [
                        'nomor_urut'       => $nomorUrut,
                        'nama'             => $nama,
                        'jabatan'          => trim($n['jabatan'] ?? ''),
                        'perangkat_daerah' => trim($n['unit_kerja'] ?? $n['perangkat_daerah'] ?? ''),
                        'pangkat'          => null,
                        'golongan'         => null,
                    ]
                );

                $k = $this->kegiatan;
                $peserta->nomor_sertifikat   = "800.1.14.2/{$nomorUrut}/{$k->bulan_kegiatan}/{$k->tahun_kegiatan}";
                $peserta->tanggal_sertifikat = $k->tanggal_selesai;
                $peserta->save();

                // ── 2. Deteksi kolom Potensi & Kompetensi dinamis ─
                $potensiAspek    = [];
                $kompetensiAspek = [];
                $potensiRek      = null;
                $kompetensiRingkasan = ['jumlah' => null, 'jpm' => null, 'kategori' => null];

                foreach ($rawHeaders as $idx => $header) {
                    $header = trim((string) $header);
                    $val    = $rawArr[$idx] ?? null;

                    if (empty($header)) continue;

                    if (str_starts_with($header, 'Potensi ')) {
                        $potensiAspek[] = ['nama' => $header, 'nilai' => $val];
                        continue;
                    }

                    if ($header === 'Rekomendasi' && $idx === $idxRekomendasi) {
                        $potensiRek = $val;
                        continue;
                    }

                    if (
                        $idxLevelJabatan !== null && $idxJumlah !== null
                        && $idx > $idxLevelJabatan && $idx < $idxJumlah
                    ) {
                        $kompetensiAspek[] = ['nama' => $header, 'nilai' => $val];
                        continue;
                    }

                    if ($idx === $idxJumlah)  $kompetensiRingkasan['jumlah']   = $val;
                    if ($idx === $idxJpm)      $kompetensiRingkasan['jpm']      = $this->parseJpm($val);
                    if ($idx === $idxKategori) $kompetensiRingkasan['kategori'] = $val;
                }

                // ── 3. Simpan Hasil Potensi ────────────────────────
                HasilTes::where('peserta_id', $peserta->id)
                        ->where('jenis_tes_id', $jenisTesPotensi->id)
                        ->delete();

                $hasPotensi = false;
                foreach ($potensiAspek as $no => $aspek) {
                    if (is_null($aspek['nilai']) || trim((string) $aspek['nilai']) === '') continue;
                    HasilTes::create([
                        'peserta_id'   => $peserta->id,
                        'jenis_tes_id' => $jenisTesPotensi->id,
                        'nomor'        => $no + 1,
                        'nama_item'    => $aspek['nama'],
                        'nilai_text'   => strtoupper(trim((string) $aspek['nilai'])),
                        'nilai_angka'  => null,
                    ]);
                    $hasPotensi = true;
                }

                if ($hasPotensi) {
                    RingkasanTes::updateOrCreate(
                        ['peserta_id' => $peserta->id, 'jenis_tes_id' => $jenisTesPotensi->id],
                        ['rekomendasi' => $potensiRek]
                    );
                }

                // ── 4. Simpan Hasil Kompetensi ─────────────────────
                HasilTes::where('peserta_id', $peserta->id)
                        ->where('jenis_tes_id', $jenisTesKomp->id)
                        ->delete();

                $totalNilai = 0;
                $hasKomp    = false;
                foreach ($kompetensiAspek as $no => $aspek) {
                    if (is_null($aspek['nilai']) || !is_numeric($aspek['nilai'])) continue;
                    HasilTes::create([
                        'peserta_id'   => $peserta->id,
                        'jenis_tes_id' => $jenisTesKomp->id,
                        'nomor'        => $no + 1,
                        'nama_item'    => $aspek['nama'],
                        'nilai_text'   => null,
                        'nilai_angka'  => (float) $aspek['nilai'],
                    ]);
                    $totalNilai += (float) $aspek['nilai'];
                    $hasKomp = true;
                }

                if ($hasKomp) {
                    $jumlah = $kompetensiRingkasan['jumlah'] ?? $totalNilai;
                    RingkasanTes::updateOrCreate(
                        ['peserta_id' => $peserta->id, 'jenis_tes_id' => $jenisTesKomp->id],
                        [
                            'jumlah'   => is_numeric($jumlah) ? (float) $jumlah : $totalNilai,
                            'jpm'      => $kompetensiRingkasan['jpm'],
                            'kategori' => $kompetensiRingkasan['kategori'],
                        ]
                    );
                }
            }
        });
    }

    /**
     * Hapus duplikat jenis_tes untuk kegiatan ini.
     * Pertahankan record dengan ID terkecil (paling lama dibuat),
     * hapus sisanya beserta hasil_tes & ringkasan_tes yang mengarah ke ID duplikat.
     */
    private function deduplicateJenisTes(): void
    {
        $kodes = ['potensi', 'kompetensi'];

        foreach ($kodes as $kode) {
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

    // ── Helper: cari index header berdasarkan nama persis ────────
    private function findHeaderIndex(array $headers, string $nama): ?int
    {
        foreach ($headers as $idx => $h) {
            if (trim((string) $h) === $nama) return $idx;
        }
        return null;
    }

    // ── Helper: normalisasi key ───────────────────────────────────
    private function normalizeKey(string $key): string
    {
        $key = strtolower(trim($key));
        $key = preg_replace('/[\s\.\-\/&\(\)]+/', '_', $key);
        $key = preg_replace('/_+/', '_', $key);
        return trim($key, '_');
    }

    // ── Helper: ambil atau buat JenisTes ─────────────────────────
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

    // ── Helper: parse JPM "100,00%" → 100.00 ─────────────────────
    private function parseJpm($val): ?float
    {
        if (is_null($val)) return null;
        $clean = str_replace(['%', ',', ' '], ['', '.', ''], (string) $val);
        if (!is_numeric($clean)) return null;
        $num = (float) $clean;
        return $num <= 1 ? $num * 100 : $num;
    }

    // ── Helper: ambil nomor urut ──────────────────────────────────
    private function extractNomorUrut(array $n): int
    {
        $keys = ['no__urut', 'no_urut', 'nomor_urut', 'urut', 'no'];
        foreach ($keys as $key) {
            if (!empty($n[$key])) {
                $val = preg_replace('/[^0-9]/', '', (string) $n[$key]);
                if ($val !== '' && is_numeric($val)) return (int) $val;
            }
        }
        return $this->kegiatan->fresh()->getNextNomorUrut();
    }
}