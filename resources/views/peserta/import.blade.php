@extends('layouts.app')
@section('title', 'Import Peserta dari Excel')
@section('page_title', 'Import Peserta')
@section('breadcrumb', $kegiatan->nama_kegiatan . ' / Import Excel')

@section('content')

<div class="mb-4">
    <a href="{{ route('peserta.index', $kegiatan) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Daftar Peserta
    </a>
</div>

{{-- Konteks kegiatan --}}
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:linear-gradient(135deg,var(--navy),var(--navy-light));border-radius:10px;margin-bottom:24px;">
    <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="bi bi-calendar2-event" style="color:var(--gold-light);font-size:16px;"></i>
    </div>
    <div>
        <div style="font-size:10.5px;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.8px;margin-bottom:2px;">Kegiatan Tujuan Import</div>
        <div style="font-size:14px;font-weight:600;color:white;font-family:'Playfair Display',serif;">{{ $kegiatan->nama_kegiatan }}</div>
    </div>
</div>

<div class="row g-4">

    {{-- ── Form Upload ── --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header" style="border-left:3px solid #2ecc71;padding-left:17px;">
                <i class="bi bi-file-earmark-excel" style="color:#2ecc71;"></i>
                <span class="card-header-title ms-2">Upload File Excel</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peserta.import.store', $kegiatan) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Pilih Format --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Format File <span class="text-danger">*</span></label>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">

                            <label id="fmt-label-v1"
                                   onclick="selectFormat('v1')"
                                   style="flex:1;min-width:180px;display:flex;align-items:flex-start;gap:10px;padding:14px;border:2px solid var(--gold);border-radius:10px;cursor:pointer;background:#fdf8ed;transition:all .2s;">
                                <input type="radio" name="format" value="v1" id="fmt-v1" checked style="margin-top:3px;">
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:var(--navy);">Format Lengkap</div>
                                    <div style="font-size:11.5px;color:var(--text-light);margin-top:2px;">Dengan aspek-aspek detail potensi &amp; kompetensi</div>
                                </div>
                            </label>

                            <label id="fmt-label-v2"
                                   onclick="selectFormat('v2')"
                                   style="flex:1;min-width:180px;display:flex;align-items:flex-start;gap:10px;padding:14px;border:2px solid var(--border);border-radius:10px;cursor:pointer;background:#faf8f4;transition:all .2s;">
                                <input type="radio" name="format" value="v2" id="fmt-v2" style="margin-top:3px;">
                                <div>
                                    <div style="font-size:13px;font-weight:600;color:var(--navy);">Format Ringkasan</div>
                                    <div style="font-size:11.5px;color:var(--text-light);margin-top:2px;">Hasil akhir saja (Rekomendasi, JPM, Kategori)</div>
                                </div>
                            </label>

                        </div>
                    </div>

                    {{-- Drop zone --}}
                    <div class="mb-4">
                        <label class="form-label">Pilih File Excel <span class="text-danger">*</span></label>

                        <label for="file-input"
                               style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;padding:32px 20px;border:2px dashed var(--border);border-radius:10px;cursor:pointer;background:#faf8f4;transition:all .2s;text-align:center;"
                               onmouseover="this.style.borderColor='var(--gold)';this.style.background='#fdf8ed';"
                               onmouseout="this.style.borderColor='var(--border)';this.style.background='#faf8f4';">
                            <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#edfaf3,#d4f5e4);display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-file-earmark-excel" style="font-size:24px;color:#1a7a4a;"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;color:var(--navy);" id="file-label">Klik untuk memilih file</div>
                                <div style="font-size:12px;color:var(--text-light);margin-top:3px;">atau seret &amp; lepas file di sini</div>
                            </div>
                            <input type="file" id="file-input" name="file"
                                   class="@error('file') is-invalid @enderror"
                                   accept=".xlsx,.xls,.csv"
                                   style="display:none;"
                                   required
                                   onchange="updateFileLabel(this)">
                        </label>

                        @error('file')
                        <div style="color:#c0392b;font-size:12.5px;margin-top:6px;">{{ $message }}</div>
                        @enderror
                        <div style="font-size:11.5px;color:var(--text-light);margin-top:8px;display:flex;align-items:center;gap:5px;">
                            <i class="bi bi-info-circle"></i>
                            Format yang diterima: <strong>.xlsx, .xls, .csv</strong> &nbsp;·&nbsp; Maksimal <strong>5 MB</strong>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-gold d-inline-flex align-items-center gap-2 px-4">
                        <i class="bi bi-upload"></i> Import Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Panduan Format ── --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header" style="border-left:3px solid var(--gold);padding-left:17px;">
                <i class="bi bi-journal-text" style="color:var(--gold);"></i>
                <span class="card-header-title ms-2">Panduan Format Excel</span>
            </div>
            <div class="card-body p-0">

                {{-- ── Panduan Format Lengkap (v1) ── --}}
                <div id="guide-v1">
                    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:12.5px;color:var(--text-mid);">
                        Baris pertama harus berisi <strong>header kolom</strong> sesuai daftar di bawah:
                    </div>

                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--text-light);text-transform:uppercase;">
                        Identitas Peserta
                    </div>
                    <div style="padding:0 18px 10px;">
                        @foreach([
                            ['nama',            'Nama peserta',     true],
                            ['nip',             'NIP peserta',      true],
                            ['jabatan',         'Jabatan',          false],
                            ['pangkat',         'Pangkat',          false],
                            ['golongan',        'Golongan',         false],
                            ['perangkat_daerah','Unit kerja / SKPD',false],
                        ] as [$col, $ket, $required])
                        <div style="display:flex;align-items:center;gap:10px;padding:5px 0;border-bottom:1px solid #f0eee8;">
                            <code style="font-size:11.5px;background:#f0f4ff;color:var(--navy);padding:1px 7px;border-radius:4px;flex-shrink:0;">{{ $col }}</code>
                            <span style="font-size:12.5px;color:var(--text-mid);flex:1;">{{ $ket }}</span>
                            @if($required)
                            <span style="font-size:10px;padding:1px 6px;background:#fdf0f2;color:#c0392b;border-radius:4px;font-weight:600;border:1px solid #f5c2c7;flex-shrink:0;">Wajib</span>
                            @else
                            <span style="font-size:10px;color:var(--text-light);flex-shrink:0;">Opsional</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--text-light);text-transform:uppercase;background:#f5f7fc;border-top:1px solid var(--border);">
                        Hasil Potensi
                    </div>
                    <div style="padding:0 18px 10px;background:#f5f7fc;">
                        @foreach([
                            ['potensi_1 s/d potensi_8', 'Skala (KS, K, C, B, BS)'],
                            ['potensi_rekomendasi',      'Rekomendasi hasil potensi'],
                        ] as [$col, $ket])
                        <div style="display:flex;align-items:center;gap:10px;padding:5px 0;border-bottom:1px solid #e8eaf0;">
                            <code style="font-size:11.5px;background:#e8edf8;color:var(--navy);padding:1px 7px;border-radius:4px;flex-shrink:0;white-space:nowrap;">{{ $col }}</code>
                            <span style="font-size:12.5px;color:var(--text-mid);">{{ $ket }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--text-light);text-transform:uppercase;border-top:1px solid var(--border);">
                        Hasil Kompetensi
                    </div>
                    <div style="padding:0 18px 12px;">
                        @foreach([
                            ['kompetensi_1 s/d kompetensi_9', 'Nilai angka (cth: 2.25)'],
                            ['kompetensi_jumlah',              'Total nilai kompetensi'],
                            ['kompetensi_jpm',                 'Job Person Match (%)'],
                            ['kompetensi_kategori',            'Kategori (Optimal, dll)'],
                        ] as [$col, $ket])
                        <div style="display:flex;align-items:center;gap:10px;padding:5px 0;border-bottom:1px solid #f0eee8;">
                            <code style="font-size:11.5px;background:#f0f4ff;color:var(--navy);padding:1px 7px;border-radius:4px;flex-shrink:0;white-space:nowrap;">{{ $col }}</code>
                            <span style="font-size:12.5px;color:var(--text-mid);">{{ $ket }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Panduan Format Ringkasan (v2) ── --}}
                <div id="guide-v2" style="display:none;">
                    <div style="padding:14px 18px;border-bottom:1px solid var(--border);font-size:12.5px;color:var(--text-mid);">
                        Gunakan file <strong>Hasil_Penilaian_Kompetensi.xlsx</strong> yang diekspor dari sistem. Struktur kolom yang dikenali:
                    </div>

                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--text-light);text-transform:uppercase;">
                        Identitas Peserta
                    </div>
                    <div style="padding:0 18px 10px;">
                        @foreach([
                            ['NO',         'Nomor urut',       true],
                            ['NAMA',       'Nama peserta',     true],
                            ['NIP',        'NIP peserta',      true],
                            ['JABATAN',    'Jabatan',          false],
                            ['UNIT KERJA', 'Unit kerja / SKPD',false],
                        ] as [$col, $ket, $required])
                        <div style="display:flex;align-items:center;gap:10px;padding:5px 0;border-bottom:1px solid #f0eee8;">
                            <code style="font-size:11.5px;background:#f0f4ff;color:var(--navy);padding:1px 7px;border-radius:4px;flex-shrink:0;">{{ $col }}</code>
                            <span style="font-size:12.5px;color:var(--text-mid);flex:1;">{{ $ket }}</span>
                            @if($required)
                            <span style="font-size:10px;padding:1px 6px;background:#fdf0f2;color:#c0392b;border-radius:4px;font-weight:600;border:1px solid #f5c2c7;flex-shrink:0;">Wajib</span>
                            @else
                            <span style="font-size:10px;color:var(--text-light);flex-shrink:0;">Opsional</span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--text-light);text-transform:uppercase;background:#f5f7fc;border-top:1px solid var(--border);">
                        Hasil Potensi
                    </div>
                    <div style="padding:0 18px 10px;background:#f5f7fc;">
                        @foreach([
                            ['REKOMENDASI POTENSI', 'Rekomendasi (Optimal / Kurang Optimal)'],
                            ['LEVEL JABATAN',       'Level jabatan peserta'],
                        ] as [$col, $ket])
                        <div style="display:flex;align-items:center;gap:10px;padding:5px 0;border-bottom:1px solid #e8eaf0;">
                            <code style="font-size:11.5px;background:#e8edf8;color:var(--navy);padding:1px 7px;border-radius:4px;flex-shrink:0;white-space:nowrap;">{{ $col }}</code>
                            <span style="font-size:12.5px;color:var(--text-mid);">{{ $ket }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="padding:10px 18px 4px;font-size:10px;font-weight:700;letter-spacing:1.5px;color:var(--text-light);text-transform:uppercase;border-top:1px solid var(--border);">
                        Hasil Kompetensi
                    </div>
                    <div style="padding:0 18px 12px;">
                        @foreach([
                            ['SKOR PEROLEHAN', 'Total skor kompetensi'],
                            ['JPM (%)',         'Job Person Match (%)'],
                            ['KATEGORI',        'Kategori (Optimal, dll)'],
                        ] as [$col, $ket])
                        <div style="display:flex;align-items:center;gap:10px;padding:5px 0;border-bottom:1px solid #f0eee8;">
                            <code style="font-size:11.5px;background:#f0f4ff;color:var(--navy);padding:1px 7px;border-radius:4px;flex-shrink:0;white-space:nowrap;">{{ $col }}</code>
                            <span style="font-size:12.5px;color:var(--text-mid);">{{ $ket }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div style="padding:10px 18px;background:#fffbea;border-top:1px solid #fde68a;font-size:12px;color:#92400e;display:flex;gap:8px;align-items:flex-start;">
                        <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;margin-top:1px;"></i>
                        <span>Header kolom berada di <strong>baris ke-4</strong> file Excel (sesuai format ekspor sistem).</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function updateFileLabel(input) {
    const label = document.getElementById('file-label');
    if (input.files && input.files[0]) {
        const name = input.files[0].name;
        const size = (input.files[0].size / 1024).toFixed(1);
        label.innerHTML = `<span style="color:var(--navy);">📄 ${name}</span> <span style="font-size:11px;color:var(--text-light);">(${size} KB)</span>`;
    } else {
        label.textContent = 'Klik untuk memilih file';
    }
}

function selectFormat(v) {
    const isV2 = v === 'v2';

    document.getElementById('fmt-v1').checked = !isV2;
    document.getElementById('fmt-v2').checked =  isV2;

    document.getElementById('fmt-label-v1').style.borderColor = isV2 ? 'var(--border)' : 'var(--gold)';
    document.getElementById('fmt-label-v1').style.background  = isV2 ? '#faf8f4'       : '#fdf8ed';
    document.getElementById('fmt-label-v2').style.borderColor = isV2 ? 'var(--gold)'   : 'var(--border)';
    document.getElementById('fmt-label-v2').style.background  = isV2 ? '#fdf8ed'       : '#faf8f4';

    document.getElementById('guide-v1').style.display = isV2 ? 'none'  : 'block';
    document.getElementById('guide-v2').style.display = isV2 ? 'block' : 'none';
}
</script>
@endpush

@endsection