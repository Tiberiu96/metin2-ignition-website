<?php

namespace App\Filament\Resources\ShopCategory\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ShopCategoryForm
{
    /** @var array<string, string> */
    private const LOCALES = [
        'en' => "\u{1F1EC}\u{1F1E7} English",
        'de' => "\u{1F1E9}\u{1F1EA} Deutsch",
        'hu' => "\u{1F1ED}\u{1F1FA} Magyar",
        'fr' => "\u{1F1EB}\u{1F1F7} Francais",
        'cs' => "\u{1F1E8}\u{1F1FF} Cestina",
        'da' => "\u{1F1E9}\u{1F1F0} Dansk",
        'es' => "\u{1F1EA}\u{1F1F8} Espanol",
        'el' => "\u{1F1EC}\u{1F1F7} Ellinika",
        'it' => "\u{1F1EE}\u{1F1F9} Italiano",
        'nl' => "\u{1F1F3}\u{1F1F1} Nederlands",
        'pl' => "\u{1F1F5}\u{1F1F1} Polski",
        'pt' => "\u{1F1F5}\u{1F1F9} Portugues",
        'ro' => "\u{1F1F7}\u{1F1F4} Romana",
        'ru' => "\u{1F1F7}\u{1F1FA} Russkij",
        'tr' => "\u{1F1F9}\u{1F1F7} Turkce",
    ];

    public static function configure(Schema $schema): Schema
    {
        $tabs = [];

        foreach (self::LOCALES as $locale => $label) {
            $tabs[] = Tab::make($label)
                ->schema([
                    TextInput::make("name.{$locale}")
                        ->label('Name')
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $state, callable $set) use ($locale) {
                            if ($locale === 'en') {
                                $set('slug', Str::slug($state));
                            }
                        }),
                ]);
        }

        return $schema->components([
            Tabs::make('Translations')
                ->tabs($tabs)
                ->columnSpanFull(),

            TextInput::make('slug')
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            TextInput::make('icon')
                ->label('Icon (emoji or class)')
                ->maxLength(50),

            TextInput::make('sort_order')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }
}
