@extends('layouts.app')
@section('title', 'Edit Kegiatan')
@section('page_title', 'Edit Kegiatan')
@section('breadcrumb', 'Kegiatan / Edit')

@section('content')

<div class="mb-4">
    <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div style="max-width:760px;">

    <form action="{{ route('kegiatan.update', $kegiatan) }}" method="POST" id="form-edit">
        @csrf @method('PUT')

    {{-- Section: Informasi Kegiatan --}}
    <div class="card mb-4">
        <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
            <i class="bi bi-pencil-square" style="color:var(--gold);"></i>
            <span class="card-header-title ms-2">Informasi Kegiatan</span>
        </div>
        <div class="card-body p-4">

            <div class="mb-4">
                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="nama_kegiatan"
                       class="form-control @error('nama_kegiatan') is-invalid @enderror"
                       value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}" required>
                @error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Metode <span class="text-danger">*</span></label>
                    <input type="text" name="jenis_kegiatan" class="form-control"
                           value="{{ old('jenis_kegiatan', $kegiatan->jenis_kegiatan) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                    <input type="text" name="penyelenggara" class="form-control"
                           value="{{ old('penyelenggara', $kegiatan->penyelenggara) }}" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Tempat <span class="text-danger">*</span></label>
                <input type="text" name="tempat" class="form-control"
                       value="{{ old('tempat', $kegiatan->tempat) }}" required>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control"
                           value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai?->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_selesai" class="form-control"
                           value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai?->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div>
                <label class="form-label">Nomor Urut Mulai Dari</label>
                <input type="number" name="nomor_urut_start" class="form-control"
                       value="{{ old('nomor_urut_start', $kegiatan->nomor_urut_start) }}"
                       min="1" style="max-width:160px;">
            </div>

        </div>
    </div>

    {{-- Section: Pejabat Penanda Tangan --}}
    <div class="card mb-4">
        <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
            <i class="bi bi-person-badge" style="color:var(--gold);"></i>
            <span class="card-header-title ms-2">Pejabat Penanda Tangan</span>
        </div>
        <div class="card-body p-4">

            <div class="mb-4">
                <label class="form-label">Nama Kepala</label>
                <input type="text" name="nama_kepala" class="form-control"
                       value="{{ old('nama_kepala', $kegiatan->nama_kepala) }}">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan_kepala" class="form-control"
                           value="{{ old('jabatan_kepala', $kegiatan->jabatan_kepala) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pangkat / Golongan</label>
                    <input type="text" name="pangkat_kepala" class="form-control"
                           value="{{ old('pangkat_kepala', $kegiatan->pangkat_kepala) }}">
                </div>
            </div>

            <div>
                <label class="form-label">NIP Kepala</label>
                <input type="text" name="nip_kepala" class="form-control"
                       value="{{ old('nip_kepala', $kegiatan->nip_kepala) }}"
                       style="max-width:280px;">
            </div>

        </div>
    </div>

    {{-- Section: Tanda Tangan Digital --}}
    <div class="card mb-4">
        <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
            <i class="bi bi-pen" style="color:var(--gold);"></i>
            <span class="card-header-title ms-2">Tanda Tangan Digital</span>
        </div>
        <div class="card-body p-4">

            <input type="hidden" name="ttd_base64" id="ttd_base64" value="{{ $kegiatan->ttd_base64 ?? '' }}">

            {{-- TTD tersimpan --}}
            <div id="ttd-lama-wrap" class="{{ $kegiatan->ttd_base64 ? '' : 'd-none' }} mb-4">
                <label class="form-label">TTD Tersimpan Sekarang</label>
                <div style="display:inline-flex;flex-direction:column;align-items:flex-start;gap:10px;">
                    <div style="border:1px solid var(--border);border-radius:8px;padding:10px 16px;background:#faf8f4;">
                        <img id="ttd-lama-img"
                             src="{{ $kegiatan->ttd_base64 ?? '' }}"
                             style="height:64px;display:block;">
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:#edfaf3;color:#1a6640;border-radius:5px;font-size:11.5px;font-weight:500;border:1px solid #c3edd8;">
                            <i class="bi bi-check-circle-fill" style="font-size:10px;"></i> TTD sudah ada
                        </span>
                        <button type="button"
                                onclick="hapusTtd()"
                                style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:transparent;border:1px solid rgba(220,53,69,.35);color:rgba(220,53,69,.8);border-radius:5px;font-size:11.5px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s;"
                                onmouseover="this.style.background='rgba(220,53,69,.07)'"
                                onmouseout="this.style.background='transparent'">
                            <i class="bi bi-trash" style="font-size:10px;"></i> Hapus TTD
                        </button>
                    </div>
                </div>
            </div>

            {{-- Canvas TTD baru --}}
            <div>
                <label class="form-label">
                    {{ $kegiatan->ttd_base64 ? 'Gambar Ulang untuk Mengganti (Opsional)' : 'Gambar TTD di Sini' }}
                </label>
                <div style="border:1.5px dashed var(--border);border-radius:8px;overflow:hidden;cursor:crosshair;background:#fefefe;transition:border-color .2s;"
                     id="canvas-wrap"
                     onmouseover="this.style.borderColor='var(--gold)'"
                     onmouseout="this.style.borderColor='var(--border)'">
                    <canvas id="signature-pad" style="width:100%;height:190px;display:block;background:transparent;"></canvas>
                </div>
                <div class="d-flex align-items-center gap-3 mt-3">
                    <button type="button" onclick="clearCanvas()"
                            class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                        <i class="bi bi-eraser"></i> Bersihkan
                    </button>
                    <small id="ttd-info" style="font-size:12px;color:var(--text-light);">
                        @if($kegiatan->ttd_base64)
                            TTD lama otomatis dipakai jika canvas tidak digambar.
                        @else
                            Gambar tanda tangan di area atas lalu klik Update.
                        @endif
                    </small>
                </div>
            </div>

        </div>
    </div>

    {{-- Submit --}}
    <div class="d-flex gap-2 align-items-center pb-4">
        <button type="submit" class="btn btn-gold px-4 d-flex align-items-center gap-2">
            <i class="bi bi-save"></i> Update Kegiatan
        </button>
        <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>

    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const canvas      = document.getElementById('signature-pad');
    const hiddenInput = document.getElementById('ttd_base64');
    const infoText    = document.getElementById('ttd-info');

    function initCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const w = canvas.offsetWidth;
        canvas.width  = w * ratio;
        canvas.height = 190 * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
    }
    initCanvas();

    const pad = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255,255,255,0)',
        penColor: '#0c1f3d',
        minWidth: 1,
        maxWidth: 2.5,
    });

    pad.addEventListener('endStroke', function () {
        hiddenInput.value = pad.toDataURL('image/png');
        infoText.innerHTML = '<span style="color:#1a6640;font-weight:500;">✏️ TTD baru siap disimpan.</span>';
    });

    window.addEventListener('resize', function () {
        const data = pad.toData();
        initCanvas();
        if (data && data.length > 0) pad.fromData(data);
    });

    window.clearCanvas = function () {
        pad.clear();
        const ttdLama = @json($kegiatan->ttd_base64 ?? '');
        hiddenInput.value = ttdLama;
        infoText.innerHTML = ttdLama
            ? 'Canvas dibersihkan. <span style="color:var(--text-light);">TTD lama tetap dipakai.</span>'
            : 'Canvas dibersihkan.';
    };

    window.hapusTtd = function () {
        if (!confirm('Hapus TTD dari kegiatan ini?\nTTD tidak akan muncul di sertifikat setelah disimpan.')) return;
        hiddenInput.value = '';
        pad.clear();
        document.getElementById('ttd-lama-wrap').classList.add('d-none');
        infoText.innerHTML = '<span style="color:#c0392b;font-weight:500;">⚠️ TTD akan dihapus saat klik Update.</span>';
    };
});
</script>
@endpush

@endsection