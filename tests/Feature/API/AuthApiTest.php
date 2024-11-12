<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

describe('Auth', function () {

    describe('POST api/register', function () {
        it('registers a user with valid data', function () {
            $response = $this->postJson('/api/register', [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $response->assertStatus(201)
                ->assertJsonStructure(['message', 'user', 'token']);
        });

        it('fails to register a user with invalid data', function () {
            $response = $this->postJson('/api/register', [
                'name' => '',
                'email' => 'not-an-email',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
        });
    });

    describe('POST api/login', function () {
        it('logs in a user with correct credentials', function () {
            User::factory()->create([
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
            ]);

            $response = $this->postJson('/api/login', [
                'email' => 'john@example.com',
                'password' => 'password123',
            ]);

            $response->assertStatus(200)
                ->assertJsonStructure(['message', 'user', 'access_token']);
        });

        it('fails to log in with incorrect credentials', function () {
            User::factory()->create([
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
            ]);

            $response = $this->postJson('/api/login', [
                'email' => 'john@example.com',
                'password' => 'wrongpassword',
            ]);

            $response->assertStatus(401)
                ->assertJson(['message' => 'Invalid credentials']);
        });
    });

    describe('POST /api/logout', function () {
        it('logs out an authenticated user', function () {
            $user = User::factory()->create();
            $token = $user->createToken('test')->plainTextToken;

            $response = $this->withToken($token)->postJson('/api/logout');

            $response->assertStatus(200)
                ->assertJson(['message' => 'User logged out successfully']);
        });
    });

    describe('POST /api/forgot-password', function () {
        it('sends a password reset link to a valid user email', function () {
            User::factory()->create(['email' => 'john@example.com']);

            $response = $this->postJson('/api/forgot-password', ['email' => 'john@example.com']);

            $response->assertStatus(200)
                ->assertJson(['message' => __('passwords.sent')]);
        });

        it('fails to send a reset link to a non-existent email', function () {
            $response = $this->postJson('/api/forgot-password', ['email' => 'nonexistent@example.com']);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
        });
    });

    describe('POST /api/reset-password', function () {
        it('resets password with a valid token and email', function () {
            $user = User::factory()->create([
                'email' => 'john@doe.com',
                'password' => Hash::make('oldpassword123'),
            ]);

            // Insert the reset token manually into the database
            $token = Str::random(60);
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]);

            $response = $this->postJson('/api/reset-password', [
                'email' => 'john@doe.com',
                'token' => $token,
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

            $response->assertStatus(200)
                ->assertJson(['message' => __('passwords.reset')]);

            $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
        });

        it('fails to reset password with an invalid token', function () {
            User::factory()->create([
                'email' => 'john@example.com',
            ]);

            $response = $this->postJson('/api/reset-password', [
                'email' => 'john@example.com',
                'token' => 'invalid-token',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

            $response->assertStatus(500)
                ->assertJson(['error' => __('passwords.token')]);
        });
    });
});
