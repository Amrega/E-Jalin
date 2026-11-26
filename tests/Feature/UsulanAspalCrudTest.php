<?php

namespace Tests\Feature;

use App\Models\JenisPengusul;
use App\Models\KabupatenKota;
use App\Models\Pengusul;
use App\Models\Provinsi;
use App\Models\Role;
use App\Models\User;
use App\Models\UsulanAspal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UsulanAspalCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithRole(string $roleName, ?string $name = null, ?int $kabKotaId = null): User
    {
        $role = Role::firstOrCreate(
            ['nama_role' => $roleName],
            ['void' => 0]
        );

        return User::create([
            'name'              => $name ?? ucfirst($roleName) . ' Test',
            'username'          => $roleName . '_test_' . uniqid(),
            'email'             => $roleName . '_' . uniqid() . '@example.com',
            'role_id'           => $role->id,
            'kabupaten_kota_id' => $kabKotaId,
            'void'              => 0,
            'password'          => 'password',
        ]);
    }

    public function test_usulan_aspal_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing('usulan_aspal');
        sort($columns);

        $this->assertSame([
            'created_at',
            'id',
            'jenis_pengusul_id',
            'kabupaten_kota',
            'keterangan_urgensi',
            'pengusul_id',
            'qty_drum',
            'updated_at',
            'upload_surat_permohonan',
        ], $columns);
    }

    public function test_administrator_can_perform_crud_and_upload_on_usulan_aspal(): void
    {
        $prov = Provinsi::create(['nama_provinsi' => 'Jawa Timur', 'void' => 0]);
        KabupatenKota::create([
            'provinsi_id'         => $prov->id,
            'nama_kabupaten_kota' => 'Kabupaten Malang',
            'void'                => 0,
        ]);
        KabupatenKota::create([
            'provinsi_id'         => $prov->id,
            'nama_kabupaten_kota' => 'Kota Surabaya',
            'void'                => 0,
        ]);

        $admin = $this->createUserWithRole('administrator', 'Admin E-Jalin');
        $userPemda = $this->createUserWithRole('user', 'Pemda Malang');

        $jenisInstansi = JenisPengusul::create(['nama_jenis_pengusul' => 'Instansi']);
        $jenisLembaga = JenisPengusul::create(['nama_jenis_pengusul' => 'Lembaga']);

        $pengusulA = Pengusul::create([
            'nama_pengusul'     => 'Dinas PU Kab. Malang',
            'jenis_pengusul_id' => $jenisInstansi->id,
            'user_id'           => $userPemda->id,
        ]);
        $pengusulB = Pengusul::create([
            'nama_pengusul'     => 'DPRD Kota Surabaya',
            'jenis_pengusul_id' => $jenisLembaga->id,
            'user_id'           => $userPemda->id,
        ]);

        // 1. Index
        $this->actingAs($admin)
            ->get(route('usulan-aspal.index'))
            ->assertOk()
            ->assertSee('Tambah Usulan Aspal')
            ->assertSee('Kabupaten Malang');

        // 2a. Non-PDF upload must be rejected
        $fakeImage = UploadedFile::fake()->create('surat.jpg', 100, 'image/jpeg');

        $this->actingAs($admin)
            ->post(route('usulan-aspal.store'), [
                'kabupaten_kota'          => 'Kabupaten Malang',
                'qty_drum'                => 25,
                'keterangan_urgensi'      => 'Tes file bukan PDF.',
                'pengusul_id'             => $pengusulA->id,
                'jenis_pengusul_id'       => $jenisInstansi->id,
                'upload_surat_permohonan' => $fakeImage,
            ])
            ->assertSessionHasErrors('upload_surat_permohonan');

        // 2b. Store with PDF file upload
        $fakePdf = UploadedFile::fake()->create('surat-permohonan-malang.pdf', 120, 'application/pdf');

        $this->actingAs($admin)
            ->post(route('usulan-aspal.store'), [
                'kabupaten_kota'          => 'Kabupaten Malang',
                'qty_drum'                => 50,
                'keterangan_urgensi'      => 'Jalan berlubang parah sepanjang 3 KM membutuhkan aspal segera.',
                'pengusul_id'             => $pengusulA->id,
                'jenis_pengusul_id'       => $jenisInstansi->id,
                'upload_surat_permohonan' => $fakePdf,
            ])
            ->assertRedirect(route('usulan-aspal.index'))
            ->assertSessionHas('success');

        $item = UsulanAspal::with(['pengusul', 'jenisPengusul'])->firstOrFail();
        $this->assertSame('Kabupaten Malang', $item->kabupaten_kota);
        $this->assertSame(50, $item->qty_drum);
        $this->assertSame('Dinas PU Kab. Malang', $item->pengusul->nama_pengusul);
        $this->assertSame('Instansi', $item->jenisPengusul->nama_jenis_pengusul);
        $this->assertNotNull($item->upload_surat_permohonan);

        $firstFilePath = public_path('uploads/dokumen/usulan-aspal/' . $item->upload_surat_permohonan);
        $this->assertTrue(File::exists($firstFilePath));

        // Download surat permohonan
        $this->actingAs($admin)
            ->get(route('usulan-aspal.download-surat', $item->id))
            ->assertOk();

        // Proteksi hapus Pengusul yang masih digunakan di Usulan Aspal
        $this->actingAs($admin)
            ->delete(route('pengusul.destroy', $pengusulA->id))
            ->assertRedirect(route('pengusul.index'))
            ->assertSessionHas('error');

        // 3. Filter & Search
        $this->actingAs($admin)
            ->get(route('usulan-aspal.index', [
                'kabupaten_kota'    => 'Kabupaten Malang',
                'jenis_pengusul_id' => $jenisInstansi->id,
                'search'            => 'berlubang',
            ]))
            ->assertOk()
            ->assertSee('Dinas PU Kab. Malang');

        // 4. Update with replacement file
        $replacementPdf = UploadedFile::fake()->create('surat-revisi-surabaya.pdf', 150, 'application/pdf');

        $this->actingAs($admin)
            ->put(route('usulan-aspal.update', $item->id), [
                'kabupaten_kota'          => 'Kota Surabaya',
                'qty_drum'                => 120,
                'keterangan_urgensi'      => 'Perbaikan darurat akses utama pelabuhan.',
                'pengusul_id'             => $pengusulB->id,
                'jenis_pengusul_id'       => $jenisLembaga->id,
                'upload_surat_permohonan' => $replacementPdf,
            ])
            ->assertRedirect(route('usulan-aspal.index'))
            ->assertSessionHas('success');

        $item->refresh();
        $this->assertSame('Kota Surabaya', $item->kabupaten_kota);
        $this->assertSame(120, $item->qty_drum);
        $this->assertSame($pengusulB->id, $item->pengusul_id);
        $this->assertSame($jenisLembaga->id, $item->jenis_pengusul_id);
        $this->assertFalse(File::exists($firstFilePath));

        $secondFilePath = public_path('uploads/dokumen/usulan-aspal/' . $item->upload_surat_permohonan);
        $this->assertTrue(File::exists($secondFilePath));

        // 5. Destroy (also cleans up uploaded file)
        $this->actingAs($admin)
            ->delete(route('usulan-aspal.destroy', $item->id))
            ->assertRedirect(route('usulan-aspal.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('usulan_aspal', ['id' => $item->id]);
        $this->assertFalse(File::exists($secondFilePath));
    }

    public function test_pemda_user_only_sees_linked_pengusul_and_jenis_pengusul_while_admin_sees_all(): void
    {
        $prov = Provinsi::create(['nama_provinsi' => 'Jawa Timur', 'void' => 0]);
        $kabMalang = KabupatenKota::create([
            'provinsi_id'         => $prov->id,
            'nama_kabupaten_kota' => 'Kabupaten Malang',
            'void'                => 0,
        ]);
        $kotaSurabaya = KabupatenKota::create([
            'provinsi_id'         => $prov->id,
            'nama_kabupaten_kota' => 'Kota Surabaya',
            'void'                => 0,
        ]);

        $admin = $this->createUserWithRole('administrator', 'Admin E-Jalin');
        $pemdaMalang = $this->createUserWithRole('user', 'Pemda Kabupaten Malang', $kabMalang->id);
        $pemdaSurabaya = $this->createUserWithRole('user', 'Pemda Kota Surabaya', $kotaSurabaya->id);

        $jenisInstansi = JenisPengusul::create(['nama_jenis_pengusul' => 'Instansi']);
        $jenisLembaga = JenisPengusul::create(['nama_jenis_pengusul' => 'Lembaga']);

        $bupatiMalang = Pengusul::create([
            'nama_pengusul'     => 'Bupati Malang',
            'jenis_pengusul_id' => $jenisInstansi->id,
            'user_id'           => $pemdaMalang->id,
        ]);
        $dprdSurabaya = Pengusul::create([
            'nama_pengusul'     => 'DPRD Kota Surabaya',
            'jenis_pengusul_id' => $jenisLembaga->id,
            'user_id'           => $pemdaSurabaya->id,
        ]);

        // Pemda Malang hanya melihat Bupati Malang & Instansi di dropdown (tidak ada pilihan lain maupun placeholder kosong)
        $response = $this->actingAs($pemdaMalang)
            ->get(route('usulan-aspal.index'))
            ->assertOk()
            ->assertSee('Bupati Malang')
            ->assertSee('Instansi')
            ->assertDontSee('DPRD Kota Surabaya')
            ->assertDontSee('Lembaga')
            ->assertDontSee('-- Pilih Pengusul --')
            ->assertDontSee('-- Pilih Jenis Pengusul --');

        // Role non-admin lain (mis. Bappeda) tanpa pengusul tertaut juga tidak melihat semua opsi dropdown
        $bappeda = $this->createUserWithRole('bappeda', 'Petugas Bappeda');
        $this->actingAs($bappeda)
            ->get(route('usulan-aspal.index'))
            ->assertOk()
            ->assertDontSee('DPRD Kota Surabaya')
            ->assertDontSee('Lembaga');

        // Admin melihat seluruh Pengusul & Jenis Pengusul
        $this->actingAs($admin)
            ->get(route('usulan-aspal.index'))
            ->assertOk()
            ->assertSee('Bupati Malang')
            ->assertSee('DPRD Kota Surabaya')
            ->assertSee('Instansi')
            ->assertSee('Lembaga');

        // Pemda Malang tidak bisa submit dengan pengusul milik akun lain
        $this->actingAs($pemdaMalang)
            ->post(route('usulan-aspal.store'), [
                'kabupaten_kota'     => 'Kabupaten Malang',
                'qty_drum'           => 30,
                'keterangan_urgensi' => 'Coba pakai pengusul lain',
                'pengusul_id'        => $dprdSurabaya->id,
                'jenis_pengusul_id'  => $jenisLembaga->id,
            ])
            ->assertSessionHasErrors('pengusul_id');

        // Pemda Malang submit dengan pengusul miliknya: jenis_pengusul_id otomatis mengikuti pengusul (Instansi)
        $this->actingAs($pemdaMalang)
            ->post(route('usulan-aspal.store'), [
                'kabupaten_kota'     => 'Kabupaten Malang',
                'qty_drum'           => 40,
                'keterangan_urgensi' => 'Kebutuhan aspal ruas kabupaten Malang.',
                'pengusul_id'        => $bupatiMalang->id,
                'jenis_pengusul_id'  => $jenisLembaga->id, // bahkan jika dicoba diubah, server menyelaraskan ke Instansi
            ])
            ->assertRedirect(route('usulan-aspal.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('usulan_aspal', [
            'kabupaten_kota'    => 'Kabupaten Malang',
            'qty_drum'          => 40,
            'pengusul_id'       => $bupatiMalang->id,
            'jenis_pengusul_id' => $jenisInstansi->id,
        ]);
    }

    public function test_verifier_role_can_view_but_cannot_create_usulan_aspal(): void
    {
        $bappeda = $this->createUserWithRole('bappeda', 'Petugas Bappeda');

        $this->actingAs($bappeda)
            ->get(route('usulan-aspal.index'))
            ->assertOk()
            ->assertDontSee('Tambah Usulan Aspal');

        $this->actingAs($bappeda)
            ->post(route('usulan-aspal.store'), [
                'kabupaten_kota'     => 'Kabupaten Malang',
                'keterangan_urgensi' => 'Test',
                'pengusul_id'        => 1,
                'jenis_pengusul_id'  => 1,
            ])
            ->assertRedirect(route('usulan-aspal.index'))
            ->assertSessionHas('error');
    }
}
