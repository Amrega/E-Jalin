<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database from ejalin.sql data.
     */
    public function run(): void
    {
        // 1. Role
        DB::table('role')->upsert([
            ['id' => 1, 'nama_role' => 'administrator', 'void' => 0, 'created_at' => '2026-11-12 14:38:45', 'updated_at' => '2026-11-12 14:38:45'],
            ['id' => 2, 'nama_role' => 'pu_turdal', 'void' => 0, 'created_at' => '2026-11-12 14:38:45', 'updated_at' => '2026-11-12 14:38:45'],
            ['id' => 3, 'nama_role' => 'bappeda', 'void' => 0, 'created_at' => '2026-11-12 14:38:45', 'updated_at' => '2026-11-12 14:38:45'],
            ['id' => 4, 'nama_role' => 'pu_bintek', 'void' => 0, 'created_at' => '2026-11-12 14:38:45', 'updated_at' => '2026-11-12 14:38:45'],
        ], ['id'], ['nama_role', 'void', 'updated_at']);

        // 2. Users
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator E-Jalin',
                'email' => 'admin@ejalin.jatimprov.go.id',
                'role_id' => 1,
                'void' => 0,
                'password' => Hash::make('admin'),
            ]
        );

        // 3. Provinsi
        DB::table('provinsi')->upsert([
            ['id' => 1, 'nama_provinsi' => 'Jawa Timur', 'void' => 0, 'created_at' => '2026-11-12 13:20:01', 'updated_at' => '2026-11-12 13:20:01'],
        ], ['id'], ['nama_provinsi', 'void', 'updated_at']);

        // 4. UPT Dinas
        DB::table('upt_dinas')->upsert([
            ['id' => 1, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Surabaya', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 2, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Mojokerto', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 3, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Malang', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 4, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Kediri', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 5, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Madiun', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 6, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Pacitan', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 7, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Bojonegoro', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 8, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Probolinggo', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 9, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Jember', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 10, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Banyuwangi', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
            ['id' => 11, 'provinsi_id' => 1, 'nama_upt' => 'UPT PJJ Pamekasan', 'void' => 0, 'created_at' => '2026-11-12 15:24:10', 'updated_at' => '2026-11-12 15:24:10'],
        ], ['id'], ['provinsi_id', 'nama_upt', 'void', 'updated_at']);

        // 5. Kabupaten / Kota
        DB::table('kabupaten_kota')->upsert([
            ['id' => 1, 'provinsi_id' => 1, 'upt_id' => 11, 'nama_kabupaten_kota' => 'Kabupaten Bangkalan', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 2, 'provinsi_id' => 1, 'upt_id' => 10, 'nama_kabupaten_kota' => 'Kabupaten Banyuwangi', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 3, 'provinsi_id' => 1, 'upt_id' => 6, 'nama_kabupaten_kota' => 'Kabupaten Blitar', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 4, 'provinsi_id' => 1, 'upt_id' => 3, 'nama_kabupaten_kota' => 'Kabupaten Bojonegoro', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 5, 'provinsi_id' => 1, 'upt_id' => 9, 'nama_kabupaten_kota' => 'Kabupaten Bondowoso', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 6, 'provinsi_id' => 1, 'upt_id' => 1, 'nama_kabupaten_kota' => 'Kabupaten Gresik', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 7, 'provinsi_id' => 1, 'upt_id' => 9, 'nama_kabupaten_kota' => 'Kabupaten Jember', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 8, 'provinsi_id' => 1, 'upt_id' => 2, 'nama_kabupaten_kota' => 'Kabupaten Jombang', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 9, 'provinsi_id' => 1, 'upt_id' => 6, 'nama_kabupaten_kota' => 'Kabupaten Kediri', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 10, 'provinsi_id' => 1, 'upt_id' => 1, 'nama_kabupaten_kota' => 'Kabupaten Lamongan', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 11, 'provinsi_id' => 1, 'upt_id' => 8, 'nama_kabupaten_kota' => 'Kabupaten Lumajang', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 12, 'provinsi_id' => 1, 'upt_id' => 4, 'nama_kabupaten_kota' => 'Kabupaten Madiun', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 13, 'provinsi_id' => 1, 'upt_id' => 4, 'nama_kabupaten_kota' => 'Kabupaten Magetan', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 14, 'provinsi_id' => 1, 'upt_id' => 7, 'nama_kabupaten_kota' => 'Kabupaten Malang', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 15, 'provinsi_id' => 1, 'upt_id' => 2, 'nama_kabupaten_kota' => 'Kabupaten Mojokerto', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 16, 'provinsi_id' => 1, 'upt_id' => 6, 'nama_kabupaten_kota' => 'Kabupaten Nganjuk', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 17, 'provinsi_id' => 1, 'upt_id' => 4, 'nama_kabupaten_kota' => 'Kabupaten Ngawi', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 18, 'provinsi_id' => 1, 'upt_id' => 5, 'nama_kabupaten_kota' => 'Kabupaten Pacitan', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 19, 'provinsi_id' => 1, 'upt_id' => 11, 'nama_kabupaten_kota' => 'Kabupaten Pamekasan', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 20, 'provinsi_id' => 1, 'upt_id' => 8, 'nama_kabupaten_kota' => 'Kabupaten Pasuruan', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 21, 'provinsi_id' => 1, 'upt_id' => 4, 'nama_kabupaten_kota' => 'Kabupaten Ponorogo', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 22, 'provinsi_id' => 1, 'upt_id' => 8, 'nama_kabupaten_kota' => 'Kabupaten Probolinggo', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 23, 'provinsi_id' => 1, 'upt_id' => 11, 'nama_kabupaten_kota' => 'Kabupaten Sampang', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 24, 'provinsi_id' => 1, 'upt_id' => 1, 'nama_kabupaten_kota' => 'Kabupaten Sidoarjo', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 25, 'provinsi_id' => 1, 'upt_id' => 10, 'nama_kabupaten_kota' => 'Kabupaten Situbondo', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 26, 'provinsi_id' => 1, 'upt_id' => 11, 'nama_kabupaten_kota' => 'Kabupaten Sumenep', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 27, 'provinsi_id' => 1, 'upt_id' => 4, 'nama_kabupaten_kota' => 'Kabupaten Trenggalek', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 28, 'provinsi_id' => 1, 'upt_id' => 3, 'nama_kabupaten_kota' => 'Kabupaten Tuban', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 29, 'provinsi_id' => 1, 'upt_id' => 6, 'nama_kabupaten_kota' => 'Kabupaten Tulungagung', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 30, 'provinsi_id' => 1, 'upt_id' => 7, 'nama_kabupaten_kota' => 'Kota Batu', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 31, 'provinsi_id' => 1, 'upt_id' => 6, 'nama_kabupaten_kota' => 'Kota Blitar', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 32, 'provinsi_id' => 1, 'upt_id' => 6, 'nama_kabupaten_kota' => 'Kota Kediri', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 33, 'provinsi_id' => 1, 'upt_id' => 4, 'nama_kabupaten_kota' => 'Kota Madiun', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 34, 'provinsi_id' => 1, 'upt_id' => 7, 'nama_kabupaten_kota' => 'Kota Malang', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 35, 'provinsi_id' => 1, 'upt_id' => 2, 'nama_kabupaten_kota' => 'Kota Mojokerto', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
            ['id' => 36, 'provinsi_id' => 1, 'upt_id' => 8, 'nama_kabupaten_kota' => 'Kota Pasuruan', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 37, 'provinsi_id' => 1, 'upt_id' => 8, 'nama_kabupaten_kota' => 'Kota Probolinggo', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:42'],
            ['id' => 38, 'provinsi_id' => 1, 'upt_id' => 1, 'nama_kabupaten_kota' => 'Kota Surabaya', 'void' => 0, 'created_at' => '2026-11-12 13:20:02', 'updated_at' => '2026-11-12 15:33:41'],
        ], ['id'], ['provinsi_id', 'upt_id', 'nama_kabupaten_kota', 'void', 'updated_at']);

        // 6. Jenis Penanganan
        DB::table('jenis_penanganan')->upsert([
            ['id' => 1, 'target_penangan' => 'jalan', 'nama_penanganan' => 'Pemeliharaan Rutin Jalan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 2, 'target_penangan' => 'jalan', 'nama_penanganan' => 'Pemeliharaan Berkala Jalan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 3, 'target_penangan' => 'jalan', 'nama_penanganan' => 'Rehabilitasi Jalan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 4, 'target_penangan' => 'jalan', 'nama_penanganan' => 'Rekonstruksi Jalan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 5, 'target_penangan' => 'jalan', 'nama_penanganan' => 'Pelebaran Jalan Menuju Standar', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 6, 'target_penangan' => 'jembatan', 'nama_penanganan' => 'Pemeliharaan Rutin Jembatan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 7, 'target_penangan' => 'jembatan', 'nama_penanganan' => 'Pemeliharaan Berkala Jembatan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 8, 'target_penangan' => 'jembatan', 'nama_penanganan' => 'Rehabilitasi Jembatan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 9, 'target_penangan' => 'jembatan', 'nama_penanganan' => 'Penggantian Jembatan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 10, 'target_penangan' => 'jembatan', 'nama_penanganan' => 'Pelebaran dan Duplikasi Jembatan', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 11, 'target_penangan' => 'jembatan', 'nama_penanganan' => 'Perbaikan dan Penanganan Sementara', 'void' => 0, 'created_at' => '2026-11-12 15:00:20', 'updated_at' => '2026-11-12 15:00:20'],
            ['id' => 14, 'target_penangan' => 'jembatan', 'nama_penanganan' => 'tes', 'void' => 0, 'created_at' => '2026-11-12 21:36:51', 'updated_at' => '2026-11-12 21:36:51'],
        ], ['id'], ['target_penangan', 'nama_penanganan', 'void', 'updated_at']);

        // 7. Tematik Kawasan
        DB::table('tematik_kawasan')->upsert([
            ['id' => 1, 'nama_tematik' => 'Swasembada Pangan - Pertanian', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 19:45:28'],
            ['id' => 2, 'nama_tematik' => 'Swasembada Pangan - Perkebunan', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 3, 'nama_tematik' => 'Swasembada Pangan - Perikanan', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 4, 'nama_tematik' => 'Swasembada Pangan - Peternakan', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 5, 'nama_tematik' => 'Swasembada Pangan - Kawasan Mendukung Program MBG', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 6, 'nama_tematik' => 'Distribusi Energi - Perkebunan yang mendukung Energi Terbarukan', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 7, 'nama_tematik' => 'Distribusi Energi - Mendukung Distribusi BBM satu harga', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 8, 'nama_tematik' => 'Kawasan Produktif Lainnya - Kawasan Transmigrasi', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 9, 'nama_tematik' => 'Kawasan Produktif Lainnya - Kawasan Industri Prioritas', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 10, 'nama_tematik' => 'Kawasan Produktif Lainnya - Kawasan Strategis Daerah', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 11, 'nama_tematik' => 'Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Nasional)', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 12, 'nama_tematik' => 'Konektivitas - Terhubung Ke Status Lebih Tinggi (Jalan Provinsi)', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
            ['id' => 13, 'nama_tematik' => 'Konektivitas - Terhubung Ke Simpul Transportasi', 'void' => 0, 'created_at' => '2026-11-12 14:49:13', 'updated_at' => '2026-11-12 14:49:13'],
        ], ['id'], ['nama_tematik', 'void', 'updated_at']);

        // 8. Koridor
        DB::table('koridor')->upsert([
            ['id' => 1, 'kabupaten_kota_id' => 1, 'nama_koridor' => 'tes', 'nomor_koridor' => 'tes', 'panjang_koridor' => 30.5, 'file_kml_koridor' => '1794471188_doc.kml', 'void' => 1, 'created_at' => '2026-11-12 01:07:47', 'updated_at' => '2026-11-13 05:24:22'],
            ['id' => 2, 'kabupaten_kota_id' => 2, 'nama_koridor' => 'Koridor 08: Lingkar Ijen - Banyuwangi (Ketapang - Baluran - Ijen - Rogojampi)', 'nomor_koridor' => 'KOR-08', 'panjang_koridor' => 95.6, 'file_kml_koridor' => 'koridor_geo_2.kml', 'void' => 0, 'created_at' => '2026-11-12 17:28:11', 'updated_at' => '2026-11-12 22:41:12'],
            ['id' => 3, 'kabupaten_kota_id' => 28, 'nama_koridor' => 'Koridor 01: Pantura Jatim (Tuban - Lamongan - Gresik - Surabaya)', 'nomor_koridor' => 'KOR-01', 'panjang_koridor' => 124.5, 'file_kml_koridor' => 'koridor_geo_3.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:57', 'updated_at' => '2026-11-12 22:41:12'],
            ['id' => 4, 'kabupaten_kota_id' => 18, 'nama_koridor' => 'Koridor 02: Pansela Jatim (Pacitan - Trenggalek - Tulungagung)', 'nomor_koridor' => 'KOR-02', 'panjang_koridor' => 148.8, 'file_kml_koridor' => 'koridor_geo_4.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:41:12'],
            ['id' => 5, 'kabupaten_kota_id' => 12, 'nama_koridor' => 'Koridor 03: Lingkar Wilis (Madiun - Ponorogo - Nganjuk - Kediri)', 'nomor_koridor' => 'KOR-03', 'panjang_koridor' => 135.2, 'file_kml_koridor' => 'koridor_geo_5.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:41:12'],
            ['id' => 6, 'kabupaten_kota_id' => 22, 'nama_koridor' => 'Koridor 04: Bromo-Semeru (Pasuruan - Probolinggo - Lumajang)', 'nomor_koridor' => 'KOR-04', 'panjang_koridor' => 112.7, 'file_kml_koridor' => 'koridor_geo_6.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:41:12'],
            ['id' => 7, 'kabupaten_kota_id' => 7, 'nama_koridor' => 'Koridor 05: Tapal Kuda Timur (Jember - Bondowoso - Situbondo)', 'nomor_koridor' => 'KOR-05', 'panjang_koridor' => 88.4, 'file_kml_koridor' => 'koridor_geo_7.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:57'],
            ['id' => 8, 'kabupaten_kota_id' => 1, 'nama_koridor' => 'Koridor 06: Trans Madura (Bangkalan - Sampang - Pamekasan - Sumenep)', 'nomor_koridor' => 'KOR-06', 'panjang_koridor' => 156.3, 'file_kml_koridor' => 'koridor_geo_8.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:41:12'],
            ['id' => 9, 'kabupaten_kota_id' => 38, 'nama_koridor' => 'Koridor 07: Poros Metropolitan (Mojokerto - Sidoarjo - Surabaya)', 'nomor_koridor' => 'KOR-07', 'panjang_koridor' => 64.1, 'file_kml_koridor' => 'koridor_geo_9.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:57'],
        ], ['id'], ['kabupaten_kota_id', 'nama_koridor', 'nomor_koridor', 'panjang_koridor', 'file_kml_koridor', 'void', 'updated_at']);

        // 9. Ruas Jalan
        DB::table('ruas_jalan')->upsert([
            ['id' => 1, 'kabupaten_kota_id' => 17, 'koridor_id' => 5, 'nama_ruas' => 'Ruas Jalan Kresikan - Dawung', 'nomor_ruas' => 'RUAS-01', 'panjang_ruas' => 22.4, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_1.kml', 'void' => 0, 'created_at' => '2026-11-12 17:51:17', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 2, 'kabupaten_kota_id' => 28, 'koridor_id' => 3, 'nama_ruas' => 'Ruas Jalan Tuban - Babat', 'nomor_ruas' => 'RUAS-02', 'panjang_ruas' => 31.8, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_2.kml', 'void' => 0, 'created_at' => '2026-11-12 17:55:19', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 3, 'kabupaten_kota_id' => 3, 'koridor_id' => null, 'nama_ruas' => 'tes', 'nomor_ruas' => 'tes', 'panjang_ruas' => 3.6, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => '1794531957_1794471188-doc.kml', 'void' => 1, 'created_at' => '2026-11-12 18:05:57', 'updated_at' => '2026-11-13 05:24:22'],
            ['id' => 4, 'kabupaten_kota_id' => 29, 'koridor_id' => 4, 'nama_ruas' => 'Ruas Jalan Blitar - Tulungagung', 'nomor_ruas' => 'RUAS-04', 'panjang_ruas' => 28.5, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_4.kml', 'void' => 0, 'created_at' => '2026-11-12 18:41:30', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 5, 'kabupaten_kota_id' => 10, 'koridor_id' => 3, 'nama_ruas' => 'Ruas Jalan Paciran - Brondong', 'nomor_ruas' => 'RUAS-05', 'panjang_ruas' => 14.2, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_5.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 6, 'kabupaten_kota_id' => 10, 'koridor_id' => 3, 'nama_ruas' => 'Ruas Jalan Babat - Sukodadi', 'nomor_ruas' => 'RUAS-06', 'panjang_ruas' => 18.6, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_6.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 7, 'kabupaten_kota_id' => 6, 'koridor_id' => 3, 'nama_ruas' => 'Ruas Jalan Manyar - Sidayu', 'nomor_ruas' => 'RUAS-07', 'panjang_ruas' => 17.9, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_7.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 8, 'kabupaten_kota_id' => 20, 'koridor_id' => 9, 'nama_ruas' => 'Ruas Jalan Porong - Bangil', 'nomor_ruas' => 'RUAS-08', 'panjang_ruas' => 16.3, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_8.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 9, 'kabupaten_kota_id' => 20, 'koridor_id' => 9, 'nama_ruas' => 'Ruas Jalan Pandaan - Purwosari', 'nomor_ruas' => 'RUAS-09', 'panjang_ruas' => 12.5, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_9.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 10, 'kabupaten_kota_id' => 14, 'koridor_id' => 9, 'nama_ruas' => 'Ruas Jalan Singosari - Kepanjen', 'nomor_ruas' => 'RUAS-10', 'panjang_ruas' => 32.4, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_10.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:58', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 11, 'kabupaten_kota_id' => 22, 'koridor_id' => 6, 'nama_ruas' => 'Ruas Jalan Tongas - Banyuanyar', 'nomor_ruas' => 'RUAS-11', 'panjang_ruas' => 19.8, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_11.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 12, 'kabupaten_kota_id' => 11, 'koridor_id' => 6, 'nama_ruas' => 'Ruas Jalan Ranuyoso - Kedungjajang', 'nomor_ruas' => 'RUAS-12', 'panjang_ruas' => 15.6, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_12.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 13, 'kabupaten_kota_id' => 7, 'koridor_id' => 7, 'nama_ruas' => 'Ruas Jalan Tanggul - Rambipuji', 'nomor_ruas' => 'RUAS-13', 'panjang_ruas' => 20.4, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_13.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 14, 'kabupaten_kota_id' => 2, 'koridor_id' => 2, 'nama_ruas' => 'Ruas Jalan Genteng - Rogojampi', 'nomor_ruas' => 'RUAS-14', 'panjang_ruas' => 24.8, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_14.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 15, 'kabupaten_kota_id' => 12, 'koridor_id' => 5, 'nama_ruas' => 'Ruas Jalan Caruban - Saradan', 'nomor_ruas' => 'RUAS-15', 'panjang_ruas' => 14.5, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_15.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 16, 'kabupaten_kota_id' => 16, 'koridor_id' => 5, 'nama_ruas' => 'Ruas Jalan Baron - Kertosono', 'nomor_ruas' => 'RUAS-16', 'panjang_ruas' => 8.2, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_16.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 17, 'kabupaten_kota_id' => 8, 'koridor_id' => 9, 'nama_ruas' => 'Ruas Jalan Mojoagung - Peterongan', 'nomor_ruas' => 'RUAS-17', 'panjang_ruas' => 13.8, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_17.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 18, 'kabupaten_kota_id' => 18, 'koridor_id' => 4, 'nama_ruas' => 'Ruas Jalan Ngadirojo - Sudimoro', 'nomor_ruas' => 'RUAS-18', 'panjang_ruas' => 11.2, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_18.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 19, 'kabupaten_kota_id' => 27, 'koridor_id' => 4, 'nama_ruas' => 'Ruas Jalan Durenan - Panggul', 'nomor_ruas' => 'RUAS-19', 'panjang_ruas' => 38.6, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_19.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 20, 'kabupaten_kota_id' => 1, 'koridor_id' => 8, 'nama_ruas' => 'Ruas Jalan Tanah Merah - Kwanyar', 'nomor_ruas' => 'RUAS-20', 'panjang_ruas' => 12.3, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_20.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 21, 'kabupaten_kota_id' => 23, 'koridor_id' => 8, 'nama_ruas' => 'Ruas Jalan Camplong - Torjun', 'nomor_ruas' => 'RUAS-21', 'panjang_ruas' => 18.2, 'status_jalan' => 'Kabupaten/Kota', 'file_kml_ruas' => 'jalan_geo_21.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
        ], ['id'], ['kabupaten_kota_id', 'koridor_id', 'nama_ruas', 'nomor_ruas', 'panjang_ruas', 'status_jalan', 'file_kml_ruas', 'void', 'updated_at']);

        // 10. Jembatan
        DB::table('jembatan')->upsert([
            ['id' => 1, 'ruas_jalan_id' => 1, 'nama_jembatan' => 'Jembatan Dawung I', 'nomor_jembatan' => '28.001.01.0', 'panjang_jembatan' => 38, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_1.kml', 'void' => 0, 'created_at' => '2026-11-13 02:02:14', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 2, 'ruas_jalan_id' => 2, 'nama_jembatan' => 'raws', 'nomor_jembatan' => 'raws', 'panjang_jembatan' => 4.7, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => '1794535523_doc.kml', 'void' => 1, 'created_at' => '2026-11-12 19:05:23', 'updated_at' => '2026-11-13 05:24:22'],
            ['id' => 3, 'ruas_jalan_id' => 20, 'nama_jembatan' => 'Jembatan Suramadu Sisi Madura', 'nomor_jembatan' => 'JMB-01-SRM', 'panjang_jembatan' => 5438, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_3.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 4, 'ruas_jalan_id' => 8, 'nama_jembatan' => 'Jembatan Kali Porong Baru', 'nomor_jembatan' => 'JMB-02-PRG', 'panjang_jembatan' => 185, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_4.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:58'],
            ['id' => 5, 'ruas_jalan_id' => 16, 'nama_jembatan' => 'Jembatan Kali Brantas Kertosono', 'nomor_jembatan' => 'JMB-03-KTS', 'panjang_jembatan' => 210, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_5.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 6, 'ruas_jalan_id' => 17, 'nama_jembatan' => 'Jembatan Ploso Baru', 'nomor_jembatan' => 'JMB-04-PLS', 'panjang_jembatan' => 258, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_6.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 7, 'ruas_jalan_id' => 12, 'nama_jembatan' => 'Jembatan Gladak Perak Reborn', 'nomor_jembatan' => 'JMB-05-GLP', 'panjang_jembatan' => 140, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_7.kml', 'void' => 0, 'created_at' => '2026-11-12 22:15:59', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 8, 'ruas_jalan_id' => 4, 'nama_jembatan' => 'Jembatan Brawijaya Kediri', 'nomor_jembatan' => 'JMB-06-BWJ', 'panjang_jembatan' => 175, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_8.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 9, 'ruas_jalan_id' => 6, 'nama_jembatan' => 'Jembatan Bengawan Solo Babat', 'nomor_jembatan' => 'JMB-07-BBT', 'panjang_jembatan' => 240, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_9.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 10, 'ruas_jalan_id' => 17, 'nama_jembatan' => 'Jembatan Kali Sadar Mojokerto', 'nomor_jembatan' => 'JMB-08-SDR', 'panjang_jembatan' => 65, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_10.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 11, 'ruas_jalan_id' => 18, 'nama_jembatan' => 'Jembatan Grindulu Pacitan', 'nomor_jembatan' => 'JMB-09-GRD', 'panjang_jembatan' => 320, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_11.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 12, 'ruas_jalan_id' => 13, 'nama_jembatan' => 'Jembatan Kali Sampean Situbondo', 'nomor_jembatan' => 'JMB-10-SMP', 'panjang_jembatan' => 95, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_12.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 13, 'ruas_jalan_id' => 14, 'nama_jembatan' => 'Jembatan Blimbingsari Banyuwangi', 'nomor_jembatan' => 'JMB-11-BLM', 'panjang_jembatan' => 75, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_13.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 14, 'ruas_jalan_id' => 4, 'nama_jembatan' => 'Jembatan Gandong Tulungagung', 'nomor_jembatan' => 'JMB-12-GDG', 'panjang_jembatan' => 52, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_14.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 15, 'ruas_jalan_id' => 7, 'nama_jembatan' => 'Jembatan Sembayat Baru Gresik', 'nomor_jembatan' => 'JMB-13-SBY', 'panjang_jembatan' => 380, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_15.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
            ['id' => 16, 'ruas_jalan_id' => 21, 'nama_jembatan' => 'Jembatan Kali Sampang', 'nomor_jembatan' => 'JMB-14-SPG', 'panjang_jembatan' => 88, 'status_jembatan' => 'Kabupaten/Kota', 'file_kml_jembatan' => 'jembatan_geo_16.kml', 'void' => 0, 'created_at' => '2026-11-12 22:16:00', 'updated_at' => '2026-11-12 22:43:59'],
        ], ['id'], ['ruas_jalan_id', 'nama_jembatan', 'nomor_jembatan', 'panjang_jembatan', 'status_jembatan', 'file_kml_jembatan', 'void', 'updated_at']);
    }
}
