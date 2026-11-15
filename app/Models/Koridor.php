<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koridor extends Model
{
    use HasFactory;

    protected $table = 'koridor';

    protected $fillable = [
        'provinsi_id',
        'nama_koridor',
        'nomor_koridor',
        'panjang_koridor',
        'file_kml_koridor',
        'void',
    ];

    protected $casts = [
        'panjang_koridor' => 'double',
        'void' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function ruasJalan()
    {
        return $this->hasMany(RuasJalan::class, 'koridor_id');
    }
}
