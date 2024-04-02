<?php

namespace Moox\Expiry\Resources\ExpiryMonitorResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Moox\Expiry\Resources\ExpiryMonitorResource;

class ListExpiryMonitors extends ListRecords
{
    protected static string $resource = ExpiryMonitorResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
