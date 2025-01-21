<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\LoginRequest;

class LoginRequestTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_validation_rules_pass_with_valid_data()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_rules_fail_with_missing_email()
    {
        $data = [
            'password' => 'password123',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());
        $this->assertEquals('Email is required', $validator->errors()->first('email'));
    }

    public function test_validation_rules_fail_with_invalid_email()
    {
        $data = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());
        $this->assertEquals('Invalid email format', $validator->errors()->first('email'));
    }

    public function test_validation_rules_fail_with_short_email()
    {
        $data = [
            'email' => 'a@b.c',
            'password' => 'password123',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());
        $this->assertEquals('Email must be at least 6 characters', $validator->errors()->first('email'));
    }

    public function test_validation_rules_fail_with_short_password()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'short',
        ];

        $request = new LoginRequest();
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->passes());
        $this->assertEquals('Password must be at least 6 characters', $validator->errors()->first('password'));
    }
}
