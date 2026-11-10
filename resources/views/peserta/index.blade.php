@extends('layouts.app')
@section('title', 'Peserta – ' . $kegiatan->nama_kegiatan)
@section('page_title', 'Daftar Peserta')
@section('breadcrumb', 'Kegiatan / ' . $kegiatan->nama_kegiatan)

@section('content')

{{-- Back --}}
<div class="mb-4">
    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Semua Kegiatan
    </a>
</div>

{{-- Kegiatan Info Banner --}}
<div class="card card-gold-accent mb-4" style="background:linear-gradient(135deg,var(--navy) 0%,var(--navy-light) 100%);border-color:transparent;">
    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div style="font-family:'Cinzel',serif;font-size:10px;letter-spacing:2px;color:var(--gold-light);text-transform:uppercase;margin-bottom:6px;">
                <i class="bi bi-calendar2-event me-1"></i> Kegiatan Aktif
            </div>
            <h2 style="font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:#fff;margin-bottom:6px;line-height:1.2;">
                {{ $kegiatan->nama_kegiatan }}
            </h2>
            <div style="font-size:12.5px;color:rgba(255,255,255,.55);display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                <span><i class="bi bi-geo-alt me-1" style="color:var(--gold-light);"></i>{{ $kegiatan->tempat }}</span>
                <span><i class="bi bi-calendar3 me-1" style="color:var(--gold-light);"></i>{{ $kegiatan->tanggal_range }}</span>
                <span><i class="bi bi-people me-1" style="color:var(--gold-light);"></i>{{ $peserta->total() }} peserta</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('peserta.import', $kegiatan) }}"
               class="btn btn-sm d-flex align-items-center gap-2"
               style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:#fff;border-radius:7px;padding:7px 14px;font-size:13px;">
                <i class="bi bi-file-earmark-excel"></i> Import Excel
            </a>
            <a href="{{ route('peserta.create', $kegiatan) }}" class="btn btn-gold btn-sm d-flex align-items-center gap-2">
                <i class="bi bi-person-plus"></i> Tambah Peserta
            </a>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header" style="justify-content:space-between;">
        <div class="d-flex align-items-center gap-2">
            <div style="width:3px;height:18px;background:var(--gold);border-radius:2px;"></div>
            <span class="card-header-title">Data Peserta</span>
        </div>
        <span style="font-size:12px;color:var(--text-light);">{{ $peserta->total() }} peserta terdaftar</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4" width="80">No. Urut</th>
                    <th>Nama Peserta</th>
                    <th>NIP</th>
                    <th>Perangkat Daerah</th>
                    <th>No. Sertifikat</th>
                    <th class="text-center" width="100">Hasil Tes</th>
                    <th class="text-center" width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peserta as $p)
                <tr>
                    <td class="ps-4">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:#f0f4ff;color:var(--navy);font-size:12px;font-weight:700;">
                            {{ $p->nomor_urut }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:600;color:var(--navy);font-size:13.5px;">{{ $p->nama }}</div>
                        <div style="font-size:11.5px;color:var(--text-light);margin-top:1px;">{{ $p->jabatan ?: '–' }}</div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:12.5px;color:var(--text-mid);background:#f5f7fc;padding:2px 8px;border-radius:4px;border:1px solid var(--border);">
                            {{ $p->nip }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:var(--text-mid);">{{ $p->perangkat_daerah ?: '–' }}</td>
                    <td>
                        <span style="font-family:monospace;font-size:11px;background:var(--navy);color:var(--gold-light);padding:2px 8px;border-radius:4px;">
                            {{ $p->nomor_sertifikat }}
                        </span>
                    </td>
                    <td class="text-center">
                        @php $jmlHasil = $p->hasilTes->count() @endphp
                        @if($jmlHasil > 0)
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 9px;background:#edfaf3;color:#1a6640;border-radius:5px;font-size:11.5px;font-weight:500;border:1px solid #c3edd8;">
                                <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> {{ $jmlHasil }} item
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 9px;background:#fdf8ed;color:#7a5a00;border-radius:5px;font-size:11.5px;font-weight:500;border:1px solid #f0d88a;">
                                <i class="bi bi-clock" style="font-size:10px;"></i> Belum ada
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <a href="{{ route('peserta.show', [$kegiatan, $p]) }}"
                               class="btn btn-sm btn-outline-secondary" style="padding:4px 8px;font-size:12px;" title="Detail & Hasil Tes">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('peserta.sertifikat.preview', [$kegiatan, $p]) }}"
                               class="btn btn-sm btn-outline-secondary" style="padding:4px 8px;font-size:12px;" title="Preview Sertifikat" target="_blank">
                                <i class="bi bi-file-earmark-text"></i>
                            </a>
                            <a href="{{ route('peserta.edit', [$kegiatan, $p]) }}"
                               class="btn btn-sm btn-outline-secondary" style="padding:4px 8px;font-size:12px;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('peserta.destroy', [$kegiatan, $p]) }}" method="POST"
                                  onsubmit="return confirm('Hapus peserta {{ addslashes($p->nama) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" style="padding:4px 8px;font-size:12px;" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div style="color:var(--text-light);">
                            <i class="bi bi-people" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                            <div style="font-size:14px;font-weight:500;color:var(--text-mid);">Belum ada peserta</div>
                            <div style="font-size:12.5px;margin-top:6px;display:flex;gap:10px;justify-content:center;">
                                <a href="{{ route('peserta.create', $kegiatan) }}" style="color:var(--gold);text-decoration:none;font-weight:500;">+ Tambah manual →</a>
                                <span style="color:var(--border);">|</span>
                                <a href="{{ route('peserta.import', $kegiatan) }}" style="color:var(--gold);text-decoration:none;font-weight:500;">Import Excel →</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peserta->hasPages())
    <div class="card-footer bg-white border-top" style="padding:12px 20px;border-color:var(--border)!important;">
        {{ $peserta->links() }}
    </div>
    @endif
</div>

@endsection