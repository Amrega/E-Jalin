<?php

namespace Database\Seeders;

use App\Models\Pengusul;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengusulPemdaSeeder extends Seeder
{
    /**
     * Seed data master Pengusul (Bupati / Walikota) untuk seluruh akun Pemda Kabupaten/Kota.
     */
    public function run(): void
    {
        $instansiId = DB::table('jenis_pengusul')
            ->where('nama_jenis_pengusul', 'Instansi')
            ->value('id');

        if (!$instansiId) {
            DB::table('jenis_pengusul')->upsert([
                ['id' => 1, 'nama_jenis_pengusul' => 'Instansi', 'created_at' => now(), 'updated_at' => now()],
                ['id' => 2, 'nama_jenis_pengusul' => 'Lembaga', 'created_at' => now(), 'updated_at' => now()],
            ], ['id'], ['nama_jenis_pengusul', 'updated_at']);

            $instansiId = DB::table('jenis_pengusul')
                ->where('nama_jenis_pengusul', 'Instansi')
                ->value('id') ?? 1;
        }

        $pemdaUsers = User::with('kabupatenKota')
            ->whereNotNull('kabupaten_kota_id')
            ->where('role_id', 5)
            ->orderBy('kabupaten_kota_id', 'asc')
            ->get();

        foreach ($pemdaUsers as $pemdaUser) {
            if (!$pemdaUser->kabupatenKota) {
                continue;
            }

            $namaKabKota = trim($pemdaUser->kabupatenKota->nama_kabupaten_kota);
            $lowerName = strtolower($namaKabKota);

            if (str_starts_with($lowerName, 'kabupaten ')) {
                $namaPengusul = 'Bupati ' . trim(substr($namaKabKota, 10));
            } elseif (str_starts_with($lowerName, 'kota ')) {
                $namaPengusul = 'Walikota ' . trim(substr($namaKabKota, 5));
            } else {
                $namaPengusul = $namaKabKota;
            }

            Pengusul::updateOrCreate(
                [
                    'user_id'       => $pemdaUser->id,
                    'nama_pengusul' => $namaPengusul,
                ],
                [
                    'jenis_pengusul_id' => $instansiId,
                ]
            );
        }
    }
}
