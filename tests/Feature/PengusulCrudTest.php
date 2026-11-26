<?php

namespace Tests\Feature;

use App\Models\JenisPengusul;
use App\Models\Pengusul;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PengusulCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithRole(string $roleName, ?string $name = null): User
    {
        $role = Role::firstOrCreate(
            ['nama_role' => $roleName],
            ['void' => 0]
        );

        return User::create([
            'name'     => $name ?? ucfirst($roleName) . ' Test',
            'username' => $roleName . '_test_' . uniqid(),
            'email'    => $roleName . '_' . uniqid() . '@example.com',
            'role_id'  => $role->id,
            'void'     => 0,
            'password' => 'password',
        ]);
    }

    public function test_pengusul_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing('pengusul');
        sort($columns);

        $this->assertSame(
            ['created_at', 'id', 'jenis_pengusul_id', 'nama_pengusul', 'updated_at', 'user_id'],
            $columns
        );
    }

    public function test_administrator_can_perform_crud_on_pengusul(): void
    {
        $admin = $this->createUserWithRole('administrator', 'Admin E-Jalin');
        $userA = $this->createUserWithRole('user', 'Pemda Kabupaten Malang');
        $userB = $this->createUserWithRole('user', 'Pemda Kota Surabaya');

        $jenisInstansi = JenisPengusul::create(['nama_jenis_pengusul' => 'Instansi']);
        $jenisLembaga = JenisPengusul::create(['nama_jenis_pengusul' => 'Lembaga']);

        // 1. Index
        $this->actingAs($admin)
            ->get(route('pengusul.index'))
            ->assertOk()
            ->assertSee('Tambah Pengusul')
            ->assertSee('Instansi')
            ->assertSee('Pemda Kabupaten Malang');

        // 2. Store
        $this->actingAs($admin)
            ->post(route('pengusul.store'), [
                'nama_pengusul'     => 'Bupati Malang',
                'jenis_pengusul_id' => $jenisInstansi->id,
                'user_id'           => $userA->id,
            ])
            ->assertRedirect(route('pengusul.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pengusul', [
            'nama_pengusul'     => 'Bupati Malang',
            'jenis_pengusul_id' => $jenisInstansi->id,
            'user_id'           => $userA->id,
        ]);

        $item = Pengusul::with(['jenisPengusul', 'user'])->firstOrFail();
        $this->assertSame('Instansi', $item->jenisPengusul->nama_jenis_pengusul);
        $this->assertSame('Pemda Kabupaten Malang', $item->user->name);

        // Proteksi hapus Jenis Pengusul & User yang sedang terhubung
        $this->actingAs($admin)
            ->delete(route('jenis-pengusul.destroy', $jenisInstansi->id))
            ->assertRedirect(route('jenis-pengusul.index'))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->delete(route('users.destroy', $userA->id))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        // Verifikasi route /users & redirect /user
        $this->actingAs($admin)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('Tambah User');

        $this->actingAs($admin)
            ->get('/user')
            ->assertRedirect('/users');

        // 3. Filter & Search
        $this->actingAs($admin)
            ->get(route('pengusul.index', [
                'jenis_pengusul_id' => $jenisInstansi->id,
                'search'            => 'Malang',
            ]))
            ->assertOk()
            ->assertSee('Bupati Malang');

        // 4. Update
        $this->actingAs($admin)
            ->put(route('pengusul.update', $item->id), [
                'nama_pengusul'     => 'DPRD Kota Surabaya',
                'jenis_pengusul_id' => $jenisLembaga->id,
                'user_id'           => $userB->id,
            ])
            ->assertRedirect(route('pengusul.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pengusul', [
            'id'                => $item->id,
            'nama_pengusul'     => 'DPRD Kota Surabaya',
            'jenis_pengusul_id' => $jenisLembaga->id,
            'user_id'           => $userB->id,
        ]);

        // 5. Destroy
        $this->actingAs($admin)
            ->delete(route('pengusul.destroy', $item->id))
            ->assertRedirect(route('pengusul.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('pengusul', [
            'id' => $item->id,
        ]);
    }

    public function test_non_administrator_cannot_access_pengusul(): void
    {
        $user = $this->createUserWithRole('user');

        $this->actingAs($user)
            ->get(route('pengusul.index'))
            ->assertRedirect(route('usulan.index'))
            ->assertSessionHas('error');
    }
}
