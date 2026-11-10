@extends('layouts.app')
@section('title', 'Tambah Kegiatan')
@section('page_title', 'Tambah Kegiatan')
@section('breadcrumb', 'Kegiatan / Tambah Baru')

@section('content')

<div class="mb-4">
    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div style="max-width:760px;">

    {{-- Section: Informasi Kegiatan --}}
    <div class="card mb-4">
        <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
            <i class="bi bi-calendar-event" style="color:var(--gold);"></i>
            <span class="card-header-title ms-2">Informasi Kegiatan</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('kegiatan.store') }}" method="POST" id="form-create">
                @csrf

                <div class="mb-4">
                    <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kegiatan"
                           class="form-control @error('nama_kegiatan') is-invalid @enderror"
                           value="{{ old('nama_kegiatan') }}"
                           placeholder="cth: Penilaian Potensi dan Kompetensi Pegawai 2026"
                           required>
                    @error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Metode <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_kegiatan" class="form-control"
                               value="{{ old('jenis_kegiatan') }}"
                               placeholder="cth: metode" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                        <input type="text" name="penyelenggara" class="form-control"
                               value="{{ old('penyelenggara') }}"
                               placeholder="cth: BKPSDM Kota Yogyakarta" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Tempat <span class="text-danger">*</span></label>
                    <input type="text" name="tempat" class="form-control"
                           value="{{ old('tempat') }}"
                           placeholder="cth: Balai Kota Yogyakarta" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai" class="form-control"
                               value="{{ old('tanggal_mulai') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_selesai" class="form-control"
                               value="{{ old('tanggal_selesai') }}" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Nomor Urut Sertifikat Mulai Dari</label>
                    <input type="number" name="nomor_urut_start" class="form-control"
                           value="{{ old('nomor_urut_start', 1) }}" min="1" style="max-width:160px;">
                    <div class="form-text mt-1" style="font-size:11.5px;color:var(--text-light);">
                        <i class="bi bi-info-circle me-1"></i>
                        Format nomor: <code>800.1.14.2/<strong>[urut]</strong>/[bulan]/[tahun]</code>
                    </div>
                </div>
            {{-- form dilanjut di section berikut, tombol submit di bawah --}}
        </div>
    </div>

    {{-- Section: Pejabat Penanda Tangan --}}
    <div class="card mb-4">
        <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
            <i class="bi bi-person-badge" style="color:var(--gold);"></i>
            <span class="card-header-title ms-2">Data Pejabat Penanda Tangan</span>
        </div>
        <div class="card-body p-4">

                <div class="mb-4">
                    <label class="form-label">Nama Kepala</label>
                    <input type="text" name="nama_kepala" class="form-control"
                           value="{{ old('nama_kepala') }}"
                           placeholder="cth: Drs. Ahmad Nugroho, M.Si.">
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan_kepala" class="form-control"
                               value="{{ old('jabatan_kepala') }}"
                               placeholder="cth: Kepala BKPSDM">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pangkat / Golongan</label>
                        <input type="text" name="pangkat_kepala" class="form-control"
                               value="{{ old('pangkat_kepala') }}"
                               placeholder="cth: Pembina Utama Muda / IV-c">
                    </div>
                </div>

                <div>
                    <label class="form-label">NIP Kepala</label>
                    <input type="text" name="nip_kepala" class="form-control"
                           value="{{ old('nip_kepala') }}"
                           placeholder="cth: 197803122003121005"
                           style="max-width:280px;">
                </div>

        </div>
    </div>

    {{-- Submit --}}
    <div class="d-flex gap-2 align-items-center pb-4">
        <button type="submit" form="form-create" class="btn btn-gold px-4 d-flex align-items-center gap-2">
            <i class="bi bi-save"></i> Simpan Kegiatan
        </button>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>

    </form>
</div>

@endsection