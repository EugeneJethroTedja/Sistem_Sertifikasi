@extends('layouts.app')
@section('title', 'Daftar Kegiatan')
@section('page_title', 'Daftar Kegiatan')
@section('breadcrumb', 'Kegiatan / Semua Kegiatan')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-header-title">Daftar Kegiatan</h1>
        <p class="page-header-sub">Kelola seluruh kegiatan & penerbitan sertifikat pegawai</p>
    </div>
    <a href="{{ route('kegiatan.create') }}" class="btn btn-gold d-flex align-items-center gap-2">
        <i class="bi bi-plus-circle"></i> Tambah Kegiatan
    </a>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card card-gold-accent p-3 d-flex flex-row align-items-center gap-3">
            <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#0c1f3d,#1e3d6e);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-calendar2-event text-white" style="font-size:16px;"></i>
            </div>
            <div>
                <div style="font-size:22px;font-weight:700;font-family:'Playfair Display',serif;color:var(--navy);line-height:1;">{{ $kegiatan->total() }}</div>
                <div style="font-size:11.5px;color:var(--text-light);margin-top:2px;">Total Kegiatan</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#b8962e,#d4af5a);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-people text-white" style="font-size:16px;"></i>
            </div>
            <div>
                <div style="font-size:22px;font-weight:700;font-family:'Playfair Display',serif;color:var(--navy);line-height:1;">{{ $kegiatan->sum('peserta_count') }}</div>
                <div style="font-size:11.5px;color:var(--text-light);margin-top:2px;">Total Peserta</div>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="card">
    <div class="card-header" style="justify-content:space-between;">
        <div class="d-flex align-items-center gap-2">
            <div style="width:3px;height:18px;background:var(--gold);border-radius:2px;"></div>
            <span class="card-header-title">Semua Kegiatan</span>
        </div>
        <span style="font-size:12px;color:var(--text-light);">{{ $kegiatan->total() }} kegiatan ditemukan</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4" width="40">#</th>
                    <th>Nama Kegiatan</th>
                    <th>Jenis</th>
                    <th>Tempat</th>
                    <th>Tanggal Pelaksanaan</th>
                    <th class="text-center" width="90">Peserta</th>
                    <th class="text-center" width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kegiatan as $k)
                <tr>
                    <td class="ps-4" style="color:var(--text-light);font-size:12px;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--navy);font-size:13.5px;">{{ $k->nama_kegiatan }}</div>
                        <div style="font-size:11.5px;color:var(--text-light);margin-top:1px;">
                            <i class="bi bi-building me-1"></i>{{ $k->penyelenggara }}
                        </div>
                    </td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:#f0f4ff;color:var(--navy);border-radius:5px;font-size:11.5px;font-weight:500;border:1px solid #dde4f5;">
                            {{ $k->jenis_kegiatan }}
                        </span>
                    </td>
                    <td style="font-size:13px;color:var(--text-mid);">
                        <i class="bi bi-geo-alt me-1" style="color:var(--gold);font-size:11px;"></i>{{ $k->tempat }}
                    </td>
                    <td>
                        <div style="font-size:13px;color:var(--text-dark);">
                            {{ \Carbon\Carbon::parse($k->tanggal_mulai)->isoFormat('D MMM Y') }}
                        </div>
                        <div style="font-size:11px;color:var(--text-light);">
                            s.d. {{ \Carbon\Carbon::parse($k->tanggal_selesai)->isoFormat('D MMM Y') }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-light));color:white;font-size:12px;font-weight:600;">
                            {{ $k->peserta_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <a href="{{ route('peserta.index', $k) }}"
                               class="btn btn-sm btn-outline-secondary"
                               style="padding:4px 9px;font-size:12px;"
                               title="Lihat Peserta">
                                <i class="bi bi-people"></i>
                            </a>
                            <a href="{{ route('kegiatan.edit', $k) }}"
                               class="btn btn-sm btn-outline-secondary"
                               style="padding:4px 9px;font-size:12px;"
                               title="Edit & ttd Kegiatan">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('kegiatan.destroy', $k) }}" method="POST"
                                  onsubmit="return confirm('Hapus kegiatan ini beserta seluruh datanya?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        style="padding:4px 9px;font-size:12px;"
                                        title="Hapus">
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
                            <i class="bi bi-calendar-x" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                            <div style="font-size:14px;font-weight:500;color:var(--text-mid);">Belum ada kegiatan</div>
                            <div style="font-size:12.5px;margin-top:4px;">
                                <a href="{{ route('kegiatan.create') }}" style="color:var(--gold);text-decoration:none;font-weight:500;">Tambah kegiatan pertama →</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($kegiatan->hasPages())
    <div class="card-footer bg-white border-top" style="padding:12px 20px;border-color:var(--border)!important;">
        {{ $kegiatan->links() }}
    </div>
    @endif
</div>

@endsection