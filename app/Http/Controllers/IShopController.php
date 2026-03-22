<?php

namespace App\Http\Controllers;

use App\Models\Metin2\Account;
use App\Models\Metin2\ItemAward;
use App\Models\Web\ShopCategory;
use App\Models\Web\ShopItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IShopController extends Controller
{
    public function browse(Request $request): View
    {
        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        $activeCategory = $request->input('category', 'all');

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

        if ($activeCategory === 'all') {
            $displayCategories = $categories;
        } else {
            $displayCategories = $categories->filter(fn ($c) => $c->slug === $activeCategory);
        }

        $confirmItem = null;
        if ($request->has('buy')) {
            $confirmItem = ShopItem::query()
                ->where('is_active', true)
                ->find($request->integer('buy'));
        }

        return view('shop.ishop', [
            'account' => $account,
            'coins' => $account->cash ?? 0,
            'categories' => $categories,
            'displayCategories' => $displayCategories,
            'hotItems' => $hotItems,
            'activeCategory' => $activeCategory,
            'confirmItem' => $confirmItem,
        ]);
    }

    public function purchase(Request $request): RedirectResponse
    {
        $request->validate([
            'item_id' => ['required', 'integer'],
            'category' => ['nullable', 'string'],
        ]);

        /** @var Account $account */
        $account = Auth::guard('metin2')->user();
        $category = $request->input('category', 'all');

        $shopItem = ShopItem::query()
            ->where('is_active', true)
            ->find($request->input('item_id'));

        if (! $shopItem) {
            return redirect()->route('ishop.browse', ['category' => $category])
                ->with('purchase_error', __('shop_item_not_available'));
        }

        if ($account->cash < $shopItem->price) {
            return redirect()->route('ishop.browse', ['category' => $category])
                ->with('purchase_error', __('shop_not_enough_coins'));
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
                'why' => '[WEBSHOP]',
                'mall' => 1,
                'socket0' => 0,
                'socket1' => 0,
                'socket2' => 0,
            ]);

            DB::connection('account')->commit();

            return redirect()->route('ishop.browse', ['category' => $category])
                ->with('purchase_success', __('shop_purchase_success'));
        } catch (\Throwable $e) {
            DB::connection('account')->rollBack();

            return redirect()->route('ishop.browse', ['category' => $category])
                ->with('purchase_error', __('shop_purchase_failed'));
        }
    }
}
