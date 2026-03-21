<?php

namespace App\Http\Controllers;

use App\Models\Metin2\Account;
use App\Models\Metin2\ItemAward;
use App\Models\Web\ShopCategory;
use App\Models\Web\ShopItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        $categories = ShopCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['items' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->get();

        $hotItems = ShopItem::query()
            ->where('is_active', true)
            ->where('is_hot', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('shop.index', [
            'account' => $account,
            'categories' => $categories,
            'hotItems' => $hotItems,
            'coins' => $account->cash ?? 0,
        ]);
    }

    public function purchase(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer|exists:shop_items,id',
        ]);

        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        $shopItem = ShopItem::query()
            ->where('is_active', true)
            ->find($request->input('item_id'));

        if (! $shopItem) {
            return response()->json(['success' => false, 'message' => 'Item not available.'], 404);
        }

        if ($account->cash < $shopItem->price) {
            return response()->json(['success' => false, 'message' => 'Not enough coins.'], 400);
        }

        try {
            DB::connection('account')->beginTransaction();

            DB::connection('account')
                ->table('account')
                ->where('id', $account->id)
                ->decrement('cash', $shopItem->price);

            ItemAward::query()->create([
                'pid' => $account->id,
                'login' => $account->login,
                'vnum' => $shopItem->vnum,
                'count' => $shopItem->count,
                'given_time' => now(),
                'why' => 'webshop',
                'mall' => 1,
                'socket0' => 0,
                'socket1' => 0,
                'socket2' => 0,
            ]);

            DB::connection('account')->commit();

            $newBalance = $account->fresh()->cash;

            return response()->json([
                'success' => true,
                'message' => 'Purchase successful! Item will be delivered in-game.',
                'coins' => $newBalance,
            ]);
        } catch (\Throwable $e) {
            DB::connection('account')->rollBack();

            return response()->json(['success' => false, 'message' => 'Purchase failed. Please try again.'], 500);
        }
    }
}
