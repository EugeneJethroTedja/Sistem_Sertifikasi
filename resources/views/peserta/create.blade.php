@extends('layouts.app')
@section('title', 'Tambah Peserta')
@section('page_title', 'Tambah Peserta')
@section('breadcrumb', $kegiatan->nama_kegiatan . ' / Tambah Peserta')

@section('content')

<div class="mb-4">
    <a href="{{ route('peserta.index', $kegiatan) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Daftar Peserta
    </a>
</div>

<div style="max-width:620px;">

    <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f0f4ff;border-radius:8px;border:1px solid #dde4f5;margin-bottom:20px;">
        <i class="bi bi-calendar2-event" style="color:var(--navy);font-size:14px;"></i>
        <div>
            <div style="font-size:11px;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;">Kegiatan</div>
            <div style="font-size:13px;font-weight:600;color:var(--navy);">{{ $kegiatan->nama_kegiatan }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
            <i class="bi bi-person-plus" style="color:var(--gold);"></i>
            <span class="card-header-title ms-2">Data Peserta Baru</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('peserta.store', $kegiatan) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="cth: Budi Santoso, S.T." required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip"
                           class="form-control font-monospace @error('nip') is-invalid @enderror"
                           value="{{ old('nip') }}"
                           placeholder="cth: 198501152010011003" required>
                    @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Jabatan + Level dalam satu baris --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan" class="form-control"
                               value="{{ old('jabatan') }}"
                               placeholder="cth: Analis Kepegawaian Ahli Muda">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Level Jabatan</label>
                        <input type="text" name="level_jabatan" class="form-control"
                               value="{{ old('level_jabatan') }}"
                               placeholder="cth: 1, 2, 3">
                        <div class="form-text">Dari kolom Level Jabatan Excel</div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label">Pangkat</label>
                        <input type="text" name="pangkat" class="form-control"
                               value="{{ old('pangkat') }}"
                               placeholder="cth: Penata Tingkat I">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Golongan</label>
                        <input type="text" name="golongan" class="form-control"
                               value="{{ old('golongan') }}"
                               placeholder="cth: III/d">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="form-label">Perangkat Daerah / Unit Kerja</label>
                    <input type="text" name="perangkat_daerah" class="form-control"
                           value="{{ old('perangkat_daerah') }}"
                           placeholder="cth: Dinas Kominfo Kota Yogyakarta">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold d-flex align-items-center gap-2 px-4">
                        <i class="bi bi-save"></i> Simpan Peserta
                    </button>
                    <a href="{{ route('peserta.index', $kegiatan) }}" class="btn btn-outline-secondary px-4">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection