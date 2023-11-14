<?php

namespace Services\ChargeCode\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ChargeCodeServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->mapRoutes();
        $this->registerMigrations();
    }
    private function mapRoutes(): void
    {
        Route::middleware("api")->prefix("api")
            ->group(dirname(__DIR__) . DIRECTORY_SEPARATOR ."Routes".DIRECTORY_SEPARATOR."api.php");
    }
    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . DIRECTORY_SEPARATOR . "Database/Migrations");
    }
}
