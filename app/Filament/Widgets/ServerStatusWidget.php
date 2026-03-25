<?php

namespace App\Filament\Widgets;

use App\Services\GameAdminSocket;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServerStatusWidget extends BaseWidget
{
    protected ?string $pollingInterval = '60s';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $socket = app(GameAdminSocket::class);
        $isUp = $socket->isServerUp();
        $players = $isUp ? $socket->getUserCount() : 0;

        return [
            Stat::make('Server Status', $isUp ? 'Online' : 'Offline')
                ->description($isUp ? 'Game server is reachable' : 'Cannot connect to game server')
                ->descriptionIcon($isUp ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                ->color($isUp ? 'success' : 'danger'),

            Stat::make('Players Online', (string) $players)
                ->description('Connected right now')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('send_notice')
                ->label('Send Notice')
                ->icon('heroicon-o-megaphone')
                ->color('warning')
                ->form([
                    Textarea::make('message')
                        ->label('Message')
                        ->required()
                        ->rows(2)
                        ->maxLength(200),
                ])
                ->action(function (array $data): void {
                    $success = app(GameAdminSocket::class)->sendNotice($data['message']);

                    if ($success) {
                        Notification::make()
                            ->title('Notice sent to all players')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Failed to send notice')
                            ->body('Could not connect to the game server.')
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('reload_quests')
                ->label('Reload Quests')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalDescription('This will reload all quests on the game server (RELOAD q).')
                ->action(function (): void {
                    $success = app(GameAdminSocket::class)->reloadQuests();

                    if ($success) {
                        Notification::make()
                            ->title('Quests reloaded')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Failed to reload quests')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
