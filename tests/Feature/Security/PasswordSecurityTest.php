<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class PasswordSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_bcrypt_hash_cost_is_appropriate()
    {
        $cost = Config::get('hashing.bcrypt.rounds');
        
        // In production, should be at least 12, but testing may use lower values
        if (app()->environment('production')) {
            $this->assertGreaterThanOrEqual(12, $cost, 'Bcrypt cost should be at least 12 in production');
        } else {
            $this->assertGreaterThanOrEqual(4, $cost, 'Bcrypt cost should be at least 4');
        }
    }

    public function test_password_hashing_uses_bcrypt()
    {
        $driver = Config::get('hashing.driver');
        $this->assertEquals('bcrypt', $driver, 'Password hashing should use bcrypt driver');
    }

    public function test_password_hash_is_unique_with_salt()
    {
        $password = 'TestPassword123';
        
        $hash1 = Hash::make($password);
        $hash2 = Hash::make($password);
        
        // Same password should produce different hashes due to salt
        $this->assertNotEquals($hash1, $hash2, 'Password hashes should be unique due to salt');
        
        // Both should verify correctly
        $this->assertTrue(Hash::check($password, $hash1));
        $this->assertTrue(Hash::check($password, $hash2));
    }

    public function test_password_hash_verification()
    {
        $password = 'SecurePassword123';
        $hash = Hash::make($password);
        
        // Correct password should verify
        $this->assertTrue(Hash::check($password, $hash));
        
        // Incorrect password should not verify
        $this->assertFalse(Hash::check('WrongPassword123', $hash));
        $this->assertFalse(Hash::check('', $hash));
        $this->assertFalse(Hash::check($password . 'extra', $hash));
    }

    public function test_password_hash_format()
    {
        $password = 'TestPassword123';
        $hash = Hash::make($password);
        
        // Should start with $2y$ or $2b$ for bcrypt
        $this->assertMatchesRegularExpression('/^\$2[yb]\$/', $hash, 'Hash should be bcrypt format');
        
        // Should have proper bcrypt structure
        $parts = explode('$', $hash);
        $this->assertGreaterThanOrEqual(4, count($parts), 'Bcrypt hash should have at least 4 parts');
        
        // Cost should be reasonable (at least 4, preferably 12+ in production)
        $cost = (int)$parts[2];
        $this->assertGreaterThanOrEqual(4, $cost, 'Bcrypt cost in hash should be at least 4');
    }

    public function test_password_hash_performance()
    {
        $password = 'PerformanceTestPassword123';
        
        $startTime = microtime(true);
        $hash = Hash::make($password);
        $hashTime = microtime(true) - $startTime;
        
        $startTime = microtime(true);
        Hash::check($password, $hash);
        $checkTime = microtime(true) - $startTime;
        
        // Hashing should complete in reasonable time
        $this->assertLessThan(2.0, $hashTime, 'Password hashing should complete within 2 seconds');
        $this->assertGreaterThan(0, $hashTime, 'Password hashing should take some time');
        
        $this->assertLessThan(2.0, $checkTime, 'Password verification should complete within 2 seconds');
        $this->assertGreaterThan(0, $checkTime, 'Password verification should take some time');
    }

    public function test_stored_passwords_are_hashed()
    {
        $user = User::factory()->create([
            'email' => 'security-test@example.com',
            'password' => Hash::make('TestPassword123'),
        ]);

        $storedPassword = $user->fresh()->password;
        
        // Password should be hashed (not plaintext)
        $this->assertNotEquals('TestPassword123', $storedPassword);
        $this->assertMatchesRegularExpression('/^\$2[yb]\$/', $storedPassword);
        $this->assertTrue(Hash::check('TestPassword123', $storedPassword));
    }

    public function test_user_model_password_casting()
    {
        // Verify password is cast to 'hashed' in User model
        $user = new User();
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('password', $casts);
        $this->assertEquals('hashed', $casts['password']);
    }

    public function test_password_rehash_on_login_enabled()
    {
        $rehashOnLogin = Config::get('hashing.rehash_on_login');
        $this->assertTrue($rehashOnLogin, 'Password rehash on login should be enabled for security');
    }

    public function test_hash_check_with_invalid_hash()
    {
        // Test with various invalid hashes
        $invalidHashes = [
            'plaintext_password',
            '',
            'not_a_hash_at_all',
        ];

        foreach ($invalidHashes as $invalidHash) {
            try {
                $result = Hash::check('password', $invalidHash);
                $this->assertFalse($result, "Hash check should return false for invalid hash: " . var_export($invalidHash, true));
            } catch (\RuntimeException $e) {
                // Some invalid hashes throw exceptions, which is also acceptable security behavior
                $this->assertStringContainsString('algorithm', $e->getMessage());
            }
        }
    }

    public function test_mass_password_hashing_consistency()
    {
        $passwords = [
            'Password123',
            'AnotherSecure456',
            'ComplexP@ssw0rd!',
            'SimplePass789',
        ];

        foreach ($passwords as $password) {
            $hash = Hash::make($password);
            
            // Each hash should be valid bcrypt format
            $this->assertMatchesRegularExpression('/^\$2[yb]\$/', $hash);
            
            // Should verify correctly
            $this->assertTrue(Hash::check($password, $hash));
            
            // Should not verify with wrong password
            $this->assertFalse(Hash::check($password . 'wrong', $hash));
        }
    }

    public function test_password_security_audit_command_exists()
    {
        $commands = \Artisan::all();
        $this->assertArrayHasKey('security:audit-passwords', $commands);
    }
}