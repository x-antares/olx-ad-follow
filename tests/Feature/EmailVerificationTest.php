<?php

namespace Tests\Feature;

use App\Models\TmpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_send_code_to_verified_email(): void
    {
        User::create([
            'email' => 'test@app.com',
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        $response = $this->post('/auth/send-code', ['email' => 'test@app.com']);

        $response->assertStatus(302);
        $response->assertInvalid(['email']);
    }

    public function test_verification_send_code_to_email_with_empty_params(): void
    {
        $response = $this->post('/auth/send-code', []);
        $response->assertStatus(302);
        $response->assertInvalid(['email']);
    }

    public function test_verification_send_code_to_email_with_not_valid_email(): void
    {
        $response = $this->post('/auth/send-code', ['email' => 'test @app.com']);
        $response->assertStatus(302);
        $response->assertInvalid(['email']);
    }

    public function test_verification_send_code_to_email(): void
    {
        $response = $this->post('/auth/send-code', ['email' => 'test@app.com']);

        $response->assertStatus(200);
    }



    public function test_verification_check_code_with_empty_params(): void
    {
        $response = $this->post('/auth/verify-email', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'code']);
    }

    public function test_verification_check_code_for_not_existing_email(): void
    {
        $response = $this->post('/auth/verify-email', ['email' => 'test@app.com', 'code' => 0000]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_verification_check_code_for_verified_email(): void
    {
        User::create([
            'email' => 'test@app.com',
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        $response = $this->post('/auth/verify-email', ['email' => 'test@app.com', 'code' => 0000]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_verification_check_expired_code_for_email(): void
    {
        $user = User::create([
            'email' => 'test@app.com',
            'email_verified_at' => null
        ]);

        $tmpCode = TmpCode::create([
            'user_id' => $user->id,
            'code' => random_int(1000, 9999),
            'used_at' => null,
            'created_at' => now()->subMinutes(120)->toDateTimeString(),
        ]);

        $response = $this->post('/auth/verify-email', ['email' => $user->email, 'code' => $tmpCode->code]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['code']);
    }

    public function test_verification_check_used_code_for_email(): void
    {
        $user = User::create([
            'email' => 'test@app.com',
            'email_verified_at' => null
        ]);

        $tmpCode = TmpCode::create([
            'user_id' => $user->id,
            'code' => random_int(1000, 9999),
            'used_at' => now()->toDateTimeString(),
        ]);

        $response = $this->post('/auth/verify-email', ['email' => $user->email, 'code' => $tmpCode->code]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['code']);
    }

    public function test_verification_check_code_for_email(): void
    {
        $user = User::create([
            'email' => 'test@app.com',
            'email_verified_at' => null
        ]);

        $tmpCode = TmpCode::create([
            'user_id' => $user->id,
            'code' => random_int(1000, 9999),
            'used_at' => null,
        ]);

        $response = $this->post('/auth/verify-email', ['email' => $user->email, 'code' => $tmpCode->code]);

        $response->assertStatus(200);
    }
}
