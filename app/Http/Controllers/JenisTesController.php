<?php

namespace App\Http\Controllers;

use App\Models\JenisTes;
use Illuminate\Http\Request;

class JenisTesController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'kegiatan_id'     => 'required|exists:kegiatan,id',
            'nama'            => 'required|string|max:255',
            'header_col1'     => 'nullable|string|max:100',
            'header_col2'     => 'nullable|string|max:100',
            'has_rekomendasi' => 'nullable',
            'has_jpm'         => 'nullable',
            'kode'            => 'nullable|string|max:50',
        ]);

        $maxUrutan = JenisTes::where('kegiatan_id', $data['kegiatan_id'])
                             ->max('urutan') ?? 10;

        JenisTes::create([
            'kegiatan_id'     => $data['kegiatan_id'],
            'kode'            => $data['kode'] ?? 'lainnya',  // ← fix: pakai kode dari request
            'nama'            => $data['nama'],
            'urutan'          => $maxUrutan + 1,
            'has_rekomendasi' => !empty($data['has_rekomendasi']),
            'has_jpm'         => !empty($data['has_jpm']),
            'header_col1'     => $data['header_col1'] ?? 'Aspek',
            'header_col2'     => $data['header_col2'] ?? 'Nilai',
        ]);

        return back()->with('success', 'Jenis tes berhasil ditambahkan untuk kegiatan ini.');
    }

    public function update(Request $request, JenisTes $jenisTes)
    {
        if (in_array($jenisTes->kode, ['potensi', 'kompetensi'])) {
            return back()->with('error', 'Jenis tes default tidak dapat diubah.');
        }

        $data = $request->validate([
            'nama'            => 'required|string|max:255',
            'header_col1'     => 'nullable|string|max:100',
            'header_col2'     => 'nullable|string|max:100',
            'has_rekomendasi' => 'nullable',
            'has_jpm'         => 'nullable',
            'kode'            => 'nullable|string|max:50',  // ← fix: tambah validasi kode
        ]);

        $jenisTes->update([
            'nama'            => $data['nama'],
            'kode'            => $data['kode'] ?? $jenisTes->kode,  // ← fix: update kode
            'header_col1'     => $data['header_col1'] ?? 'Aspek',
            'header_col2'     => $data['header_col2'] ?? 'Nilai',
            'has_rekomendasi' => !empty($data['has_rekomendasi']),
            'has_jpm'         => !empty($data['has_jpm']),
        ]);

        return back()->with('success', 'Jenis tes berhasil diperbarui.');
    }

    public function destroy(JenisTes $jenisTes)
    {
        if (in_array($jenisTes->kode, ['potensi', 'kompetensi'])) {
            return back()->with('error', 'Jenis tes default tidak dapat dihapus.');
        }

        $jenisTes->delete();

        return back()->with('success', 'Jenis tes berhasil dihapus.');
    }
}