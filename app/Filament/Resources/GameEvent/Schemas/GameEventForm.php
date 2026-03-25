<?php

namespace App\Filament\Resources\GameEvent\Schemas;

use App\Enums\EventCategory;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GameEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Event Details')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->helperText('Unique identifier, lowercase with underscores'),

                    Select::make('category')
                        ->label('Category')
                        ->options(EventCategory::class)
                        ->required(),

                    TextInput::make('quest_flag')
                        ->label('Primary Quest Flag')
                        ->required()
                        ->maxLength(80)
                        ->helperText('The flag name sent as: /eventflag <name> 1'),

                    Toggle::make('is_active')
                        ->label('Currently active')
                        ->disabled()
                        ->helperText('Use Activate / Deactivate actions from the event list'),

                    Textarea::make('description')
                        ->label('Description')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Default Parameters')
                ->description('Extra flags sent alongside the primary flag when this event is activated. Keys are game flag names, values are the values to set.')
                ->schema([
                    KeyValue::make('params')
                        ->label('')
                        ->keyLabel('Flag name')
                        ->valueLabel('Value')
                        ->addActionLabel('Add flag')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
