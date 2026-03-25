<?php

namespace App\Filament\Resources\GameEvent;

use App\Filament\Resources\GameEvent\Pages\EditGameEvent;
use App\Filament\Resources\GameEvent\Pages\ListGameEvents;
use App\Filament\Resources\GameEvent\RelationManagers\LogsRelationManager;
use App\Filament\Resources\GameEvent\RelationManagers\SchedulesRelationManager;
use App\Filament\Resources\GameEvent\Schemas\GameEventForm;
use App\Filament\Resources\GameEvent\Tables\GameEventTable;
use App\Models\Web\GameEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GameEventResource extends Resource
{
    protected static ?string $model = GameEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static ?string $navigationLabel = 'Events';

    protected static ?string $modelLabel = 'Game Event';

    protected static ?string $pluralModelLabel = 'Game Events';

    protected static string|\UnitEnum|null $navigationGroup = 'Game Server';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return GameEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GameEventTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SchedulesRelationManager::class,
            LogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGameEvents::route('/'),
            'edit' => EditGameEvent::route('/{record}/edit'),
        ];
    }
}
