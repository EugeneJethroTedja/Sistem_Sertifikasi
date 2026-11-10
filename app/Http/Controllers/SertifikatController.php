<?php

namespace App\Http\Controllers;

use App\Models\{Peserta, Kegiatan, JenisTes, HasilTes, RingkasanTes};
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SertifikatController extends Controller
{
    /**
     * Preview sertifikat di browser
     */
    public function preview(Kegiatan $kegiatan, Peserta $peserta)
    {
        $data = $this->buildData($kegiatan, $peserta);
        return view('sertifikat.preview', $data);
    }

    /**
     * Download sertifikat sebagai PDF
     */
    public function download(Kegiatan $kegiatan, Peserta $peserta)
    {
        $data = $this->buildData($kegiatan, $peserta);
        $filename = 'Sertifikat_' . str_replace(['/', ' '], ['_', '_'], $peserta->nomor_sertifikat) . '.pdf';

        $pdf = Pdf::loadView('sertifikat.pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'Times New Roman',
                'dpi'                  => 150,
            ]);

        return $pdf->download($filename);
    }

    /**
     * Download semua sertifikat dalam satu kegiatan (batch)
     */
    public function downloadAll(Kegiatan $kegiatan)
    {
        // Untuk batch, kita redirect dengan pesan agar user download satu per satu
        // atau bisa diimplementasikan dengan ZIP jika butuh
        $pesertaList = $kegiatan->peserta;
        $allData = [];

        foreach ($pesertaList as $peserta) {
            $allData[] = $this->buildData($kegiatan, $peserta);
        }

        $pdf = Pdf::loadView('sertifikat.pdf_batch', ['allData' => $allData, 'kegiatan' => $kegiatan])
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'Times New Roman',
            ]);

        $filename = 'Sertifikat_' . str_replace(' ', '_', $kegiatan->nama_kegiatan) . '.pdf';
        return $pdf->download($filename);
    }

    // private function buildData(Kegiatan $kegiatan, Peserta $peserta): array
    // {
    //     // ← filter jenis tes untuk kegiatan ini saja
    //     $jenisTesAll = JenisTes::where(function($q) use ($kegiatan) {
    //             $q->whereNull('kegiatan_id')
    //             ->orWhere('kegiatan_id', $kegiatan->id);
    //         })
    //         ->orderBy('urutan')
    //         ->get();

    //     $hasilPerJenis = [];

    //     foreach ($jenisTesAll as $jt) {
    //         $items = HasilTes::where('peserta_id', $peserta->id)
    //                     ->where('jenis_tes_id', $jt->id)
    //                     ->orderBy('nomor')
    //                     ->get();

    //         $ringkasan = RingkasanTes::where('peserta_id', $peserta->id)
    //                     ->where('jenis_tes_id', $jt->id)
    //                     ->first();

    //         if ($items->isNotEmpty()) {
    //             $hasilPerJenis[] = [
    //                 'jenis_tes' => $jt,
    //                 'items'     => $items,
    //                 'ringkasan' => $ringkasan,
    //             ];
    //         }
    //     }

    //     $pages = array_chunk($hasilPerJenis, 2);
    //     $masaBerlakuSampai = Carbon::parse($kegiatan->tanggal_selesai)->addYears(3)->isoFormat('D MMMM Y');

    //     return compact('kegiatan', 'peserta', 'hasilPerJenis', 'pages', 'masaBerlakuSampai');
    // }

    private function buildData(Kegiatan $kegiatan, Peserta $peserta): array
    {
        $jenisTesAll = JenisTes::where(function($q) use ($kegiatan) {
                $q->whereNull('kegiatan_id')
                ->orWhere('kegiatan_id', $kegiatan->id);
            })
            ->orderBy('urutan')
            ->get();

        $hasilPerJenis = [];

        foreach ($jenisTesAll as $jt) {
            $items = HasilTes::where('peserta_id', $peserta->id)
                        ->where('jenis_tes_id', $jt->id)
                        ->orderBy('nomor')
                        ->get();

            $ringkasan = RingkasanTes::where('peserta_id', $peserta->id)
                        ->where('jenis_tes_id', $jt->id)
                        ->first();

            // ── FIX: masukkan ke hasilPerJenis jika ADA ITEMS **atau** ADA RINGKASAN ──
            // Sebelumnya: hanya if ($items->isNotEmpty()) → ringkasan-only tidak muncul
            if ($items->isNotEmpty() || $ringkasan !== null) {
                $hasilPerJenis[] = [
                    'jenis_tes' => $jt,
                    'items'     => $items,
                    'ringkasan' => $ringkasan,
                ];
            }
        }

        $pages = array_chunk($hasilPerJenis, 2);
        $masaBerlakuSampai = Carbon::parse($kegiatan->tanggal_selesai)->addYears(3)->isoFormat('D MMMM Y');

        return compact('kegiatan', 'peserta', 'hasilPerJenis', 'pages', 'masaBerlakuSampai');
    }
}