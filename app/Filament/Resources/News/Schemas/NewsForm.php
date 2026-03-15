<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    /** @var array<string, string> */
    private const LOCALES = [
        'en' => '🇬🇧 English',
        'de' => '🇩🇪 Deutsch',
        'hu' => '🇭🇺 Magyar',
        'fr' => '🇫🇷 Français',
        'cs' => '🇨🇿 Čeština',
        'da' => '🇩🇰 Dansk',
        'es' => '🇪🇸 Español',
        'el' => '🇬🇷 Ελληνικά',
        'it' => '🇮🇹 Italiano',
        'nl' => '🇳🇱 Nederlands',
        'pl' => '🇵🇱 Polski',
        'pt' => '🇵🇹 Português',
        'ro' => '🇷🇴 Română',
        'ru' => '🇷🇺 Русский',
        'tr' => '🇹🇷 Türkçe',
    ];

    public static function configure(Schema $schema): Schema
    {
        $tabs = [];

        foreach (self::LOCALES as $locale => $label) {
            $tabs[] = Tab::make($label)
                ->schema([
                    TextInput::make("title.{$locale}")
                        ->label('Title')
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $state, callable $set) use ($locale) {
                            if ($locale === 'en') {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Textarea::make("excerpt.{$locale}")
                        ->label('Excerpt')
                        ->rows(3)
                        ->maxLength(500),

                    RichEditor::make("body.{$locale}")
                        ->label('Body')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline',
                            'bulletList', 'orderedList',
                            'h2', 'h3',
                            'link',
                        ]),
                ]);
        }

        return $schema->components([
            Tabs::make('Translations')
                ->tabs($tabs)
                ->columnSpanFull(),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            Toggle::make('is_published')
                ->label('Published')
                ->default(false),

            DateTimePicker::make('published_at')
                ->label('Publish date')
                ->default(now())
                ->native(false)
                ->seconds(false)
                ->nullable(),
        ]);
    }
}
