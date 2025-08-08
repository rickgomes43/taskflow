<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    /**
     * Test user model creation and basic properties.
     */
    public function test_user_model_has_fillable_properties(): void
    {
        $user = new User();
        
        $fillable = $user->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
    }

    /**
     * Test user model has hidden attributes.
     */
    public function test_user_model_hides_sensitive_attributes(): void
    {
        $user = new User();
        
        $hidden = $user->getHidden();
        
        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }

    /**
     * Test user model has email verification timestamp casting.
     */
    public function test_user_model_casts_email_verified_at(): void
    {
        $user = new User();
        
        $casts = $user->getCasts();
        
        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertEquals('datetime', $casts['email_verified_at']);
    }
}