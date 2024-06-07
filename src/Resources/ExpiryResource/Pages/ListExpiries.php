<?php

namespace Moox\Expiry\Resources\ExpiryResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Moox\Expiry\Resources\ExpiryResource;

class ListExpiries extends ListRecords
{
    protected static string $resource = ExpiryResource::class;
}
