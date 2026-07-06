<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $colaborador;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'colaborador']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->colaborador = User::factory()->create();
        $this->colaborador->assignRole('colaborador');
    }

    public function test_admin_can_view_the_permissions_list(): void
    {
        Permission::create(['name' => 'equipamentos']);

        $response = $this->actingAs($this->admin)->get(route('permissions.index'));

        $response->assertOk();
        $response->assertSee('equipamentos');
    }

    public function test_admin_can_create_a_permission(): void
    {
        $response = $this->actingAs($this->admin)->post(route('permissions.store'), [
            'name' => 'nova-permissao',
        ]);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', ['name' => 'nova-permissao']);
    }

    public function test_permission_name_must_be_unique(): void
    {
        Permission::create(['name' => 'equipamentos']);

        $response = $this->actingAs($this->admin)->post(route('permissions.store'), [
            'name' => 'equipamentos',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_a_permission(): void
    {
        $permission = Permission::create(['name' => 'antiga-chave']);

        $response = $this->actingAs($this->admin)->put(route('permissions.update', $permission), [
            'name' => 'nova-chave',
        ]);

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseHas('permissions', ['id' => $permission->id, 'name' => 'nova-chave']);
    }

    public function test_admin_can_delete_a_permission(): void
    {
        $permission = Permission::create(['name' => 'equipamentos']);

        $response = $this->actingAs($this->admin)->delete(route('permissions.destroy', $permission));

        $response->assertRedirect(route('permissions.index'));
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    public function test_colaborador_cannot_access_permission_management(): void
    {
        $response = $this->actingAs($this->colaborador)->get(route('permissions.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('permissions.index'));

        $response->assertRedirect(route('login'));
    }
}
