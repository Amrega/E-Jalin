<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = [
        'nama_role',
        'void',
    ];

    protected $casts = [
        'void'       => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User pengguna sistem
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
