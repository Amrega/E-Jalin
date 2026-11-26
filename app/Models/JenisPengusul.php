<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPengusul extends Model
{
    use HasFactory;

    protected $table = 'jenis_pengusul';

    protected $fillable = [
        'nama_jenis_pengusul',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke master Pengusul
     */
    public function pengusul()
    {
        return $this->hasMany(Pengusul::class, 'jenis_pengusul_id');
    }

    /**
     * Relasi ke Usulan Aspal
     */
    public function usulanAspal()
    {
        return $this->hasMany(UsulanAspal::class, 'jenis_pengusul_id');
    }
}
