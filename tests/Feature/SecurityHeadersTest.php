<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_responses_contain_required_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy');
    }

    public function test_otp_verification_route_has_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/verify-otp', ['otp' => '000000']);
        }

        $response = $this->post('/verify-otp', ['otp' => '000000']);
        $response->assertStatus(429); // Too Many Requests
    }
}
