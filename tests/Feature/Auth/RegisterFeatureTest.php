<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');
        $response->assertOk();
        $response->assertViewIs('auth.register');
    }

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success');

        // Check user was created in database
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('colaborador', $user->role);
        $this->assertTrue(Hash::check('ValidPass123', $user->password));
    }

    public function test_registration_fails_with_duplicate_email()
    {
        // Create existing user
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_fails_with_weak_password()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'weakpass', // Missing uppercase and number
            'password_confirmation' => 'weakpass',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_registration_fails_with_password_mismatch()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'DifferentPass123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_registration_fails_with_missing_required_fields()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'password_confirmation']);
        $this->assertGuest();
    }

    public function test_registration_fails_with_invalid_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_cannot_access_register()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/register');
        $response->assertRedirect('/dashboard');
    }

    public function test_authenticated_user_cannot_submit_registration()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_registration_rate_limiting()
    {
        // Make 5 successful registration attempts (rate limit is 5 per minute)
        for ($i = 0; $i < 5; $i++) {
            $this->post('/register', [
                'name' => "Test User {$i}",
                'email' => "test{$i}@example.com",
                'password' => 'ValidPass123',
                'password_confirmation' => 'ValidPass123',
            ]);
            
            // Logout after each registration since they auto-login
            auth()->logout();
        }

        // 6th attempt should be rate limited
        $response = $this->post('/register', [
            'name' => 'Test User Blocked',
            'email' => 'blocked@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123',
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    public function test_password_is_properly_hashed()
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        
        $this->assertNotEquals('ValidPass123', $user->password);
        $this->assertTrue(Hash::check('ValidPass123', $user->password));
    }
}