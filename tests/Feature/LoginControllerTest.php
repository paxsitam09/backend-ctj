<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase; // Ensures database resets after each test

    /** @test */
    public function test_user_can_login_successfully()
    {
        // Create a test user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Send login request
        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => 'password123',
        ]);

        // Assert response structure and status
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'token',
                ],
            ])
            ->assertJson([
                'status' => 200,
                'message' => 'Login successful',
                'user' => [
                    'email' => 'test@example.com',
                ],
            ]);
    }

    /** @test */
    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 401,
                'message' => 'Invalid email or password.',
            ]);
    }

    /** @test */
    public function test_login_fails_with_non_existing_email()
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'doesnotexist@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 404,
                'message' => 'User not found.',
            ]);
    }

    /** @test */
    public function test_login_fails_with_missing_fields()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422) // Laravel returns 422 for validation errors
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
