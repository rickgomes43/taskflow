<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LogoutFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_logout()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user);

        // Perform logout
        $response = $this->post('/logout');

        // Assert redirect to home page
        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Logout realizado com sucesso!');

        // Assert user is no longer authenticated
        $this->assertGuest();
    }

    public function test_guest_cannot_access_logout_route()
    {
        $response = $this->post('/logout');

        // Should redirect to login page due to auth middleware
        $response->assertRedirect('/login');
    }

    public function test_logout_invalidates_session()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Get initial session ID
        $initialSessionId = session()->getId();

        // Logout
        $this->post('/logout');

        // Start a new request to check session
        $this->get('/login');
        $newSessionId = session()->getId();

        // Session ID should be different after logout
        $this->assertNotEquals($initialSessionId, $newSessionId);
    }

    public function test_logout_shows_success_message_on_home_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Logout and follow redirect to home page
        $response = $this->followingRedirects()->post('/logout');

        // Check that home page shows success message
        $response->assertSee('Logout realizado com sucesso!');
    }

    public function test_user_cannot_access_protected_routes_after_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Confirm user can access dashboard
        $this->get('/dashboard')->assertOk();

        // Logout
        $this->post('/logout');

        // Try to access dashboard after logout
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }
}