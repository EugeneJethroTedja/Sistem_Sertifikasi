@extends('layouts.app')
@section('title', 'Edit Peserta – ' . $peserta->nama)
@section('page_title', 'Edit Peserta')
@section('breadcrumb', $kegiatan->nama_kegiatan . ' / ' . $peserta->nama)

@section('content')

<div class="mb-4">
    <a href="{{ route('peserta.show', [$kegiatan, $peserta]) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Detail Peserta
    </a>
</div>

<div style="max-width:620px;">

    <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:10px;margin-bottom:20px;">
        <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi bi-person-fill" style="color:white;font-size:17px;"></i>
        </div>
        <div>
            <div style="font-size:11px;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.5px;margin-bottom:2px;">Mengedit data</div>
            <div style="font-size:14px;font-weight:600;color:white;font-family:'Playfair Display',serif;">{{ $peserta->nama }}</div>
            <div style="font-size:11.5px;color:var(--gold-light);margin-top:1px;font-family:monospace;">{{ $peserta->nomor_sertifikat }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
            <i class="bi bi-pencil-square" style="color:var(--gold);"></i>
            <span class="card-header-title ms-2">Edit Data Peserta</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('peserta.update', [$kegiatan, $peserta]) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $peserta->nama) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" class="form-control font-monospace"
                           value="{{ old('nip', $peserta->nip) }}" required>
                </div>

                {{-- Jabatan + Level dalam satu baris --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control"
                               value="{{ old('jabatan', $peserta->jabatan) }}"
                               placeholder="cth: Analis Kepegawaian Ahli Muda">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Level Jabatan</label>
                        <input type="text" name="level_jabatan" class="form-control"
                               value="{{ old('level_jabatan', $peserta->level_jabatan) }}"
                               placeholder="cth: 1, 2, 3">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label">Pangkat</label>
                        <input type="text" name="pangkat" class="form-control"
                               value="{{ old('pangkat', $peserta->pangkat) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Golongan</label>
                        <input type="text" name="golongan" class="form-control"
                               value="{{ old('golongan', $peserta->golongan) }}" placeholder="III/d">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Perangkat Daerah / Unit Kerja</label>
                    <input type="text" name="perangkat_daerah" class="form-control"
                           value="{{ old('perangkat_daerah', $peserta->perangkat_daerah) }}">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold d-flex align-items-center gap-2 px-4">
                        <i class="bi bi-save"></i> Update Data
                    </button>
                    <a href="{{ route('peserta.show', [$kegiatan, $peserta]) }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection