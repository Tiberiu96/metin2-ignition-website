<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Metin2\Account;
use App\Models\Web\CoinPackage;
use App\Models\Web\CoinTransaction;
use App\Models\Web\Coupon;
use App\Models\Web\CouponUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\StripeClient;
use Stripe\Webhook;

class CoinService
{
    public function redeemCoupon(Account $account, string $code, string $ip): CoinTransaction
    {
        $code = strtoupper(trim($code));

        return DB::connection('mysql')->transaction(function () use ($account, $code, $ip) {
            $coupon = Coupon::query()
                ->where('code', $code)
                ->lockForUpdate()
                ->first();

            if (! $coupon || ! $coupon->isValid()) {
                throw new \RuntimeException(__('coins_coupon_invalid'));
            }

            if ($coupon->max_uses_per_user !== null) {
                $usage = CouponUsage::query()
                    ->where('coupon_id', $coupon->id)
                    ->where('account_id', $account->id)
                    ->lockForUpdate()
                    ->first();

                if ($usage && $usage->times_used >= $coupon->max_uses_per_user) {
                    throw new \RuntimeException(__('coins_coupon_used_up'));
                }
            }

            $coupon->incrementUsage();

            $usage = CouponUsage::query()
                ->where('coupon_id', $coupon->id)
                ->where('account_id', $account->id)
                ->first();

            if ($usage) {
                $usage->increment('times_used');
            } else {
                CouponUsage::query()->create([
                    'coupon_id' => $coupon->id,
                    'account_id' => $account->id,
                    'times_used' => 1,
                ]);
            }

            $transaction = CoinTransaction::query()->create([
                'account_id' => $account->id,
                'type' => TransactionType::Coupon,
                'coins' => $coupon->coins,
                'coupon_code' => $coupon->code,
                'status' => TransactionStatus::Completed,
                'ip_address' => $ip,
            ]);

            $this->creditCoins($account->id, $coupon->coins);

            return $transaction;
        });
    }

    // Currencies Stripe treats as zero-decimal (no subunits)
    private const ZERO_DECIMAL_CURRENCIES = ['HUF', 'JPY', 'KRW', 'VND', 'BIF', 'CLP', 'GNF', 'MGA', 'PYG', 'RWF', 'UGX', 'VUV', 'XAF', 'XOF', 'XPF'];

    public function createStripeCheckout(Account $account, CoinPackage $package, string $ip, string $locale = 'en'): StripeSession
    {
        try {
            $displayPrice = app(CurrencyService::class)->getDisplayPrice((float) $package->price_eur, $locale);
            $currency = $displayPrice['currency'];
            $amount = $displayPrice['amount'];
        } catch (\Throwable) {
            $currency = 'EUR';
            $amount = (float) $package->price_eur;
        }

        $isZeroDecimal = in_array($currency, self::ZERO_DECIMAL_CURRENCIES);
        $unitAmount = $isZeroDecimal ? (int) round($amount) : (int) round($amount * 100);

        $transaction = CoinTransaction::query()->create([
            'account_id' => $account->id,
            'type' => TransactionType::Stripe,
            'coins' => $package->coins,
            'amount_eur' => $package->price_eur,
            'currency' => $currency,
            'status' => TransactionStatus::Pending,
            'ip_address' => $ip,
        ]);

        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => "{$package->coins} Coins - Metin2 Ignition",
                    ],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'locale' => $locale,
            'success_url' => route('coins.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('coins.stripe.cancel'),
            'metadata' => [
                'transaction_id' => $transaction->id,
                'account_id' => $account->id,
                'coins' => $package->coins,
            ],
        ]);

        $transaction->update(['stripe_session_id' => $session->id]);

        return $session;
    }

    public function handleStripeWebhook(string $payload, string $signature): void
    {
        $event = Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );

        if ($event->type !== 'checkout.session.completed') {
            return;
        }

        $session = $event->data->object;
        $transactionId = $session->metadata->transaction_id ?? null;

        if (! $transactionId) {
            Log::warning('Stripe webhook: missing transaction_id in metadata');

            return;
        }

        $transaction = CoinTransaction::query()->find($transactionId);

        if (! $transaction) {
            Log::warning("Stripe webhook: transaction {$transactionId} not found");

            return;
        }

        if ($transaction->status === TransactionStatus::Completed) {
            return;
        }

        $this->creditCoins($transaction->account_id, $transaction->coins);

        $transaction->update(['status' => TransactionStatus::Completed]);
    }

    protected function creditCoins(int $accountId, int $coins): void
    {
        DB::connection('account')
            ->table('account')
            ->where('id', $accountId)
            ->increment('cash', $coins);
    }
}
