@extends('layouts.app')
@section('title', 'Detail – ' . $peserta->nama)
@section('page_title', 'Detail Peserta')
@section('breadcrumb', $kegiatan->nama_kegiatan . ' / ' . $peserta->nama)

@section('content')

<style>
.side-panel-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 1040;
}
.side-panel-overlay.active { display: block; }
.side-panel {
    position: fixed;
    top: 0; right: -480px;
    width: 460px;
    height: 100%;
    background: #fff;
    z-index: 1050;
    box-shadow: -4px 0 24px rgba(0,0,0,.15);
    transition: right .28s ease;
    display: flex;
    flex-direction: column;
}
.side-panel.open { right: 0; }
.side-panel-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--navy);
    color: #fff;
}
.side-panel-body { padding: 24px; overflow-y: auto; flex: 1; }
.side-panel-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--border);
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}
.btn-close-panel {
    background: none;
    border: none;
    color: rgba(255,255,255,.7);
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
    padding: 0;
}
.btn-close-panel:hover { color: #fff; }
</style>

<div class="mb-4">
    <a href="{{ route('peserta.index', $kegiatan) }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i> Daftar Peserta
    </a>
</div>

<div class="row g-4">

    {{-- ── Kolom Kiri: Identitas ── --}}
    <div class="col-md-4">

        <div class="card card-gold-accent mb-3">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--navy),var(--navy-light));display:flex;align-items:center;justify-content:center;margin:0 auto 12px;box-shadow:0 4px 16px rgba(12,31,61,.2);">
                        <i class="bi bi-person-fill" style="font-size:28px;color:white;"></i>
                    </div>
                    <div style="font-family:'Playfair Display',serif;font-size:16px;font-weight:700;color:var(--navy);line-height:1.3;">
                        {{ $peserta->nama }}
                    </div>
                    <div style="font-size:11.5px;color:var(--text-light);margin-top:4px;">
                        {{ $peserta->jabatan ?: '–' }}
                        @if($peserta->level_jabatan)
                            <span style="display:inline-block;margin-left:4px;padding:1px 7px;background:#f0f4ff;color:var(--navy);border-radius:4px;font-size:10.5px;font-weight:600;border:1px solid #dde4f5;">
                                Level {{ $peserta->level_jabatan }}
                            </span>
                        @endif
                    </div>
                    <div style="margin-top:8px;">
                        <span style="font-family:monospace;font-size:11px;background:var(--navy);color:var(--gold-light);padding:3px 10px;border-radius:4px;">
                            {{ $peserta->nomor_sertifikat }}
                        </span>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:0;">
                    @foreach([
                        ['bi-person-vcard', 'NIP',             $peserta->nip ?: '–',                                                              true],
                        ['bi-briefcase',    'Jabatan',         $peserta->jabatan ?: '–',                                                           false],
                        ['bi-bar-chart',    'Level Jabatan',   $peserta->level_jabatan ? 'Level '.$peserta->level_jabatan : '–',                   false],
                        ['bi-layers',       'Pangkat / Gol.',  ($peserta->pangkat ?: '–') . ($peserta->golongan ? ' / '.$peserta->golongan : ''),  false],
                        ['bi-building',     'Perangkat Daerah',$peserta->perangkat_daerah ?: '–',                                                  false],
                    ] as [$icon, $label, $val, $mono])
                    <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid var(--border);">
                        <div style="width:28px;height:28px;border-radius:7px;background:#f0f4ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
                            <i class="bi {{ $icon }}" style="font-size:12px;color:var(--navy);"></i>
                        </div>
                        <div>
                            <div style="font-size:10.5px;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:1px;">{{ $label }}</div>
                            <div style="font-size:13px;color:var(--text-dark);font-weight:500;{{ $mono ? 'font-family:monospace;' : '' }}">{{ $val }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-3 d-flex flex-column gap-2">
                <button type="button" onclick="bukaPanel('panelEditPeserta')"
                   class="btn btn-sm d-flex align-items-center gap-2"
                   style="background:#f5f7fc;border:1px solid var(--border);color:var(--navy);font-size:13px;border-radius:7px;padding:8px 14px;">
                    <i class="bi bi-pencil-square"></i> Edit Data Peserta
                </button>
                <a href="{{ route('peserta.sertifikat.preview', [$kegiatan, $peserta]) }}"
                   class="btn btn-sm d-flex align-items-center gap-2"
                   style="background:#fdf8ed;border:1px solid #f0d88a;color:#7a5a00;font-size:13px;border-radius:7px;padding:8px 14px;"
                   target="_blank">
                    <i class="bi bi-file-earmark-text"></i> Preview Sertifikat
                </a>
            </div>
        </div>
    </div>

    {{-- ── Kolom Kanan: Hasil Tes ── --}}
    <div class="col-md-8">
        <div class="card">

            {{-- Nav Tabs — FIX: $jenisTesAll sudah difilter per kegiatan di controller --}}
            <div class="card-header p-0" style="background:white;border-bottom:2px solid var(--border);">
                <ul class="nav nav-tabs border-0 px-2 pt-1 flex-wrap gap-0" id="tesTabs" role="tablist">

                    @foreach($jenisTesAll as $idx => $jt)
                    <li class="nav-item d-flex align-items-center" role="presentation">
                        <button class="nav-link {{ $idx === 0 ? 'active' : '' }}"
                                id="tab-btn-{{ $jt->id }}"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-{{ $jt->id }}"
                                type="button"
                                role="tab">
                            {{ $jt->nama }}
                        </button>
                        @if(!in_array($jt->kode, ['potensi', 'kompetensi']))
                        <div class="d-flex gap-1 pe-1">
                            <button type="button"
                                style="padding:2px 7px;font-size:11px;background:#fdf8ed;border:1px solid #f0d88a;color:#7a5a00;border-radius:5px;cursor:pointer;"
                                onclick="bukaEditTes({{ $jt->id }}, '{{ addslashes($jt->nama) }}', '{{ addslashes($jt->header_col1) }}', '{{ addslashes($jt->header_col2) }}', {{ $jt->has_rekomendasi ? 1 : 0 }}, {{ $jt->has_jpm ? 1 : 0 }}, '{{ $jt->kode }}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('jenis-tes.destroy', $jt->id) }}" method="POST" class="d-inline m-0"
                                  onsubmit="return confirm('Hapus jenis tes ini? Semua data terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="padding:2px 7px;font-size:11px;background:#fdf0f2;border:1px solid #f5c2c7;color:#c0392b;border-radius:5px;cursor:pointer;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                    </li>
                    @endforeach

                    <li class="nav-item ms-auto" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-1"
                                id="tab-btn-new"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-new"
                                type="button"
                                role="tab"
                                style="color:var(--gold);font-size:13px;">
                            <i class="bi bi-plus-circle"></i> Tambah Tes
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Tab Content --}}
            <div class="tab-content" id="tesTabsContent">

                @foreach($jenisTesAll as $idx => $jt)
                @php
                    $hasilData = collect($hasilPerJenis)->firstWhere('jenis_tes.id', $jt->id);
                    $items     = $hasilData ? $hasilData['items'] : collect();
                    $ringkasan = $hasilData ? $hasilData['ringkasan'] : null;
                    $pakaiTeks = ($jt->kode === 'potensi' || str_starts_with($jt->kode, 'teks_'));
                @endphp
                <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}"
                     id="tab-{{ $jt->id }}"
                     role="tabpanel">

                    <form action="{{ route('peserta.hasil.save', [$kegiatan, $peserta]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="jenis_tes_id" value="{{ $jt->id }}">

                        <div class="table-responsive">
                            <table class="table table-sm mb-0" style="border-color:var(--border);">
                                <thead>
                                    <tr>
                                        <th class="ps-4" width="48">No</th>
                                        <th>{{ $jt->header_col1 }}</th>
                                        <th width="160" class="text-center">{{ $jt->header_col2 }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $maxRows = $items->isNotEmpty() ? max($items->count(), 8) : 8; @endphp
                                    @for($i = 1; $i <= $maxRows + 2; $i++)
                                    @php $existItem = $items->firstWhere('nomor', $i); @endphp
                                    <tr>
                                        <td class="ps-4 text-center" style="color:var(--text-light);font-size:12px;">{{ $i }}</td>
                                        <td>
                                            <input type="hidden" name="items[{{ $i }}][nomor]" value="{{ $i }}">
                                            <input type="text" name="items[{{ $i }}][nama_item]"
                                                class="form-control form-control-sm border-0"
                                                style="background:transparent;font-size:13px;"
                                                value="{{ $existItem?->nama_item ?? '' }}"
                                                placeholder="—">
                                        </td>
                                        <td>
                                            @if($pakaiTeks)
                                            <input type="text" name="items[{{ $i }}][nilai_text]"
                                                class="form-control form-control-sm text-center"
                                                style="font-size:13px;font-weight:600;color:var(--navy);"
                                                value="{{ $existItem?->nilai_text ?? '' }}"
                                                placeholder="B / BS / C">
                                            @else
                                            <input type="number" name="items[{{ $i }}][nilai_angka]"
                                                class="form-control form-control-sm text-center"
                                                style="font-size:13px;font-weight:600;color:var(--navy);"
                                                value="{{ $existItem?->nilai_angka ?? '' }}"
                                                step="0.01" min="0" max="10" placeholder="0.00">
                                            @endif
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        @if($jt->has_rekomendasi || $jt->has_jpm)
                        <div style="background:#faf8f4;border-top:1px solid var(--border);padding:16px 20px;">
                            @if($jt->has_rekomendasi)
                            <div class="mb-3">
                                <label class="form-label">Rekomendasi</label>
                                <input type="text" name="ringkasan[rekomendasi]" class="form-control"
                                    value="{{ $ringkasan?->rekomendasi ?? '' }}"
                                    placeholder="Optimal / Kurang Optimal / dll">
                            </div>
                            @endif
                            @if($jt->has_jpm)
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Jumlah</label>
                                    <input type="number" name="ringkasan[jumlah]" class="form-control"
                                        value="{{ $ringkasan?->jumlah ?? '' }}" step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Standar</label>
                                    <input type="number" name="ringkasan[standar]" class="form-control"
                                        value="{{ $ringkasan?->standar ?? '' }}" step="0.01" placeholder="18">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">JPM (%)</label>
                                    <input type="number" name="ringkasan[jpm]" class="form-control"
                                        value="{{ $ringkasan?->jpm ?? '' }}" step="0.01">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Kategori</label>
                                    <input type="text" name="ringkasan[kategori]" class="form-control"
                                        value="{{ $ringkasan?->kategori ?? '' }}" placeholder="Optimal / dll">
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div style="padding:14px 20px;border-top:1px solid var(--border);">
                            <button type="submit" class="btn btn-gold btn-sm d-inline-flex align-items-center gap-2">
                                <i class="bi bi-save"></i> Simpan {{ $jt->nama }}
                            </button>
                        </div>
                    </form>
                </div>
                @endforeach

                {{-- Tab Tambah Jenis Tes --}}
                <div class="tab-pane fade" id="tab-new" role="tabpanel">
                    <div style="padding:24px 20px;">
                        <p style="font-size:13px;color:var(--text-light);margin-bottom:16px;">
                            Tambah jenis tes baru selain Potensi &amp; Kompetensi:
                        </p>
                        <form action="{{ route('jenis-tes.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Tes <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control"
                                        placeholder="cth: Tes DISC" required value="{{ old('nama') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Header Kolom 1</label>
                                    <input type="text" name="header_col1" class="form-control"
                                        value="{{ old('header_col1', 'Aspek') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Header Kolom 2</label>
                                    <input type="text" name="header_col2" class="form-control"
                                        value="{{ old('header_col2', 'Nilai') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tipe Nilai</label>
                                    <select name="kode" class="form-select">
                                        <option value="lainnya">Angka (0–10)</option>
                                        <option value="teks_lainnya">Teks (KS/K/C/B/BS)</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Fitur Tambahan</label>
                                    <div class="d-flex flex-column gap-2 mt-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="has_rekomendasi" value="1" id="chk_rek">
                                            <label class="form-check-label" for="chk_rek" style="font-size:13px;">Tampilkan kolom Rekomendasi</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="has_jpm" value="1" id="chk_jpm">
                                            <label class="form-check-label" for="chk_jpm" style="font-size:13px;">Tampilkan Jumlah, JPM &amp; Kategori</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-gold w-100 d-flex align-items-center justify-content-center gap-2">
                                        <i class="bi bi-plus-circle"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ═══ OVERLAY ═══ --}}
<div class="side-panel-overlay" id="overlay" onclick="tutupSemua()"></div>

{{-- ═══ PANEL EDIT PESERTA — dengan field level_jabatan ═══ --}}
<div class="side-panel" id="panelEditPeserta">
    <div class="side-panel-header">
        <span style="font-family:'Playfair Display',serif;font-size:15px;">
            <i class="bi bi-pencil-square me-2" style="color:var(--gold-light);"></i>Edit Data Peserta
        </span>
        <button class="btn-close-panel" onclick="tutupSemua()">×</button>
    </div>
    <form action="{{ route('peserta.update', [$kegiatan, $peserta]) }}" method="POST">
        @csrf @method('PUT')
        <div class="side-panel-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control"
                           value="{{ old('nama', $peserta->nama) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" class="form-control font-monospace"
                           value="{{ old('nip', $peserta->nip) }}" required>
                </div>
                <div class="col-8">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control"
                           value="{{ old('jabatan', $peserta->jabatan) }}"
                           placeholder="cth: Analis Kepegawaian Ahli Muda">
                </div>
                <div class="col-4">
                    <label class="form-label">Level Jabatan</label>
                    <input type="text" name="level_jabatan" class="form-control"
                           value="{{ old('level_jabatan', $peserta->level_jabatan) }}"
                           placeholder="cth: 1, 2, 3">
                </div>
                <div class="col-8">
                    <label class="form-label">Pangkat</label>
                    <input type="text" name="pangkat" class="form-control"
                           value="{{ old('pangkat', $peserta->pangkat) }}">
                </div>
                <div class="col-4">
                    <label class="form-label">Golongan</label>
                    <input type="text" name="golongan" class="form-control"
                           value="{{ old('golongan', $peserta->golongan) }}" placeholder="III/d">
                </div>
                <div class="col-12">
                    <label class="form-label">Perangkat Daerah</label>
                    <input type="text" name="perangkat_daerah" class="form-control"
                           value="{{ old('perangkat_daerah', $peserta->perangkat_daerah) }}">
                </div>
            </div>
        </div>
        <div class="side-panel-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="tutupSemua()">Batal</button>
            <button type="submit" class="btn btn-gold btn-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>
    </form>
</div>

{{-- ═══ PANEL EDIT JENIS TES ═══ --}}
<div class="side-panel" id="panelEditTes">
    <div class="side-panel-header">
        <span style="font-family:'Playfair Display',serif;font-size:15px;">
            <i class="bi bi-pencil me-2" style="color:var(--gold-light);"></i>Edit Jenis Tes
        </span>
        <button class="btn-close-panel" onclick="tutupSemua()">×</button>
    </div>
    <form id="formEditTes" method="POST">
        @csrf @method('PUT')
        <div class="side-panel-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Tes <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Header Kolom 1</label>
                    <input type="text" name="header_col1" id="edit_header1" class="form-control">
                </div>
                <div class="col-6">
                    <label class="form-label">Header Kolom 2</label>
                    <input type="text" name="header_col2" id="edit_header2" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Tipe Nilai</label>
                    <select name="kode" id="edit_kode" class="form-select">
                        <option value="lainnya">Angka (0–10)</option>
                        <option value="teks_lainnya">Teks (KS / K / C / B / BS)</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Fitur Tambahan</label>
                    <div class="d-flex flex-column gap-2 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_rekomendasi" value="1" id="edit_rek">
                            <label class="form-check-label" for="edit_rek" style="font-size:13px;">Tampilkan kolom Rekomendasi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_jpm" value="1" id="edit_jpm">
                            <label class="form-check-label" for="edit_jpm" style="font-size:13px;">Tampilkan Jumlah, JPM &amp; Kategori</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="side-panel-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="tutupSemua()">Batal</button>
            <button type="submit" class="btn btn-gold btn-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-save"></i> Simpan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function bukaPanel(id) {
    document.getElementById('overlay').classList.add('active');
    document.getElementById(id).classList.add('open');
}
function tutupSemua() {
    document.getElementById('overlay').classList.remove('active');
    document.querySelectorAll('.side-panel').forEach(p => p.classList.remove('open'));
}
function bukaEditTes(id, nama, header1, header2, hasRek, hasJpm, kode) {
    document.getElementById('formEditTes').action = '/jenis-tes/' + id;
    document.getElementById('edit_nama').value    = nama;
    document.getElementById('edit_header1').value = header1;
    document.getElementById('edit_header2').value = header2;
    document.getElementById('edit_rek').checked   = hasRek == 1;
    document.getElementById('edit_jpm').checked   = hasJpm == 1;
    var kodeEl = document.getElementById('edit_kode');
    kodeEl.value = (kode === 'potensi' || (kode && kode.startsWith('teks_'))) ? 'teks_lainnya' : 'lainnya';
    bukaPanel('panelEditTes');
}
</script>
@endpush

@endsection