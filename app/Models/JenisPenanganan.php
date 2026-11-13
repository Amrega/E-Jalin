<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenanganan extends Model
{
    use HasFactory;

    protected $table = 'jenis_penanganan';

    protected $fillable = [
        'target_penangan',
        'nama_penanganan',
        'void',
    ];

    protected $casts = [
        'void'       => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor untuk target_penanganan (alias dari target_penangan)
     */
    public function getTargetPenangananAttribute()
    {
        return $this->attributes['target_penangan'] ?? null;
    }

    /**
     * Mutator untuk target_penanganan (alias ke target_penangan)
     */
    public function setTargetPenangananAttribute($value)
    {
        $this->attributes['target_penangan'] = $value;
    }
}
