<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Item Shop</title>
<style>
body { margin: 0; padding: 0; background: #0a0608; color: #d4b8b8; font-family: Arial, sans-serif; font-size: 12px; }
a { color: #d4b8b8; text-decoration: none; }
a:hover { color: #e03030; }
img { border: 0; }
table { border-collapse: collapse; }

.header { background: #120a0a; border-bottom: 1px solid #2e1414; padding: 6px 10px; }
.header td { vertical-align: middle; }
.username { color: #e03030; font-weight: bold; font-size: 13px; }
.coins { color: #ffd700; font-weight: bold; font-size: 14px; }
.coins-label { color: #7a5555; font-size: 10px; }

.cats { background: #120a0a; border-bottom: 1px solid #2e1414; padding: 4px 10px; }
.cat-link { color: #7a5555; font-size: 11px; padding: 2px 6px; }
.cat-link:hover { color: #d4b8b8; }
.cat-active { color: #e03030; font-weight: bold; }

.wrap { padding: 6px 10px; }

.sec-title { color: #e03030; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; padding: 6px 0 4px 0; border-bottom: 1px solid #2e1414; margin-bottom: 2px; }

.itbl { width: 100%; }
.itbl td { padding: 6px 4px; border-bottom: 1px solid #1a0e0e; vertical-align: middle; }
.itbl tr:hover td { background: #140c0c; }

.iicon { width: 34px; height: 34px; background: #080608; border: 1px solid #2e1414; text-align: center; }
.iicon img { width: 32px; height: 32px; }

.iname { font-weight: bold; color: #e8d8d8; font-size: 12px; }
.idesc { color: #5a3535; font-size: 10px; }
.icnt { color: #aaa; font-size: 10px; font-weight: bold; }

.iprice { color: #ffd700; font-weight: bold; font-size: 13px; text-align: right; white-space: nowrap; }
.iprice-old { color: #5a3535; font-size: 10px; text-decoration: line-through; }

.btn-buy { background: #8b0000; color: #fff; border: 1px solid #c01e1e; padding: 3px 10px; font-size: 10px; font-weight: bold; cursor: pointer; }
.btn-buy:hover { background: #a01818; }

.hot { background: #8b0000; color: #fff; font-size: 8px; font-weight: bold; padding: 1px 4px; }

.msg-ok { background: #0f1a0f; border: 1px solid #1a3a1a; color: #5cb85c; padding: 6px 10px; font-size: 11px; font-weight: bold; margin-bottom: 6px; }
.msg-err { background: #1a0f0f; border: 1px solid #3a1a1a; color: #cc5555; padding: 6px 10px; font-size: 11px; font-weight: bold; margin-bottom: 6px; }

.cbox { background: #120a0a; border: 1px solid #2e1414; padding: 16px; margin-bottom: 10px; }
.cbox h3 { font-size: 13px; color: #e8d8d8; margin: 0 0 10px 0; }
.cbox-item { background: #0a0608; border: 1px solid #2e1414; padding: 8px; margin-bottom: 12px; }
.btn-cancel { background: #1a1010; color: #7a5555; border: 1px solid #2e1414; padding: 4px 12px; font-size: 11px; cursor: pointer; }
.btn-cancel:hover { background: #2e1414; }
.btn-ok { background: #8b0000; color: #fff; border: 1px solid #c01e1e; padding: 4px 12px; font-size: 11px; font-weight: bold; cursor: pointer; }
.btn-ok:hover { background: #a01818; }

.empty { text-align: center; color: #5a3535; padding: 40px 0; }
</style>
</head>
<body>

<div class="header">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td><span class="username">{{ $account->login }}</span></td>
<td align="right"><span class="coins">{{ number_format($coins) }}</span> <span class="coins-label">Coins</span></td>
</tr>
</table>
</div>

<div class="cats">
<a href="{{ route('ishop.browse', ['category' => 'all']) }}" class="cat-link {{ $activeCategory === 'all' ? 'cat-active' : '' }}">All</a>
@foreach($categories as $category)
 | <a href="{{ route('ishop.browse', ['category' => $category->slug]) }}" class="cat-link {{ $activeCategory === $category->slug ? 'cat-active' : '' }}">{{ $category->name }}</a>
@endforeach
</div>

<div class="wrap">

@if(session('purchase_success'))
<div class="msg-ok">{{ session('purchase_success') }}</div>
@endif
@if(session('purchase_error'))
<div class="msg-err">{{ session('purchase_error') }}</div>
@endif

@if(isset($confirmItem))
<div class="cbox">
<h3>{{ __('shop_confirm_purchase') }}</h3>
<div class="cbox-item">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td><b>{{ $confirmItem->name }}</b>
@if($confirmItem->count > 1) <span class="icnt">x{{ $confirmItem->count }}</span>@endif
</td>
<td align="right"><span class="iprice">{{ number_format($confirmItem->price) }}</span></td>
</tr>
</table>
</div>
<div align="right">
<a href="{{ route('ishop.browse', ['category' => $activeCategory]) }}"><button type="button" class="btn-cancel">{{ __('shop_cancel') }}</button></a>
<form method="POST" action="{{ route('ishop.purchase') }}" style="display: inline;">
@csrf
<input type="hidden" name="item_id" value="{{ $confirmItem->id }}">
<input type="hidden" name="category" value="{{ $activeCategory }}">
<button type="submit" class="btn-ok">{{ __('shop_confirm') }}</button>
</form>
</div>
</div>
@endif

@if($activeCategory === 'all' && $hotItems->isNotEmpty())
<div class="sec-title">{{ __('shop_popular_items') }}</div>
<table class="itbl" cellpadding="0" cellspacing="0">
@foreach($hotItems as $item)
<tr>
<td style="width:38px"><div class="iicon">@if($item->icon_url)<img src="{{ $item->icon_url }}" alt="">@endif</div></td>
<td>
<span class="iname">{{ $item->name }}</span> <span class="hot">HOT</span>
@if($item->count > 1) <span class="icnt">x{{ $item->count }}</span>@endif
@if($item->description)<br><span class="idesc">{{ $item->description }}</span>@endif
</td>
<td style="width:60px" class="iprice">
@if($item->price_original && $item->price_original > $item->price)<span class="iprice-old">{{ number_format($item->price_original) }}</span><br>@endif
{{ number_format($item->price) }}
</td>
<td style="width:50px" align="right">
<a href="{{ route('ishop.browse', ['category' => $activeCategory, 'buy' => $item->id]) }}"><button class="btn-buy">BUY</button></a>
</td>
</tr>
@endforeach
</table>
@endif

@foreach($displayCategories as $category)
@if($category->items->isNotEmpty())
<div class="sec-title">{{ $category->name }}</div>
<table class="itbl" cellpadding="0" cellspacing="0">
@foreach($category->items as $item)
<tr>
<td style="width:38px"><div class="iicon">@if($item->icon_url)<img src="{{ $item->icon_url }}" alt="">@endif</div></td>
<td>
<span class="iname">{{ $item->name }}</span>
@if($item->is_hot) <span class="hot">HOT</span>@endif
@if($item->count > 1) <span class="icnt">x{{ $item->count }}</span>@endif
@if($item->description)<br><span class="idesc">{{ $item->description }}</span>@endif
</td>
<td style="width:60px" class="iprice">
@if($item->price_original && $item->price_original > $item->price)<span class="iprice-old">{{ number_format($item->price_original) }}</span><br>@endif
{{ number_format($item->price) }}
</td>
<td style="width:50px" align="right">
<a href="{{ route('ishop.browse', ['category' => $activeCategory, 'buy' => $item->id]) }}"><button class="btn-buy">BUY</button></a>
</td>
</tr>
@endforeach
</table>
@endif
@endforeach

@if($displayCategories->isEmpty() || $displayCategories->every(fn($c) => $c->items->isEmpty()))
<div class="empty">{{ __('shop_no_items') }}</div>
@endif

</div>
</body>
</html>
