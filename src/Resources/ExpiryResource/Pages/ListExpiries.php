<?php

namespace Moox\Expiry\Resources\ExpiryResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Moox\Expiry\Resources\ExpiryResource;

class ListExpiries extends ListRecords
{
    protected static string $resource = ExpiryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
