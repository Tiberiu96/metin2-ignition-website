<?php

namespace App\Filament\Pages;

use App\Models\Metin2\ItemProto;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class ItemBrowser extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = null;

    protected static string|\UnitEnum|null $navigationGroup = 'Item Shop';

    protected static ?string $navigationLabel = 'Item Browser';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Game Item Browser';

    protected static ?string $slug = 'item-browser';

    protected string $view = 'filament.pages.item-browser';

    private const TYPE_NAMES = [
        0 => 'None', 1 => 'Weapon', 2 => 'Armor', 3 => 'Use', 4 => 'Auto-use',
        5 => 'Material', 6 => 'Special', 7 => 'Tool', 8 => 'Chest', 9 => 'Yang',
        10 => 'Metin Stone', 11 => 'Container', 12 => 'Fish', 13 => 'Rod',
        14 => 'Resource', 15 => 'Campfire', 16 => 'Unique', 17 => 'Skillbook',
        18 => 'Quest', 19 => 'Polymorph', 20 => 'Treasure Box', 21 => 'Treasure Key',
        22 => 'Skill Forget', 23 => 'Gift Box', 24 => 'Pickaxe', 25 => 'Hair',
        26 => 'Totem', 27 => 'Blend', 28 => 'Costume', 29 => 'Dragon Soul',
        30 => 'Special DS', 31 => 'Extract', 32 => 'Secondary Coin', 33 => 'Ring', 34 => 'Belt',
    ];

    /** @var array<int, array<int, string>> */
    private const SUBTYPE_NAMES = [
        1 => [0 => 'Sword', 1 => 'Dagger', 2 => 'Bow', 3 => 'Two-handed', 4 => 'Bell', 5 => 'Fan', 6 => 'Arrow', 7 => 'Mount Spear', 8 => 'Claw'],
        2 => [0 => 'Body', 1 => 'Helmet', 2 => 'Shield', 3 => 'Bracelet', 4 => 'Shoes', 5 => 'Necklace', 6 => 'Earrings'],
        3 => [0 => 'Potion', 1 => 'Talisman', 2 => 'Tuning', 3 => 'Teleport', 4 => 'Treasure Box', 5 => 'Money Bag', 6 => 'Bait', 7 => 'Ability Up', 8 => 'Affect', 9 => 'Create Stone', 10 => 'Special', 11 => 'Potion (instant)', 12 => 'Clear', 13 => 'Invisibility', 14 => 'Detach Stone', 16 => 'Potion (cont.)', 17 => 'Clean Socket', 18 => 'Change Bonus', 19 => 'Add Bonus', 20 => 'Add Acc. Socket', 21 => 'Put Into Socket', 22 => 'Add Bonus (rare)', 23 => 'Recipe', 24 => 'Change Bonus (rare)'],
        10 => [0 => 'Normal', 1 => 'Gold'],
        12 => [0 => 'Alive', 1 => 'Dead'],
        16 => [0 => 'Default', 1 => 'Book', 2 => 'Special Mount', 5 => 'Special'],
        28 => [0 => 'Body', 1 => 'Hair', 2 => 'Mount', 3 => 'Sash/Wing', 4 => 'Weapon Skin', 5 => 'Aura'],
        29 => [0 => 'Slot 1', 1 => 'Slot 2', 2 => 'Slot 3', 3 => 'Slot 4', 4 => 'Slot 5', 5 => 'Slot 6'],
    ];

    public function getViewData(): array
    {
        $iconsUrl = rtrim(config('services.shop.icons_url', ''), '/');
        $iconsPath = config('services.shop.icons_path', '/var/www/patches/webshop_icons');

        $browserItems = Cache::remember('item_proto_browser', 3600, function () use ($iconsUrl, $iconsPath): array {
            return ItemProto::query()
                ->whereNotIn('vnum', [1, 2])
                ->orderBy('vnum')
                ->get(['vnum', 'locale_name', 'type', 'subtype'])
                ->map(function (object $row) use ($iconsUrl, $iconsPath): array {
                    $name = $row->locale_name;
                    if (! mb_check_encoding($name, 'UTF-8')) {
                        $name = mb_convert_encoding($name, 'UTF-8', 'EUC-KR');
                    }

                    $type = (int) $row->type;
                    $subtype = (int) $row->subtype;
                    $typeName = self::TYPE_NAMES[$type] ?? 'Unknown';
                    $subtypeName = self::SUBTYPE_NAMES[$type][$subtype] ?? '';

                    $vnum = (int) $row->vnum;
                    $icon = self::resolveIconUrl($vnum, $iconsUrl, $iconsPath);

                    return [
                        'vnum' => $vnum,
                        'name' => $name,
                        'type' => $typeName,
                        'subtype' => $subtypeName,
                        'icon' => $icon,
                    ];
                })
                ->values()
                ->all();
        });

        return [
            'items' => $browserItems,
        ];
    }

    private static function resolveIconUrl(int $vnum, string $iconsUrl, string $iconsPath): string
    {
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
}
