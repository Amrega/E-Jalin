<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuasJalan extends Model
{
    use HasFactory;

    protected $table = 'ruas_jalan';

    protected $fillable = [
        'kabupaten_kota_id',
        'koridor_id',
        'nama_ruas',
        'nomor_ruas',
        'panjang_ruas',
        'status_jalan',
        'file_kml_ruas',
        'void',
    ];

    protected $casts = [
        'panjang_ruas' => 'double',
        'void'         => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /**
     * Relasi ke master Kabupaten / Kota
     */
    public function kabupatenKota()
    {
        return $this->belongsTo(KabupatenKota::class, 'kabupaten_kota_id');
    }

    /**
     * Relasi ke Koridor (opsional / nullable)
     */
    public function koridor()
    {
        return $this->belongsTo(Koridor::class, 'koridor_id');
    }

    /**
     * Relasi ke Jembatan pada ruas jalan ini
     */
    public function jembatan()
    {
        return $this->hasMany(Jembatan::class, 'ruas_jalan_id');
    }
}
