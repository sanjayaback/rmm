<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KhaltiService
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('roomrent.khalti_secret_key', '');
        $this->baseUrl   = config('roomrent.khalti_base_url', 'https://a.khalti.com/api/v2');
    }

    /**
     * Verify a Khalti payment token (v1 widget).
     * @param string $token  Token from Khalti widget
     * @param int    $amount Amount in paisa (NPR × 100)
     */
    public function verify(string $token, int $amount): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->secretKey,
            ])->post($this->baseUrl . '/payment/verify/', [
                'token'  => $token,
                'amount' => $amount,
            ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            Log::warning('Khalti verify failed', ['status' => $response->status(), 'body' => $response->body()]);
            return ['success' => false, 'error' => $response->json('detail') ?? 'Payment verification failed'];

        } catch (\Exception $e) {
            Log::error('Khalti service error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Payment service unavailable'];
        }
    }

    /**
     * Initiate a Khalti ePay (v2) payment.
     */
    public function initiate(array $payload): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->secretKey,
            ])->post($this->baseUrl . '/epayment/initiate/', $payload);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }
            return ['success' => false, 'error' => $response->json('detail') ?? 'Initiation failed'];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Payment service unavailable'];
        }
    }

    /**
     * Look up a Khalti payment by pidx.
     */
    public function lookup(string $pidx): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->secretKey,
            ])->post($this->baseUrl . '/epayment/lookup/', ['pidx' => $pidx]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }
            return ['success' => false, 'error' => 'Lookup failed'];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Payment service unavailable'];
        }
    }
}
