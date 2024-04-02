<?php

declare(strict_types=1);

namespace Moox\Expiry;

use Moox\Expiry\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ExpiryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('expiry')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasCommand(InstallCommand::class);
    }

    public function boot()
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations/01_create_expiry_monitors_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_01_create_expiry_monitors_table.php'),
            ], 'expiry-migrations');

            $this->publishes([
                __DIR__.'/../database/migrations/02_create_expiries_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_02_create_expiries_table.php'),
            ], 'expiry-migrations');

            $this->publishes([
                __DIR__.'/../database/migrations/03_add_foreigns_to_expiries_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_04_add_foreigns_to_expiries_table.php'),
            ], 'expiry-migrations');
        }
    }
}
