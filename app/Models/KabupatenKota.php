<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KabupatenKota extends Model
{
    use HasFactory;

    protected $table = 'kabupaten_kota';

    protected $fillable = [
        'provinsi_id',
        'upt_id',
        'nama_kabupaten_kota',
        'void',
    ];

    protected $casts = [
        'void'       => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function uptDinas()
    {
        return $this->belongsTo(UptDinas::class, 'upt_id');
    }

    public function koridor()
    {
        return $this->hasMany(Koridor::class, 'kabupaten_kota_id');
    }

    public function ruasJalan()
    {
        return $this->hasMany(RuasJalan::class, 'kabupaten_kota_id');
    }
}
