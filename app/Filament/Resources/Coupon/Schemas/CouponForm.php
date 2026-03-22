<?php

namespace App\Filament\Resources\Coupon\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')
                ->label('Coupon Code')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(50)
                ->suffixAction(
                    Action::make('generate')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (callable $set) {
                            $set('code', strtoupper(Str::random(10)));
                        })
                ),

            TextInput::make('coins')
                ->label('Coins')
                ->required()
                ->numeric()
                ->minValue(1)
                ->maxValue(500)
                ->helperText('Maximum 500 coins per coupon'),

            TextInput::make('max_uses')
                ->label('Max Total Uses')
                ->numeric()
                ->minValue(1)
                ->nullable()
                ->helperText('Total uses across all users. Leave empty for unlimited.'),

            TextInput::make('max_uses_per_user')
                ->label('Max Uses Per User')
                ->numeric()
                ->minValue(1)
                ->nullable()
                ->helperText('How many times a single user can redeem this. Leave empty for unlimited.'),

            DateTimePicker::make('expires_at')
                ->label('Expires At')
                ->native(false)
                ->nullable()
                ->helperText('Leave empty for no expiration'),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }
}
