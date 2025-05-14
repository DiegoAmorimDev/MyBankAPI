<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // app/Providers/AppServiceProvider.php
    public function register()
    {
        $this->app->bind(
            \Domain\Auth\Repositories\UserRepositoryInterface::class,
            \Infrastructure\Persistence\Mock\Repositories\UserMockRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
