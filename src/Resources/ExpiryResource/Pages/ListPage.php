<?php

namespace Moox\Expiry\Resources\ExpiryResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Moox\Expiry\Models\Expiry;
use Moox\Expiry\Resources\ExpiryResource;
use Moox\Expiry\Resources\ExpiryResource\Widgets\ExpiryWidgets;

class ListPage extends ListRecords
{
    public static string $resource = ExpiryResource::class;

    public function getActions(): array
    {
        return [];
    }

    public function getHeaderWidgets(): array
    {
        return [
            ExpiryWidgets::class,
        ];
    }

    public function getTitle(): string
    {
        return __('expiry::translations.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->using(function (array $data, string $model): Expiry {
                    return $model::create($data);
                }),
        ];
    }
}
