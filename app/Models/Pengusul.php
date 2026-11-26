<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengusul extends Model
{
    use HasFactory;

    protected $table = 'pengusul';

    protected $fillable = [
        'nama_pengusul',
        'jenis_pengusul_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke master Jenis Pengusul
     */
    public function jenisPengusul()
    {
        return $this->belongsTo(JenisPengusul::class, 'jenis_pengusul_id');
    }

    /**
     * Relasi ke akun Pengguna (users)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
