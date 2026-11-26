<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAspal extends Model
{
    use HasFactory;

    protected $table = 'usulan_aspal';

    protected $fillable = [
        'tahun',
        'kabupaten_kota',
        'qty_drum',
        'keterangan_urgensi',
        'pengusul_id',
        'jenis_pengusul_id',
        'upload_surat_permohonan',
    ];

    protected $casts = [
        'tahun'      => 'integer',
        'qty_drum'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke master Pengusul
     */
    public function pengusul()
    {
        return $this->belongsTo(Pengusul::class, 'pengusul_id');
    }

    /**
     * Relasi ke master Jenis Pengusul
     */
    public function jenisPengusul()
    {
        return $this->belongsTo(JenisPengusul::class, 'jenis_pengusul_id');
    }
}
