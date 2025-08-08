<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Http\Requests\UserRegistrationRequest;
use Illuminate\Support\Facades\Validator;

class UserRegistrationRequestTest extends TestCase
{
    public function test_registration_request_validates_required_fields()
    {
        $request = new UserRegistrationRequest();
        $rules = $request->rules();

        $validator = Validator::make([], $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertArrayHasKey('password_confirmation', $validator->errors()->toArray());
    }

    public function test_registration_request_validates_email_format()
    {
        $request = new UserRegistrationRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123'
        ];

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_registration_request_validates_password_strength()
    {
        $request = new UserRegistrationRequest();
        $rules = $request->rules();

        // Test weak password (no uppercase)
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'weakpass123',
            'password_confirmation' => 'weakpass123'
        ];

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_registration_request_validates_password_confirmation()
    {
        $request = new UserRegistrationRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'DifferentPass123'
        ];

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function test_registration_request_validates_name_length()
    {
        $request = new UserRegistrationRequest();
        $rules = $request->rules();

        // Test name too short
        $data = [
            'name' => 'A',
            'email' => 'test@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123'
        ];

        $validator = Validator::make($data, $rules);
        
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    public function test_registration_request_passes_with_valid_data()
    {
        $request = new UserRegistrationRequest();
        $rules = $request->rules();

        $data = [
            'name' => 'Valid User Name',
            'email' => 'valid@example.com',
            'password' => 'ValidPass123',
            'password_confirmation' => 'ValidPass123'
        ];

        $validator = Validator::make($data, $rules);
        
        $this->assertTrue($validator->passes());
    }

    public function test_authorization_returns_true()
    {
        $request = new UserRegistrationRequest();
        $this->assertTrue($request->authorize());
    }
}