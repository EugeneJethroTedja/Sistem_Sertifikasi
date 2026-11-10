<?php

namespace App\Http\Controllers;

use App\Models\{Kegiatan, HasilTes, RingkasanTes, JenisTes};
use Illuminate\Http\Request;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::withCount('peserta')->latest()->paginate(15);
        return view('kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('kegiatan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'jenis_kegiatan'   => 'required|string|max:100',
            'penyelenggara'    => 'required|string|max:255',
            'tempat'           => 'required|string|max:255',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'nomor_urut_start' => 'required|integer|min:1',
            'nama_kepala'      => 'nullable|string|max:255',
            'jabatan_kepala'   => 'nullable|string|max:255',
            'pangkat_kepala'   => 'nullable|string|max:100',
            'nip_kepala'       => 'nullable|string|max:30',
        ]);

        $tanggal = Carbon::parse($data['tanggal_mulai']);
        $data['bulan_kegiatan'] = $tanggal->month;
        $data['tahun_kegiatan'] = $tanggal->year;

        Kegiatan::create($data);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $peserta = $kegiatan->peserta()->paginate(20);
        return view('kegiatan.show', compact('kegiatan', 'peserta'));
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama_kegiatan'    => 'required|string|max:255',
            'jenis_kegiatan'   => 'required|string|max:100',
            'penyelenggara'    => 'required|string|max:255',
            'tempat'           => 'required|string|max:255',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'nomor_urut_start' => 'required|integer|min:1',
            'nama_kepala'      => 'nullable|string|max:255',
            'jabatan_kepala'   => 'nullable|string|max:255',
            'pangkat_kepala'   => 'nullable|string|max:100',
            'nip_kepala'       => 'nullable|string|max:30',
            'ttd_base64'       => 'nullable|string',
        ]);

        $tanggal = Carbon::parse($validated['tanggal_mulai']);
        $validated['bulan_kegiatan'] = $tanggal->month;
        $validated['tahun_kegiatan'] = $tanggal->year;

        // ── LOGIKA TTD ──────────────────────────────────────────
        $ttdValue = $request->input('ttd_base64');

        if (!empty($ttdValue) && str_starts_with($ttdValue, 'data:image/')) {
            $validated['ttd_base64'] = $ttdValue;
        } elseif ($ttdValue === '') {
            $validated['ttd_base64'] = null;
        } else {
            unset($validated['ttd_base64']);
        }
        // ────────────────────────────────────────────────────────

        $kegiatan->update($validated);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        // ── FIX: Hapus child records secara manual sesuai urutan FK ──
        //
        // Urutan constraint: hasil_tes → jenis_tes (FK jenis_tes_id)
        //                    hasil_tes → peserta   (FK peserta_id)
        //                    ringkasan_tes → jenis_tes
        //                    ringkasan_tes → peserta
        //                    peserta → kegiatan
        //                    jenis_tes → kegiatan
        //
        // Jadi urutan hapus yang benar:
        // 1. hasil_tes (referencing jenis_tes & peserta)
        // 2. ringkasan_tes (referencing jenis_tes & peserta)
        // 3. jenis_tes (referencing kegiatan)
        // 4. peserta (referencing kegiatan)
        // 5. kegiatan

        // Ambil semua peserta_id dan jenis_tes_id milik kegiatan ini
        $pesertaIds  = $kegiatan->peserta()->pluck('id');
        $jenisTesIds = JenisTes::where('kegiatan_id', $kegiatan->id)->pluck('id');

        // 1. Hapus hasil_tes milik peserta kegiatan ini
        if ($pesertaIds->isNotEmpty()) {
            HasilTes::whereIn('peserta_id', $pesertaIds)->delete();
            RingkasanTes::whereIn('peserta_id', $pesertaIds)->delete();
        }

        // 2. Hapus jenis_tes milik kegiatan ini
        if ($jenisTesIds->isNotEmpty()) {
            JenisTes::whereIn('id', $jenisTesIds)->delete();
        }

        // 3. Hapus peserta
        $kegiatan->peserta()->delete();

        // 4. Hapus kegiatan
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}