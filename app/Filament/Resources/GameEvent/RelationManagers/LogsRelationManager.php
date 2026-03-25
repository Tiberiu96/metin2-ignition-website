<?php

namespace App\Filament\Resources\GameEvent\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogsRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    protected static ?string $title = 'Activity Log';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Time')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),

                TextColumn::make('action')
                    ->label('Action')
                    ->badge(),

                TextColumn::make('triggered_by')
                    ->label('Triggered by')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('admin.name')
                    ->label('Admin')
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25]);
    }
}
