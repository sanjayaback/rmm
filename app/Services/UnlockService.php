<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\Unlock;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UnlockService
{
    public function __construct(private readonly KhaltiService $khalti) {}

    public function isUnlocked(User $user, Listing $listing): bool
    {
        return $user->hasUnlocked($listing->id);
    }

    public function initiate(User $user, Listing $listing): Unlock
    {
        $existing = Unlock::where('user_id', $user->id)
            ->where('listing_id', $listing->id)
            ->where('payment_status', 'completed')
            ->first();

        if ($existing) return $existing;

        return Unlock::updateOrCreate(
            ['user_id' => $user->id, 'listing_id' => $listing->id],
            [
                'amount_paid'    => $listing->unlock_fee,
                'payment_method' => $this->isFake() ? 'fake' : 'khalti',
                'payment_status' => 'pending',
                'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            ]
        );
    }

    public function processPayment(Unlock $unlock, string $token): array
    {
        if ($this->isFake()) return $this->fakePay($unlock);
        return $this->khaltiPay($unlock, $token);
    }

    public function complete(Unlock $unlock, array $data = []): Unlock
    {
        $unlock->markCompleted($data);
        Log::info('Listing unlocked', [
            'user_id'    => $unlock->user_id,
            'listing_id' => $unlock->listing_id,
            'amount'     => $unlock->amount_paid,
        ]);
        return $unlock;
    }

    public function getUserUnlocks(User $user)
    {
        return Unlock::with('listing')
            ->where('user_id', $user->id)
            ->where('payment_status', 'completed')
            ->latest()
            ->paginate(10);
    }

    // ── Private ───────────────────────────────────────────────────

    private function fakePay(Unlock $unlock): array
    {
        $data = [
            'idx'        => 'FAKE-' . strtoupper(Str::random(8)),
            'amount'     => $unlock->amount_paid * 100,
            'fake_mode'  => true,
        ];
        $unlock->update(['khalti_idx' => $data['idx'], 'payment_method' => 'fake']);
        $this->complete($unlock, $data);
        return ['success' => true, 'data' => $data];
    }

    private function khaltiPay(Unlock $unlock, string $token): array
    {
        try {
            $result = $this->khalti->verify($token, (int)($unlock->amount_paid * 100));
            if ($result['success']) {
                $unlock->update([
                    'khalti_token' => $token,
                    'khalti_idx'   => $result['data']['idx'] ?? null,
                ]);
                $this->complete($unlock, $result['data']);
            } else {
                $unlock->update(['payment_status' => 'failed']);
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('Khalti pay error', ['error' => $e->getMessage()]);
            $unlock->update(['payment_status' => 'failed']);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function isFake(): bool
    {
        return config('roomrent.khalti_fake_mode', true);
    }
}
