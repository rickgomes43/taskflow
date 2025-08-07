<?php

namespace Tests\Unit;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    public function test_authorize_returns_true(): void
    {
        $request = new LoginRequest();
        
        $this->assertTrue($request->authorize());
    }

    public function test_valid_data_passes_validation(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => true,
        ], $request->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_email_is_required(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'password' => 'password123',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_password_is_required(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('password'));
    }

    public function test_email_must_be_valid_format(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'invalid-email',
            'password' => 'password123',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_email_cannot_exceed_255_characters(): void
    {
        $request = new LoginRequest();
        $longEmail = str_repeat('a', 250) . '@example.com'; // 261 characters
        
        $validator = Validator::make([
            'email' => $longEmail,
            'password' => 'password123',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_password_must_be_at_least_8_characters(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => '1234567', // 7 characters
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('password'));
    }

    public function test_password_cannot_exceed_255_characters(): void
    {
        $request = new LoginRequest();
        $longPassword = str_repeat('a', 256);
        
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => $longPassword,
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('password'));
    }

    public function test_remember_field_is_optional(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => 'password123',
        ], $request->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_remember_field_must_be_boolean(): void
    {
        $request = new LoginRequest();
        $validator = Validator::make([
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => 'not-a-boolean',
        ], $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('remember'));
    }

    public function test_custom_error_messages(): void
    {
        $request = new LoginRequest();
        $messages = $request->messages();

        $this->assertArrayHasKey('email.required', $messages);
        $this->assertArrayHasKey('password.required', $messages);
        $this->assertEquals('O campo email é obrigatório.', $messages['email.required']);
        $this->assertEquals('O campo senha é obrigatório.', $messages['password.required']);
    }

    public function test_custom_attributes(): void
    {
        $request = new LoginRequest();
        $attributes = $request->attributes();

        $this->assertArrayHasKey('email', $attributes);
        $this->assertArrayHasKey('password', $attributes);
        $this->assertEquals('email', $attributes['email']);
        $this->assertEquals('senha', $attributes['password']);
    }
}
