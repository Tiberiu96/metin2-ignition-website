<?php

namespace App\Filament\Resources\CoinTransaction\Tables;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CoinTransactionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('account_id')
                    ->label('Account ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (TransactionType $state) => match ($state) {
                        TransactionType::Stripe => 'info',
                        TransactionType::Coupon => 'warning',
                    }),

                TextColumn::make('coins')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('amount_eur')
                    ->label('Amount (EUR)')
                    ->money('EUR')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('coupon_code')
                    ->label('Coupon')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (TransactionStatus $state) => match ($state) {
                        TransactionStatus::Pending => 'warning',
                        TransactionStatus::Completed => 'success',
                        TransactionStatus::Failed => 'danger',
                    }),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('stripe_session_id')
                    ->label('Stripe Session')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(20),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->options(TransactionType::class),

                SelectFilter::make('status')
                    ->options(TransactionStatus::class),
            ]);
    }
}
