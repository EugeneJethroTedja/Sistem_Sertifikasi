<?php

use App\Http\Controllers\{
    AuthController,
    KegiatanController,
    PesertaController,
    SertifikatController,
    JenisTesController,
};
use Illuminate\Support\Facades\Route;

// ── Auth (Public) ─────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('admin.auth');

// ── Protected Routes (Admin Only) ─────────────────────────────────────
Route::middleware('admin.auth')->group(function () {

    Route::get('/', fn() => redirect()->route('kegiatan.index'));

    // Jenis Tes — di luar prefix kegiatan karena bersifat global
    Route::post('jenis-tes',               [JenisTesController::class, 'store'])->name('jenis-tes.store');
    Route::put('jenis-tes/{jenisTes}',     [JenisTesController::class, 'update'])->name('jenis-tes.update');
    Route::delete('jenis-tes/{jenisTes}',  [JenisTesController::class, 'destroy'])->name('jenis-tes.destroy');

    // Kegiatan
    Route::resource('kegiatan', KegiatanController::class);

    // Peserta & Sertifikat (nested di bawah kegiatan)
    Route::prefix('kegiatan/{kegiatan}')->name('peserta.')->group(function () {

        Route::get('peserta',                [PesertaController::class, 'index'])->name('index');
        Route::get('peserta/create',         [PesertaController::class, 'create'])->name('create');
        Route::post('peserta',               [PesertaController::class, 'store'])->name('store');
        Route::get('peserta/{peserta}',      [PesertaController::class, 'show'])->name('show');
        Route::get('peserta/{peserta}/edit', [PesertaController::class, 'edit'])->name('edit');
        Route::put('peserta/{peserta}',      [PesertaController::class, 'update'])->name('update');
        Route::delete('peserta/{peserta}',   [PesertaController::class, 'destroy'])->name('destroy');

        Route::get('import',  [PesertaController::class, 'importForm'])->name('import');
        Route::post('import', [PesertaController::class, 'import'])->name('import.store');

        Route::post('peserta/{peserta}/hasil', [PesertaController::class, 'saveHasil'])->name('hasil.save');

        Route::get('peserta/{peserta}/sertifikat',          [SertifikatController::class, 'preview'])->name('sertifikat.preview');
        Route::get('peserta/{peserta}/sertifikat/download', [SertifikatController::class, 'download'])->name('sertifikat.download');
        Route::get('sertifikat/download-all',               [SertifikatController::class, 'downloadAll'])->name('sertifikat.download_all');
    });

});