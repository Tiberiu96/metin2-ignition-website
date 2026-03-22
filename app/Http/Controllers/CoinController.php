<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedeemCouponRequest;
use App\Models\Metin2\Account;
use App\Models\Web\CoinPackage;
use App\Services\CoinService;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CoinController extends Controller
{
    public function __construct(
        protected CoinService $coinService,
        protected CurrencyService $currencyService
    ) {}

    public function index(): View
    {
        /** @var Account $account */
        $account = Auth::guard('metin2')->user();
        $locale = app()->getLocale();

        $packages = CoinPackage::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function (CoinPackage $package) use ($locale) {
                $display = $this->currencyService->getDisplayPrice((float) $package->price_eur, $locale);

                return [
                    'id' => $package->id,
                    'coins' => $package->coins,
                    'price_eur' => $package->price_eur,
                    'display_price' => $display,
                ];
            });

        return view('shop.coins', [
            'account' => $account,
            'coins' => $account->cash ?? 0,
            'packages' => $packages,
            'locale' => $locale,
        ]);
    }

    public function redeemCoupon(RedeemCouponRequest $request): JsonResponse
    {
        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        try {
            $transaction = $this->coinService->redeemCoupon(
                $account,
                $request->validated('code'),
                $request->ip()
            );

            $newBalance = $account->fresh()->cash;

            return response()->json([
                'success' => true,
                'message' => __('coins_coupon_success', ['coins' => $transaction->coins]),
                'coins' => $newBalance,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function stripeCheckout(Request $request): JsonResponse
    {
        $request->validate([
            'package_id' => 'required|integer|exists:coin_packages,id',
        ]);

        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        $package = CoinPackage::query()
            ->where('is_active', true)
            ->find($request->input('package_id'));

        if (! $package) {
            return response()->json(['success' => false, 'message' => __('coins_package_unavailable')], 404);
        }

        try {
            $session = $this->coinService->createStripeCheckout(
                $account,
                $package,
                $request->ip()
            );

            return response()->json([
                'success' => true,
                'checkout_url' => $session->url,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('coins_stripe_error'),
            ], 500);
        }
    }

    public function stripeSuccess(Request $request): RedirectResponse
    {
        return redirect()->route('coins.index')->with('success', __('coins_purchase_success'));
    }

    public function stripeCancel(Request $request): RedirectResponse
    {
        return redirect()->route('coins.index')->with('info', __('coins_purchase_cancelled'));
    }

    public function stripeWebhook(Request $request): Response
    {
        try {
            $this->coinService->handleStripeWebhook(
                $request->getContent(),
                $request->header('Stripe-Signature', '')
            );

            return response('OK', 200);
        } catch (\Throwable $e) {
            return response('Webhook error', 400);
        }
    }
}
