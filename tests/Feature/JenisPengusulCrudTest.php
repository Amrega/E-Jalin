<?php

namespace Tests\Feature;

use App\Models\JenisPengusul;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class JenisPengusulCrudTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithRole(string $roleName): User
    {
        $role = Role::create([
            'nama_role' => $roleName,
            'void'      => 0,
        ]);

        return User::create([
            'name'     => ucfirst($roleName) . ' Test',
            'username' => $roleName . '_test_' . uniqid(),
            'email'    => $roleName . '_' . uniqid() . '@example.com',
            'role_id'  => $role->id,
            'void'     => 0,
            'password' => 'password',
        ]);
    }

    public function test_jenis_pengusul_table_has_expected_columns(): void
    {
        $columns = Schema::getColumnListing('jenis_pengusul');
        sort($columns);

        $this->assertSame(['created_at', 'id', 'nama_jenis_pengusul', 'updated_at'], $columns);
    }

    public function test_administrator_can_perform_crud_on_jenis_pengusul(): void
    {
        $admin = $this->createUserWithRole('administrator');

        // 1. Index
        $this->actingAs($admin)
            ->get(route('jenis-pengusul.index'))
            ->assertOk()
            ->assertSee('Tambah Jenis Pengusul');

        // 2. Store
        $this->actingAs($admin)
            ->post(route('jenis-pengusul.store'), [
                'nama_jenis_pengusul' => 'Pemerintah Daerah',
            ])
            ->assertRedirect(route('jenis-pengusul.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jenis_pengusul', [
            'nama_jenis_pengusul' => 'Pemerintah Daerah',
        ]);

        $item = JenisPengusul::firstOrFail();

        // Duplicate validation
        $this->actingAs($admin)
            ->post(route('jenis-pengusul.store'), [
                'nama_jenis_pengusul' => 'Pemerintah Daerah',
            ])
            ->assertSessionHasErrors('nama_jenis_pengusul');

        // 3. Search on Index
        $this->actingAs($admin)
            ->get(route('jenis-pengusul.index', ['search' => 'Pemerintah']))
            ->assertOk()
            ->assertSee('Pemerintah Daerah');

        // 4. Update
        $this->actingAs($admin)
            ->put(route('jenis-pengusul.update', $item->id), [
                'nama_jenis_pengusul' => 'Pemerintah Kabupaten/Kota',
            ])
            ->assertRedirect(route('jenis-pengusul.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('jenis_pengusul', [
            'id'                  => $item->id,
            'nama_jenis_pengusul' => 'Pemerintah Kabupaten/Kota',
        ]);

        // 5. Destroy
        $this->actingAs($admin)
            ->delete(route('jenis-pengusul.destroy', $item->id))
            ->assertRedirect(route('jenis-pengusul.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('jenis_pengusul', [
            'id' => $item->id,
        ]);
    }

    public function test_non_administrator_cannot_access_jenis_pengusul(): void
    {
        $user = $this->createUserWithRole('user');

        $this->actingAs($user)
            ->get(route('jenis-pengusul.index'))
            ->assertRedirect(route('usulan.index'))
            ->assertSessionHas('error');
    }
}
