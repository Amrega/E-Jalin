<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    use HasFactory;

    protected $table = 'usulan';

    protected $fillable = [
        'kode_usulan',
        'kabupaten_kota_id',
        'upt_dinas_id',
        'tematik_kawasan_id',
        'jenis_penanganan_id',
        'koridor_id',
        'ruas_jalan_id',
        'jembatan_id',
        'nama_pengusul',
        'nama_kegiatan',
        'status_ruas',
        'panjang_penanganan',
        'satuan',
        'alokasi_usulan',
        'keterangan',
        'status_dokumen_pendukung',
        'file_dokumen_pendukung',
        'prioritas_pu',
        'prioritas_bappeda',
        'verifikasi_pu',
        'void',
    ];

    protected $casts = [
        'panjang_penanganan' => 'double',
        'alokasi_usulan'     => 'integer',
        'prioritas_pu'       => 'boolean',
        'prioritas_bappeda'  => 'boolean',
        'verifikasi_pu'      => 'boolean',
        'void'               => 'boolean',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    /**
     * Relasi ke Kabupaten / Kota
     */
    public function kabupatenKota()
    {
        return $this->belongsTo(KabupatenKota::class, 'kabupaten_kota_id');
    }

    /**
     * Relasi ke UPT Dinas (UPT PJJ yang mendapat tugas dari PU Turdal)
     */
    public function uptDinas()
    {
        return $this->belongsTo(UptDinas::class, 'upt_dinas_id');
    }

    /**
     * Relasi ke Tematik Kawasan
     */
    public function tematikKawasan()
    {
        return $this->belongsTo(TematikKawasan::class, 'tematik_kawasan_id');
    }

    /**
     * Relasi ke Jenis Penanganan
     */
    public function jenisPenanganan()
    {
        return $this->belongsTo(JenisPenanganan::class, 'jenis_penanganan_id');
    }

    /**
     * Relasi ke Koridor
     */
    public function koridor()
    {
        return $this->belongsTo(Koridor::class, 'koridor_id');
    }

    /**
     * Relasi ke Ruas Jalan
     */
    public function ruasJalan()
    {
        return $this->belongsTo(RuasJalan::class, 'ruas_jalan_id');
    }

    /**
     * Relasi ke Jembatan
     */
    public function jembatan()
    {
        return $this->belongsTo(Jembatan::class, 'jembatan_id');
    }
}
