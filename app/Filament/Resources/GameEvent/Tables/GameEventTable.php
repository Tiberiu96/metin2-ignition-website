<?php

namespace App\Filament\Resources\GameEvent\Tables;

use App\Enums\EventCategory;
use App\Models\Web\GameEvent;
use App\Services\EventService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GameEventTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('name')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge(),

                TextColumn::make('quest_flag')
                    ->label('Primary flag')
                    ->fontFamily('mono')
                    ->color('gray'),

                TextColumn::make('logs_count')
                    ->label('Logs')
                    ->counts('logs')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Last updated')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('category')
                    ->options(EventCategory::class),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                Action::make('activate')
                    ->label('Activate')
                    ->icon(Heroicon::OutlinedPlay)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (GameEvent $record) => "Activate: {$record->name}")
                    ->modalDescription('This will send the event flags to the game server immediately.')
                    ->visible(fn (GameEvent $record) => ! $record->is_active)
                    ->action(function (GameEvent $record): void {
                        $success = app(EventService::class)->activate($record);

                        if ($success) {
                            Notification::make()
                                ->title("Event activated: {$record->name}")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Failed to activate event')
                                ->body('Could not connect to the game server. Check logs for details.')
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon(Heroicon::OutlinedStop)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (GameEvent $record) => "Deactivate: {$record->name}")
                    ->modalDescription('This will set the primary event flag to 0 on the game server.')
                    ->visible(fn (GameEvent $record) => $record->is_active)
                    ->action(function (GameEvent $record): void {
                        $success = app(EventService::class)->deactivate($record);

                        if ($success) {
                            Notification::make()
                                ->title("Event deactivated: {$record->name}")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Failed to deactivate event')
                                ->body('Could not connect to the game server. Check logs for details.')
                                ->danger()
                                ->send();
                        }
                    }),

                EditAction::make(),
            ]);
    }
}
