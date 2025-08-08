<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSeeText('Lista de usuários (Admin apenas)');
    }

    public function test_collaborator_cannot_access_admin_routes(): void
    {
        $collaborator = User::factory()->create(['role' => 'colaborador']);

        $response = $this->actingAs($collaborator)->get('/admin/users');

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_client_cannot_access_admin_routes(): void
    {
        $client = User::factory()->create(['role' => 'cliente']);

        $response = $this->actingAs($client)->get('/admin/users');

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_admin_can_access_collaborator_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/colaborador/projetos');

        $response->assertStatus(200);
        $response->assertSeeText('Projetos (Admin e Colaborador)');
    }

    public function test_collaborator_can_access_collaborator_routes(): void
    {
        $collaborator = User::factory()->create(['role' => 'colaborador']);

        $response = $this->actingAs($collaborator)->get('/colaborador/projetos');

        $response->assertStatus(200);
        $response->assertSeeText('Projetos (Admin e Colaborador)');
    }

    public function test_client_cannot_access_collaborator_routes(): void
    {
        $client = User::factory()->create(['role' => 'cliente']);

        $response = $this->actingAs($client)->get('/colaborador/projetos');

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_client_can_access_client_routes(): void
    {
        $client = User::factory()->create(['role' => 'cliente']);

        $response = $this->actingAs($client)->get('/cliente/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText('Dashboard do Cliente');
    }

    public function test_admin_cannot_access_client_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/cliente/dashboard');

        $response->assertRedirect('/admin/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_all_authenticated_users_can_access_general_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $collaborator = User::factory()->create(['role' => 'colaborador']);
        $client = User::factory()->create(['role' => 'cliente']);

        $this->actingAs($admin)->get('/dashboard')->assertStatus(200);
        $this->actingAs($collaborator)->get('/dashboard')->assertStatus(200);
        $this->actingAs($client)->get('/dashboard')->assertStatus(200);
    }
}