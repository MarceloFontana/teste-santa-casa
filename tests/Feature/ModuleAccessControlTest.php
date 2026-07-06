<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ModuleAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'colaborador']);

        Permission::create(['name' => 'equipamentos']);
        Permission::create(['name' => 'setores-hospitalares']);
    }

    public function test_colaborador_with_permission_can_access_the_module(): void
    {
        $user = User::factory()->create();
        $user->assignRole('colaborador');
        $user->givePermissionTo('equipamentos');

        $response = $this->actingAs($user)->get(route('modules.equipamentos'));

        $response->assertOk();
        $response->assertSee('Equipamentos');
    }

    public function test_colaborador_without_permission_cannot_access_the_module(): void
    {
        $user = User::factory()->create();
        $user->assignRole('colaborador');
        $user->givePermissionTo('setores-hospitalares');

        $response = $this->actingAs($user)->get(route('modules.equipamentos'));

        $response->assertForbidden();
    }

    public function test_admin_without_permission_cannot_access_a_module(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('modules.equipamentos'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('modules.equipamentos'));

        $response->assertRedirect(route('login'));
    }
}
