<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TematikKawasan extends Model
{
    use HasFactory;

    protected $table = 'tematik_kawasan';

    protected $fillable = [
        'nama_tematik',
        'void',
    ];

    protected $casts = [
        'void'       => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
