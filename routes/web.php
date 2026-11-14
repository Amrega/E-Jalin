<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GeospasialController;
use App\Http\Controllers\JembatanController;
use App\Http\Controllers\JenisPenangananController;
use App\Http\Controllers\KabupatenKotaController;
use App\Http\Controllers\KoridorController;
use App\Http\Controllers\PenangananController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RuasJalanController;
use App\Http\Controllers\TematikKawasanController;
use App\Http\Controllers\UptDinasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsulanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('geospasial.index');
    }
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('geospasial.index');
    })->name('dashboard');

    // Visualisasi Peta Geospasial GIS
    Route::get('/geospasial', [GeospasialController::class, 'index'])->name('geospasial.index');
    Route::get('/peta', function () {
        return redirect()->route('geospasial.index');
    });

    // CRUD Usulan
    Route::resource('usulan', UsulanController::class)->parameters(['usulan' => 'usulan']);
    Route::post('usulan/{usulan}/update-status', [UsulanController::class, 'updateStatus'])->name('usulan.update-status');
    Route::post('usulan/{usulan}/toggle-void', [UsulanController::class, 'toggleVoid'])->name('usulan.toggle-void');
    Route::get('usulan/{usulan}/download-dokumen', [UsulanController::class, 'downloadDokumen'])->name('usulan.download-dokumen');
    Route::redirect('usulan-inpres', '/usulan');

    Route::get('/referensi-jembatan', function () {
        return redirect()->route('jembatan.index');
    })->name('referensi-jembatan');

    // CRUD Jembatan
    Route::resource('jembatan', JembatanController::class)->parameters(['jembatan' => 'jembatan']);
    Route::post('jembatan/{jembatan}/toggle-void', [JembatanController::class, 'toggleVoid'])->name('jembatan.toggle-void');
    Route::get('jembatan/{jembatan}/download-kml', [JembatanController::class, 'downloadKml'])->name('jembatan.download-kml');

    // CRUD Ruas Jalan
    Route::resource('ruas', RuasJalanController::class)->parameters(['ruas' => 'ruas']);
    Route::post('ruas/{ruas}/toggle-void', [RuasJalanController::class, 'toggleVoid'])->name('ruas.toggle-void');
    Route::get('ruas/{ruas}/download-kml', [RuasJalanController::class, 'downloadKml'])->name('ruas.download-kml');

    // CRUD Koridor
    Route::resource('koridor', KoridorController::class);
    Route::post('koridor/{koridor}/toggle-void', [KoridorController::class, 'toggleVoid'])->name('koridor.toggle-void');
    Route::get('koridor/{koridor}/download-kml', [KoridorController::class, 'downloadKml'])->name('koridor.download-kml');

    // CRUD Role
    Route::resource('role', RoleController::class);
    Route::post('role/{role}/toggle-void', [RoleController::class, 'toggleVoid'])->name('role.toggle-void');

    // CRUD Pengguna (Users)
    Route::resource('user', UserController::class);
    Route::post('user/{user}/toggle-void', [UserController::class, 'toggleVoid'])->name('user.toggle-void');

    // CRUD Tematik Kawasan
    Route::resource('tematik-kawasan', TematikKawasanController::class)->parameters(['tematik-kawasan' => 'tematik_kawasan']);
    Route::post('tematik-kawasan/{tematik_kawasan}/toggle-void', [TematikKawasanController::class, 'toggleVoid'])->name('tematik-kawasan.toggle-void');

    // CRUD Jenis Penanganan
    Route::resource('jenis-penanganan', JenisPenangananController::class)->parameters(['jenis-penanganan' => 'jenis_penanganan']);
    Route::post('jenis-penanganan/{jenis_penanganan}/toggle-void', [JenisPenangananController::class, 'toggleVoid'])->name('jenis-penanganan.toggle-void');
    Route::redirect('penanganan', '/jenis-penanganan');

    // CRUD Kabupaten / Kota
    Route::resource('kabupaten-kota', KabupatenKotaController::class)->parameters(['kabupaten-kota' => 'kabupaten_kota']);
    Route::post('kabupaten-kota/{kabupaten_kota}/toggle-void', [KabupatenKotaController::class, 'toggleVoid'])->name('kabupaten-kota.toggle-void');

    // CRUD UPT Dinas (UPT PJJ)
    Route::resource('upt-dinas', UptDinasController::class)->parameters(['upt-dinas' => 'upt_dinas']);
    Route::post('upt-dinas/{upt_dinas}/toggle-void', [UptDinasController::class, 'toggleVoid'])->name('upt-dinas.toggle-void');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
