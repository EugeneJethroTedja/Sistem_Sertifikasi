<?php

namespace App\Http\Controllers;

use App\Models\{Kegiatan, Peserta, JenisTes, HasilTes, RingkasanTes, MasterItemTes};
use App\Imports\PesertaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PesertaImportV2;  // ← tambah ini

class PesertaController extends Controller
{
    public function index(Kegiatan $kegiatan)
    {
        $peserta = $kegiatan->peserta()
                    ->orderBy('nomor_urut')
                    ->paginate(20);
        return view('peserta.index', compact('kegiatan', 'peserta'));
    }

    public function create(Kegiatan $kegiatan)
    {
        return view('peserta.create', compact('kegiatan'));
    }

    // public function store(Request $request, Kegiatan $kegiatan)
    // {
    //     $data = $request->validate([
    //         'nama'            => 'required|string|max:255',
    //         'nip'             => 'required|string|max:30',
    //         'jabatan'         => 'nullable|string|max:255',
    //         'pangkat'         => 'nullable|string|max:100',
    //         'golongan'        => 'nullable|string|max:20',
    //         'perangkat_daerah'=> 'nullable|string|max:255',
    //     ]);

    //     $data['kegiatan_id'] = $kegiatan->id;
    //     $data['nomor_urut']  = $kegiatan->getNextNomorUrut();

    //     $peserta = Peserta::create($data);

    //     return redirect()->route('peserta.show', [$kegiatan, $peserta])
    //         ->with('success', 'Peserta berhasil ditambahkan. Nomor Sertifikat: ' . $peserta->nomor_sertifikat);
    // }

    // public function show(Kegiatan $kegiatan, Peserta $peserta)
    // {
    //     // Ambil jenis tes khusus kegiatan ini saja
    //     $jenisTesAll = JenisTes::untukKegiatan($kegiatan->id)->get();

    //     $hasilPerJenis = [];
    //     foreach ($jenisTesAll as $jt) {
    //         $items     = HasilTes::where('peserta_id', $peserta->id)
    //                             ->where('jenis_tes_id', $jt->id)
    //                             ->orderBy('nomor')->get();
    //         $ringkasan = RingkasanTes::where('peserta_id', $peserta->id)
    //                                 ->where('jenis_tes_id', $jt->id)->first();
    //         $hasilPerJenis[] = [
    //             'jenis_tes' => $jt,
    //             'items'     => $items,
    //             'ringkasan' => $ringkasan,
    //         ];
    //     }

    //     return view('peserta.show', compact('kegiatan', 'peserta', 'hasilPerJenis', 'jenisTesAll'));
    // }

    public function edit(Kegiatan $kegiatan, Peserta $peserta)
    {
        return view('peserta.edit', compact('kegiatan', 'peserta'));
    }

    // public function update(Request $request, Kegiatan $kegiatan, Peserta $peserta)
    // {
    //     $data = $request->validate([
    //         'nama'            => 'required|string|max:255',
    //         'nip'             => 'required|string|max:30',
    //         'jabatan'         => 'nullable|string|max:255',
    //         'pangkat'         => 'nullable|string|max:100',
    //         'golongan'        => 'nullable|string|max:20',
    //         'perangkat_daerah'=> 'nullable|string|max:255',
    //     ]);

    //     $peserta->update($data);

    //     return redirect()->route('peserta.show', [$kegiatan, $peserta])
    //         ->with('success', 'Data peserta berhasil diperbarui.');
    // }

    public function destroy(Kegiatan $kegiatan, Peserta $peserta)
    {
        $peserta->delete();
        return redirect()->route('peserta.index', $kegiatan)
            ->with('success', 'Peserta berhasil dihapus.');
    }

    // ── Import Excel ───────────────────────────────────────────────
    public function importForm(Kegiatan $kegiatan)
    {
        return view('peserta.import', compact('kegiatan'));
    }

    // public function import(Request $request, Kegiatan $kegiatan)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls,csv|max:5120',
    //     ]);

    //     Excel::import(new PesertaImport($kegiatan), $request->file('file'));

    //     return redirect()->route('peserta.index', $kegiatan)
    //         ->with('success', 'Data peserta berhasil diimport.');
    // }

    public function import(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'file'   => 'required|mimes:xlsx,xls,csv|max:5120',
            'format' => 'nullable|in:v1,v2',
        ]);

        $importer = $request->input('format') === 'v2'
            ? new PesertaImportV2($kegiatan)
            : new PesertaImport($kegiatan);

        Excel::import($importer, $request->file('file'));

        return redirect()->route('peserta.index', $kegiatan)
            ->with('success', 'Data peserta berhasil diimport.');
    }

    // ── Simpan Hasil Tes ───────────────────────────────────────────
    public function saveHasil(Request $request, Kegiatan $kegiatan, Peserta $peserta)
    {
        $jenisTesId = $request->input('jenis_tes_id');
        $items      = $request->input('items', []);
        $ringkasan  = $request->input('ringkasan', []);

        // Hapus data lama
        HasilTes::where('peserta_id', $peserta->id)->where('jenis_tes_id', $jenisTesId)->delete();

        // Simpan data baru
        foreach ($items as $item) {
            if (empty($item['nama_item']) && empty($item['nilai_text']) && empty($item['nilai_angka'])) {
                continue;
            }
            HasilTes::create([
                'peserta_id'   => $peserta->id,
                'jenis_tes_id' => $jenisTesId,
                'nomor'        => $item['nomor'],
                'nama_item'    => $item['nama_item'] ?? '',
                'nilai_text'   => $item['nilai_text'] ?? null,
                'nilai_angka'  => !empty($item['nilai_angka']) ? $item['nilai_angka'] : null,
            ]);
        }

        // Simpan ringkasan
        if (!empty($ringkasan)) {
            RingkasanTes::updateOrCreate(
                ['peserta_id' => $peserta->id, 'jenis_tes_id' => $jenisTesId],
                [
                    'rekomendasi' => $ringkasan['rekomendasi'] ?? null,
                    'jumlah'      => !empty($ringkasan['jumlah']) ? $ringkasan['jumlah'] : null,
                    'jpm'         => !empty($ringkasan['jpm']) ? $ringkasan['jpm'] : null,
                    'kategori'    => $ringkasan['kategori'] ?? null,
                ]
            );
        }

        return redirect()->route('peserta.show', [$kegiatan, $peserta])
            ->with('success', 'Hasil tes berhasil disimpan.');
    }

    public function show(Kegiatan $kegiatan, Peserta $peserta)
{
    // FIX: hanya ambil jenis_tes milik kegiatan ini (bukan global/NULL)
    $jenisTesAll = JenisTes::where('kegiatan_id', $kegiatan->id)
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

        $hasilPerJenis[] = [
            'jenis_tes' => $jt,
            'items'     => $items,
            'ringkasan' => $ringkasan,
        ];
    }

    return view('peserta.show', compact('kegiatan', 'peserta', 'jenisTesAll', 'hasilPerJenis'));
}

// ═══════════════════════════════════════════════════════════════
// PERBAIKAN METHOD store() & update() — tambah level_jabatan
// ═══════════════════════════════════════════════════════════════

public function store(Request $request, Kegiatan $kegiatan)
{
    $validated = $request->validate([
        'nama'             => 'required|string|max:255',
        'nip'              => 'required|string|max:30',
        'jabatan'          => 'nullable|string|max:255',
        'level_jabatan'    => 'nullable|string|max:50',   // ← BARU
        'pangkat'          => 'nullable|string|max:100',
        'golongan'         => 'nullable|string|max:20',
        'perangkat_daerah' => 'nullable|string|max:255',
    ]);

    $nomorUrut = Peserta::where('kegiatan_id', $kegiatan->id)->max('nomor_urut') + 1;

    $peserta = Peserta::create([
        'kegiatan_id'   => $kegiatan->id,
        'nomor_urut'    => $nomorUrut,
        ...$validated,
    ]);

    return redirect()
        ->route('peserta.show', [$kegiatan, $peserta])
        ->with('success', 'Peserta berhasil ditambahkan.');
}

public function update(Request $request, Kegiatan $kegiatan, Peserta $peserta)
{
    $validated = $request->validate([
        'nama'             => 'required|string|max:255',
        'nip'              => 'required|string|max:30',
        'jabatan'          => 'nullable|string|max:255',
        'level_jabatan'    => 'nullable|string|max:50',   // ← BARU
        'pangkat'          => 'nullable|string|max:100',
        'golongan'         => 'nullable|string|max:20',
        'perangkat_daerah' => 'nullable|string|max:255',
    ]);

    $peserta->update($validated);

    return redirect()
        ->route('peserta.show', [$kegiatan, $peserta])
        ->with('success', 'Data peserta berhasil diperbarui.');
}
}