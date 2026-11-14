<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'role_id',
        'kabupaten_kota_id',
        'void',
        'password',
    ];

    /**
     * Relasi ke master Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relasi ke Kabupaten / Kota (untuk akun Pemda Kab/Kota)
     */
    public function kabupatenKota()
    {
        return $this->belongsTo(KabupatenKota::class, 'kabupaten_kota_id');
    }

    /**
     * Cek nama role user saat ini (lowercase)
     */
    public function roleName(): string
    {
        return strtolower(trim($this->role->nama_role ?? ''));
    }

    /**
     * Cek apakah user memiliki salah satu role yang diberikan
     */
    public function hasRole(string|array $roles): bool
    {
        $current = $this->roleName();
        $roles = is_array($roles) ? array_map('strtolower', $roles) : [strtolower($roles)];
        return in_array($current, $roles, true);
    }

    /**
     * Apakah user adalah Administrator
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('administrator');
    }

    /**
     * Apakah user merupakan akun Pemerintah Daerah Kabupaten/Kota
     */
    public function isKabupatenKota(): bool
    {
        return $this->hasRole(['kabupaten_kota', 'pemda']) || (!empty($this->kabupaten_kota_id) && !$this->isAdmin() && !$this->isVerifierRole());
    }

    /**
     * Apakah user merupakan role verifikator / indikasi (pu_turdal, bappeda, pu_bintek)
     */
    public function isVerifierRole(): bool
    {
        return $this->hasRole(['pu_turdal', 'bappeda', 'pu_bintek']);
    }

    /**
     * Cek apakah user boleh menambah / mengedit / menghapus data Usulan.
     * - pu_turdal, bappeda, pu_bintek: TIDAK BISA tambah/edit/hapus usulan (hanya lihat & verifikasi/indikasi).
     * - kabupaten_kota: Hanya bisa tambah usulan milik kab/kotanya sendiri dan edit/hapus usulan milik kab/kotanya sendiri.
     * - administrator: Bisa kelola semua usulan.
     */
    public function canManageUsulan(?Usulan $usulan = null): bool
    {
        if ($this->isVerifierRole()) {
            return false;
        }

        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isKabupatenKota()) {
            if ($usulan === null) {
                return true;
            }
            return (int) $usulan->kabupaten_kota_id === (int) $this->kabupaten_kota_id;
        }

        return false;
    }

    /**
     * pu_turdal melakukan indikasi ruas prioritas di PU
     */
    public function canEditPrioritasPu(): bool
    {
        return $this->hasRole(['pu_turdal', 'administrator']);
    }

    /**
     * bappeda melakukan indikasi ruas prioritas di Bappeda
     */
    public function canEditPrioritasBappeda(): bool
    {
        return $this->hasRole(['bappeda', 'administrator']);
    }

    /**
     * pu_bintek melakukan verifikasi di PU
     */
    public function canEditVerifikasiPu(): bool
    {
        return $this->hasRole(['pu_bintek', 'administrator']);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'void' => 'boolean',
        ];
    }
}
