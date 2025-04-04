<?php

namespace Moox\Expiry\Resources\ExpiryResource\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Moox\Core\Traits\SoftDelete\SingleSoftDeleteInListPage;
use Moox\Core\Traits\Tabs\HasListPageTabs;
use Moox\Expiry\Models\Expiry;
use Moox\Expiry\Resources\ExpiryResource;

class ListExpiries extends ListRecords
{
    use HasListPageTabs;
    use SingleSoftDeleteInListPage;

    protected static string $resource = ExpiryResource::class;

    protected function getHeaderActions(): array
    {
        return config('expiry.collect_expiries_action')
            ? [
                Action::make('collectExpiries')
                    ->label(__('core::expiry.update_expiries'))
                    ->requiresConfirmation()
                    ->action(function (): void {
                        self::collectExpiries();
                    }),
            ]
            : [];
    }

    public static function collectExpiries(): void
    {
        $jobs = config('expiry.collect_expiries_jobs', []);
        foreach ($jobs as $jobClass) {
            dispatch(new $jobClass);
        }

        Notification::make()
            ->title(__('core::expiry.updating_started'))
            ->success()
            ->send();
    }

    public function getTabs(): array
    {
        return $this->getDynamicTabs('expiry.resources.expiry.tabs', Expiry::class);
    }
}
