<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'colaborador']);
        Permission::create(['name' => 'equipamentos']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_creating_an_admin_user_ignores_any_selected_permissions(): void
    {
        $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Novo Admin',
            'email' => 'novo.admin@santacasa.org.br',
            'password' => 'password',
            'role' => 'admin',
            'permissions' => ['equipamentos'],
            'usr_blq' => User::USR_BLQ_DESBLOQUEADO,
        ]);

        $user = User::where('email', 'novo.admin@santacasa.org.br')->firstOrFail();

        $this->assertTrue($user->hasRole('admin'));
        $this->assertCount(0, $user->getDirectPermissions());
    }

    public function test_changing_a_user_role_to_admin_removes_existing_permissions(): void
    {
        $colaborador = User::factory()->create();
        $colaborador->assignRole('colaborador');
        $colaborador->givePermissionTo('equipamentos');

        $this->actingAs($this->admin)->put(route('users.update', $colaborador), [
            'name' => $colaborador->name,
            'email' => $colaborador->email,
            'role' => 'admin',
            'permissions' => ['equipamentos'],
            'usr_blq' => User::USR_BLQ_DESBLOQUEADO,
        ]);

        $colaborador->refresh();

        $this->assertTrue($colaborador->hasRole('admin'));
        $this->assertCount(0, $colaborador->getDirectPermissions());
    }
}
