<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jembatan extends Model
{
    use HasFactory;

    protected $table = 'jembatan';

    protected $fillable = [
        'ruas_jalan_id',
        'nama_jembatan',
        'nomor_jembatan',
        'panjang_jembatan',
        'status_jembatan',
        'file_kml_jembatan',
        'void',
    ];

    protected $casts = [
        'panjang_jembatan' => 'double',
        'void'             => 'boolean',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Relasi ke Ruas Jalan
     */
    public function ruasJalan()
    {
        return $this->belongsTo(RuasJalan::class, 'ruas_jalan_id');
    }
}
