<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsi';

    public $timestamps = false;

    protected $fillable = [
        'nama_provinsi',
        'void',
    ];

    protected $casts = [
        'void' => 'boolean',
    ];

    public function kabupatenKota()
    {
        return $this->hasMany(KabupatenKota::class, 'provinsi_id');
    }
}
