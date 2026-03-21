<?php

namespace App\Filament\Resources\ShopItem\Schemas;

use App\Models\Metin2\ItemProto;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class ShopItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('shop_category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Select::make('vnum')
                ->label('Game Item')
                ->helperText(new HtmlString('<a href="'.route('filament.admin.pages.item-browser').'" target="_blank" class="text-primary-600 hover:underline">Browse all game items &rarr;</a>'))
                // Filament Select renders max ~57 options in dropdown; search queries all cached items
                ->options(fn (): array => static::getItemsWithIcons(collect(static::getCachedItems())->take(57)))
                ->searchable()
                ->required()
                ->live()
                ->allowHtml()
                ->getSearchResultsUsing(function (string $search): array {
                    $q = mb_strtolower($search);

                    return static::getItemsWithIcons(
                        collect(static::getCachedItems())
                            ->filter(fn (string $label, int $vnum): bool => str_contains(mb_strtolower($label), $q) || str_contains((string) $vnum, $search))
                            ->take(100)
                    );
                })
                ->getOptionLabelUsing(function (mixed $value): ?string {
                    $items = static::getCachedItems();
                    $label = $items[(int) $value] ?? null;

                    if (! $label) {
                        return null;
                    }

                    $iconUrl = static::getIconUrl((int) $value);

                    return '<span style="display:flex;align-items:center;gap:8px">'.e($label).'<img src="'.e($iconUrl).'" loading="lazy" style="width:20px;height:20px;object-fit:contain" onerror="this.style.display=\'none\'" /></span>';
                })
                ->afterStateUpdated(function (callable $set, ?string $state): void {
                    if ($state) {
                        $items = static::getCachedItems();
                        $label = $items[(int) $state] ?? '';
                        $name = preg_replace('/^\[\d+\]\s*/', '', $label);
                        $set('name', $name);
                    }
                }),

            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->helperText('Auto-filled from item selection. You can customize it.'),

            Textarea::make('description')
                ->rows(2)
                ->maxLength(500),

            TextInput::make('price')
                ->label('Price (coins)')
                ->required()
                ->numeric()
                ->minValue(1),

            TextInput::make('price_original')
                ->label('Original price (for discount display)')
                ->numeric()
                ->minValue(1),

            TextInput::make('count')
                ->label('Item count')
                ->numeric()
                ->default(1)
                ->minValue(1),

            TextInput::make('sort_order')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),

            Toggle::make('is_hot')
                ->label('Hot item (featured)')
                ->default(false),
        ]);
    }

    private static function getIconUrl(int $vnum): string
    {
        $iconsUrl = rtrim(config('services.shop.icons_url', ''), '/');
        $iconsPath = config('services.shop.icons_path', '/var/www/patches/webshop_icons');

        $candidates = [
            str_pad((string) $vnum, 5, '0', STR_PAD_LEFT),
            (string) $vnum,
        ];

        $baseVnum = (int) floor($vnum / 10) * 10;
        if ($baseVnum !== $vnum) {
            $candidates[] = str_pad((string) $baseVnum, 5, '0', STR_PAD_LEFT);
            $candidates[] = (string) $baseVnum;
        }

        foreach ($candidates as $name) {
            if (file_exists($iconsPath.'/'.$name.'.png')) {
                return $iconsUrl.'/'.$name.'.png';
            }
        }

        return $iconsUrl.'/'.str_pad((string) $vnum, 5, '0', STR_PAD_LEFT).'.png';
    }

    /**
     * @param  Collection<int, string>  $items
     * @return array<int, string>
     */
    private static function getItemsWithIcons(Collection $items): array
    {
        return $items->map(function (string $label, int $vnum): string {
            $iconUrl = static::getIconUrl($vnum);

            return '<span style="display:flex;align-items:center;gap:8px">'.e($label).'<img src="'.e($iconUrl).'" loading="lazy" style="width:20px;height:20px;object-fit:contain" onerror="this.style.display=\'none\'" /></span>';
        })->all();
    }

    /**
     * @return array<int, string>
     */
    private static function getCachedItems(): array
    {
        return Cache::remember('item_proto_select', 3600, function (): array {
            return ItemProto::query()
                ->whereNotIn('vnum', [1, 2])
                ->orderBy('vnum')
                ->pluck('locale_name', 'vnum')
                ->map(function (string $name, int $vnum): string {
                    if (! mb_check_encoding($name, 'UTF-8')) {
                        $name = mb_convert_encoding($name, 'UTF-8', 'EUC-KR');
                    }

                    return "[{$vnum}] {$name}";
                })
                ->all();
        });
    }
}
