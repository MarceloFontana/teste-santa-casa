<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserBlockingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'colaborador']);
    }

    public function test_blocked_user_cannot_authenticate(): void
    {
        $user = User::factory()->create(['usr_blq' => User::USR_BLQ_BLOQUEADO]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_unblocked_user_can_authenticate(): void
    {
        $user = User::factory()->create(['usr_blq' => User::USR_BLQ_DESBLOQUEADO]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_admin_can_block_another_users_account(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $colaborador = User::factory()->create();
        $colaborador->assignRole('colaborador');

        $response = $this->actingAs($admin)->put(route('users.update', $colaborador), [
            'name' => $colaborador->name,
            'email' => $colaborador->email,
            'role' => 'colaborador',
            'usr_blq' => User::USR_BLQ_BLOQUEADO,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertTrue($colaborador->fresh()->isBloqueado());
    }

    public function test_admin_cannot_block_their_own_account(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->put(route('users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'admin',
            'usr_blq' => User::USR_BLQ_BLOQUEADO,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertFalse($admin->fresh()->isBloqueado());
    }
}
