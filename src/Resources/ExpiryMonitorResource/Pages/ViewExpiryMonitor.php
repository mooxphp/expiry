<?php

namespace Moox\Expiry\Resources\ExpiryMonitorResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Moox\Expiry\Resources\ExpiryMonitorResource;

class ViewExpiryMonitor extends ViewRecord
{
    protected static string $resource = ExpiryMonitorResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make(), DeleteAction::make()];
    }
}
