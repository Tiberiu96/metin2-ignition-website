<?php

namespace App\Filament\Resources\GameEvent\RelationManagers;

use App\Enums\RepeatType;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Schedules';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            DateTimePicker::make('start_at')
                ->label('Start at')
                ->required()
                ->native(false)
                ->seconds(false),

            DateTimePicker::make('stop_at')
                ->label('Stop at')
                ->native(false)
                ->seconds(false)
                ->nullable()
                ->helperText('Leave empty to activate without auto-stopping'),

            Select::make('repeat_type')
                ->label('Repeat')
                ->options(RepeatType::class)
                ->default(RepeatType::None)
                ->required(),

            KeyValue::make('params_override')
                ->label('Parameter overrides')
                ->keyLabel('Flag name')
                ->valueLabel('Value')
                ->addActionLabel('Add override')
                ->helperText('Leave empty to use the event default parameters')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_at')
                    ->label('Start')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('stop_at')
                    ->label('Stop')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                IconColumn::make('started')
                    ->label('Started')
                    ->boolean(),

                IconColumn::make('stopped')
                    ->label('Stopped')
                    ->boolean(),

                TextColumn::make('repeat_type')
                    ->label('Repeat')
                    ->badge(),

                TextColumn::make('creator.name')
                    ->label('Created by')
                    ->placeholder('—'),
            ])
            ->defaultSort('start_at', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                Action::make('reset')
                    ->label('Reset')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription('Reset the started/stopped flags so this schedule will run again.')
                    ->action(fn ($record) => $record->update(['started' => false, 'stopped' => false])),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
