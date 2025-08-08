<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_admin(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isCollaborator());
        $this->assertFalse($user->isClient());
    }

    public function test_user_can_be_collaborator(): void
    {
        $user = User::factory()->create(['role' => 'colaborador']);

        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isCollaborator());
        $this->assertFalse($user->isClient());
    }

    public function test_user_can_be_client(): void
    {
        $user = User::factory()->create(['role' => 'cliente']);

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isCollaborator());
        $this->assertTrue($user->isClient());
    }

    public function test_user_has_default_collaborator_role(): void
    {
        $user = User::factory()->create(['role' => 'colaborador']);

        $this->assertEquals('colaborador', $user->role);
        $this->assertTrue($user->isCollaborator());
    }

    public function test_admin_scope_returns_only_admins(): void
    {
        User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        User::factory()->create(['role' => 'colaborador', 'name' => 'Collaborator User']);
        User::factory()->create(['role' => 'cliente', 'name' => 'Client User']);

        $admins = User::admins()->get();

        $this->assertEquals(1, $admins->count());
        $this->assertTrue($admins->first()->isAdmin());
    }

    public function test_collaborators_scope_returns_only_collaborators(): void
    {
        User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        User::factory()->create(['role' => 'colaborador', 'name' => 'Collaborator User']);
        User::factory()->create(['role' => 'cliente', 'name' => 'Client User']);

        $collaborators = User::collaborators()->get();

        $this->assertEquals(1, $collaborators->count());
        $this->assertTrue($collaborators->first()->isCollaborator());
    }

    public function test_clients_scope_returns_only_clients(): void
    {
        User::factory()->create(['role' => 'admin', 'name' => 'Admin User']);
        User::factory()->create(['role' => 'colaborador', 'name' => 'Collaborator User']);
        User::factory()->create(['role' => 'cliente', 'name' => 'Client User']);

        $clients = User::clients()->get();

        $this->assertEquals(1, $clients->count());
        $this->assertTrue($clients->first()->isClient());
    }

    public function test_role_is_fillable(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->assertEquals('admin', $user->role);
        $this->assertTrue($user->isAdmin());
    }
}