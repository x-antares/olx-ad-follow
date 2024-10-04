<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_to_ad_by_unverified_email(): void
    {
        $user = User::create([
            'email' => 'test@app.com',
            'email_verified_at' => null,
        ]);

        $url = 'https://www.olx.ua/d/uk/obyavlenie/doplachuyu-300grn-shkaf-bezkoshtovno-shafa-IDVkQGe.html';

        $response = $this->post('/subscribe', [
            'email' => $user->email,
            'url' => $url,
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['email']);
    }

    public function test_subscription_to_ad_by_unknown_email(): void
    {
        $url = 'https://www.olx.ua/d/uk/obyavlenie/doplachuyu-300grn-shkaf-bezkoshtovno-shafa-IDVkQGe.html';

        $response = $this->post('/subscribe', [
            'email' => 'test@app.com',
            'url' => $url,
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['email']);
    }

    public function test_subscription_to_ad_with_invalid_url_by_email(): void
    {
        $url = 'http://test.com';

        $response = $this->post('/subscribe', [
            'email' => 'test@app.com',
            'url' => $url,
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['url']);
    }

    public function test_subscription_to_ad_by_invalid_email(): void
    {
        $user = User::create([
            'email' => 'test @app.com',
            'email_verified_at' => now()->toDateTimeString(),
        ]);
        $url = 'https://www.olx.ua/d/uk/obyavlenie/doplachuyu-300grn-shkaf-bezkoshtovno-shafa-IDVkQGe.html';

        $response = $this->post('/subscribe', [
            'email' => $user->email,
            'url' => $url,
        ]);

        $response->assertStatus(302);
        $response->assertInvalid(['email']);
    }

    public function test_subscription_to_ad_by_email_with_empty_params(): void
    {
        $response = $this->post('/subscribe', []);

        $response->assertStatus(302);
        $response->assertInvalid(['email', 'url']);
    }

    public function test_subscription_to_ad_by_email(): void
    {
        $user = User::create([
            'email' => 'test@app.com',
            'email_verified_at' => now()->toDateTimeString(),
        ]);
        $url = 'https://www.olx.ua/d/uk/obyavlenie/doplachuyu-300grn-shkaf-bezkoshtovno-shafa-IDVkQGe.html';

        $response = $this->post('/subscribe', [
            'email' => $user->email,
            'url' => $url,
        ]);

        $response->assertStatus(200);
    }

    public function test_subscription_to_ad_with_url_that_have_request_params_by_email(): void
    {
        $user = User::create([
            'email' => 'test@app.com',
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        $url = 'https://www.olx.ua/d/uk/obyavlenie/styki-peredn-v-sbor-z-pruzhinami-land-rover-freelander-1-IDUW9tl.html?reason=ip%7Ccool_rec_platform';

        $response = $this->post('/subscribe', [
            'email' => $user->email,
            'url' => $url,
        ]);

        $response->assertStatus(200);
    }
}
