<?php

namespace Moox\Expiry\Resources\ExpiryResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Moox\Expiry\Models\Expiry;

class ExpiryWidgets extends BaseWidget
{
    protected function getCards(): array
    {
        $aggregationColumns = [
            DB::raw('COUNT(*) as count'),
            DB::raw('COUNT(*) as count'),
            DB::raw('COUNT(*) as count'),
        ];

        $aggregatedInfo = Expiry::query()
            ->select($aggregationColumns)
            ->first();

        return [
            Stat::make(__('expiry::translations.totalone'), $aggregatedInfo->count ?? 0),
            Stat::make(__('expiry::translations.totaltwo'), $aggregatedInfo->count ?? 0),
            Stat::make(__('expiry::translations.totalthree'), $aggregatedInfo->count ?? 0),
        ];
    }
}
