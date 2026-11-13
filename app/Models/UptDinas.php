<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UptDinas extends Model
{
    use HasFactory;

    protected $table = 'upt_dinas';

    protected $fillable = [
        'provinsi_id',
        'nama_upt',
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

    public function kabupatenKota()
    {
        return $this->hasMany(KabupatenKota::class, 'upt_id');
    }
}
